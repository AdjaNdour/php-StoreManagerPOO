<?php

namespace App\Service;

use Adja\Core\Database;
use App\Model\Entity\Vente;
use App\Model\Entity\Dette;
use App\Model\Entity\Produit;
use App\Model\Entity\Approvisionnement;
use App\Model\Entity\Client;
use stdClass;

class DashboardService
{
    public static function getKpis(): array
    {
        $sqlVentesJour = "SELECT COALESCE(SUM(montant_total), 0) AS ca_jour, COUNT(*) AS nbr_ventes
                          FROM ventes 
                          WHERE date_vente = CURRENT_DATE";
        $resVentesJour = Database::query($sqlVentesJour, true);

        $sqlVentesMois = "SELECT COALESCE(SUM(montant_total), 0) AS ca_mois
                          FROM ventes 
                          WHERE EXTRACT(MONTH FROM date_vente) = EXTRACT(MONTH FROM CURRENT_DATE)
                            AND EXTRACT(YEAR FROM date_vente) = EXTRACT(YEAR FROM CURRENT_DATE)";
        $resVentesMois = Database::query($sqlVentesMois, true);

        $sqlDettes = "SELECT COALESCE(SUM(montant_restant), 0) AS total_dettes, COUNT(*) AS nbr_dettes
                      FROM dettes 
                      WHERE montant_restant > 0";
        $resDettes = Database::query($sqlDettes, true);

        $sqlDettesRetard = "SELECT COALESCE(SUM(montant_restant), 0) AS total_retard, COUNT(*) AS nbr_retard
                            FROM dettes 
                            WHERE montant_restant > 0 AND date_echeance < CURRENT_DATE";
        $resDettesRetard = Database::query($sqlDettesRetard, true);

        $sqlProduits = "SELECT COUNT(*) AS total_produits,
                               SUM(CASE WHEN stock_initial <= seuil_alerte THEN 1 ELSE 0 END) AS alertes_stock,
                               SUM(CASE WHEN stock_initial = 0 THEN 1 ELSE 0 END) AS ruptures_stock,
                               COALESCE(SUM(stock_initial * cout_achat), 0) AS valeur_stock
                        FROM produits";
        $resProduits = Database::query($sqlProduits, true);

        $sqlAppros = "SELECT COUNT(*) AS bl_attente, COALESCE(SUM(cout_achat), 0) AS cout_attente
                      FROM approvisionnements
                      WHERE date_reception IS NULL";
        $resAppros = Database::query($sqlAppros, true);

        $sqlClients = "SELECT COUNT(*) AS total_clients FROM clients";
        $resClients = Database::query($sqlClients, true);

        $sqlFournisseurs = "SELECT COUNT(*) AS total_fournisseurs FROM fournisseurs";
        $resFournisseurs = Database::query($sqlFournisseurs, true);

        $valeurStock = (float)($resProduits->valeur_stock ?? 0);
        $caJour = (float)($resVentesJour->ca_jour ?? 0);
        $totalDettes = (float)($resDettes->total_dettes ?? 0);
        $coutAttente = (float)($resAppros->cout_attente ?? 0);

        return [
            'ca_jour' => $caJour,
            'ventesComptant' => $caJour,
            'nbr_ventes_jour' => (int)($resVentesJour->nbr_ventes ?? 0),
            'ca_mois' => (float)($resVentesMois->ca_mois ?? 0),
            'total_dettes' => $totalDettes,
            'dettesARecuperer' => $totalDettes,
            'nbr_dettes' => (int)($resDettes->nbr_dettes ?? 0),
            'total_retard' => (float)($resDettesRetard->total_retard ?? 0),
            'nbr_retard' => (int)($resDettesRetard->nbr_retard ?? 0),
            'total_produits' => (int)($resProduits->total_produits ?? 0),
            'alertes_stock' => (int)($resProduits->alertes_stock ?? 0),
            'ruptures_stock' => (int)($resProduits->ruptures_stock ?? 0),
            'valeur_stock' => $valeurStock,
            'valeurStock' => $valeurStock,
            'bl_attente' => (int)($resAppros->bl_attente ?? 0),
            'cout_attente' => $coutAttente,
            'volumeApprovisionne' => $coutAttente,
            'total_clients' => (int)($resClients->total_clients ?? 0),
            'total_fournisseurs' => (int)($resFournisseurs->total_fournisseurs ?? 0),
        ];
    }

    public static function getDernieresVentes(int $limit = 5): array
    {
        $sql = "SELECT v.id AS vente_id, v.id, v.numero_facture, v.montant_total, v.montant_verse, v.statut, v.date_vente, v.date_echeance, v.mode_paiement_id,
                       c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, c.email AS client_email, c.limite_credit AS client_limite,
                       mp.id AS mode_id, mp.nom AS mode_paiement_nom, mp.nom AS mode_nom
                FROM ventes v
                JOIN clients c ON c.id = v.client_id
                LEFT JOIN modes_paiement mp ON mp.id = v.mode_paiement_id
                ORDER BY v.id DESC LIMIT $limit";

        $rows = Database::query($sql, false);
        if (!empty($rows)) {
            return array_map(fn($row) => Vente::toEntity($row), $rows);
        }
        return [];
    }

    public static function getDettesDuJour(): array
    {
        $sql = "SELECT d.id AS dette_id, d.id, d.ref, d.montant_initial, d.montant_verse, d.montant_restant, d.date_dette, d.date_echeance,
                       c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone,
                       sd.nom AS statut_nom,
                       v.id AS vente_id, v.numero_facture, v.statut AS vente_statut, v.montant_total, v.montant_verse AS vente_montant_verse, v.date_vente
                FROM dettes d
                JOIN clients c ON c.id = d.client_id
                JOIN statuts_dette sd ON sd.id = d.statut_dette_id
                JOIN ventes v ON v.id = d.vente_id
                WHERE d.date_dette = CURRENT_DATE AND d.montant_restant > 0
                ORDER BY d.id DESC";

        $rows = Database::query($sql, false);
        if (!empty($rows)) {
            return array_map(fn($row) => Dette::toEntity($row), $rows);
        }
        return [];
    }

    public static function getRupturesEtAlertes(): array
    {
        $sql = "SELECT p.id AS produit_id, p.id, p.code, p.libelle, p.categorie, p.prix_vente, p.cout_achat, p.stock_initial, p.seuil_alerte, p.fournisseur_id,
                       f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.telephone AS fournisseur_telephone
                FROM produits p
                LEFT JOIN fournisseurs f ON f.id = p.fournisseur_id
                WHERE p.stock_initial <= p.seuil_alerte
                ORDER BY p.stock_initial ASC";

        $rows = Database::query($sql, false);
        if (!empty($rows)) {
            return array_map(fn($row) => Produit::toEntity($row), $rows);
        }
        return [];
    }

    public static function getLivraisonsDuJour(): array
    {
        $sql = "SELECT a.id AS appro_id, a.id, a.reference_bl, a.cout_achat, a.date_appro, a.date_reception, a.fournisseur_id,
                       f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.telephone AS fournisseur_telephone
                FROM approvisionnements a
                JOIN fournisseurs f ON f.id = a.fournisseur_id
                WHERE a.date_reception IS NULL
                ORDER BY a.id DESC";

        $rows = Database::query($sql, false);
        if (!empty($rows)) {
            return array_map(fn($row) => Approvisionnement::toEntity($row), $rows);
        }
        return [];
    }

    public static function getClientsDebiteurs(): array
    {
        $sql = "SELECT c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone,
                       COUNT(d.id) AS nbr_dettes,
                       SUM(d.montant_restant) AS cumul_du
                FROM dettes d
                JOIN clients c ON c.id = d.client_id
                WHERE d.montant_restant > 0
                GROUP BY c.id, c.nom, c.prenom, c.telephone
                ORDER BY cumul_du DESC";

        $rows = Database::query($sql, false);
        if (!empty($rows)) {
            return array_map(function ($row) {
                $clientId = (int)($row->client_id ?? 0);
                $sqlDettes = "SELECT d.id AS dette_id, d.id, d.ref, d.montant_initial, d.montant_verse, d.montant_restant, d.date_dette, d.date_echeance,
                                     v.numero_facture, v.id AS vente_id, sd.nom AS statut_nom
                              FROM dettes d
                              JOIN ventes v ON v.id = d.vente_id
                              JOIN statuts_dette sd ON sd.id = d.statut_dette_id
                              WHERE d.client_id = :client_id AND d.montant_restant > 0
                              ORDER BY d.id DESC";
                $detteRows = Database::executeQuery($sqlDettes, ['client_id' => $clientId], false);
                $dettes = (!empty($detteRows)) ? array_map(fn($dRow) => Dette::toEntity($dRow), $detteRows) : [];

                return [
                    'client' => Client::toEntity($row),
                    'nbr_dettes' => (int)($row->nbr_dettes ?? 0),
                    'cumul_du' => (float)($row->cumul_du ?? 0),
                    'dettes' => $dettes
                ];
            }, $rows);
        }
        return [];
    }

    public static function getSoldeFournisseurs(): array
    {
        $sql = "SELECT f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.telephone AS fournisseur_telephone,
                       COUNT(a.id) AS nbr_appro,
                       COALESCE(SUM(a.cout_achat), 0) AS volume_achat,
                       SUM(CASE WHEN a.date_reception IS NULL THEN 1 ELSE 0 END) AS bl_en_cours
                FROM fournisseurs f
                LEFT JOIN approvisionnements a ON a.fournisseur_id = f.id
                GROUP BY f.id, f.nom, f.telephone
                ORDER BY volume_achat DESC";

        return Database::query($sql, false) ?: [];
    }

    public static function getPerformanceVendeurs(): array
    {
        $sql = "SELECT u.id AS user_id, u.nom, u.prenom,
                       COUNT(v.id) AS nbr_ventes,
                       COALESCE(SUM(v.montant_total), 0) AS total_ca,
                       COALESCE(SUM(v.montant_verse), 0) AS total_encaisse
                FROM utilisateurs u
                LEFT JOIN ventes v ON v.utilisateur_id = u.id
                GROUP BY u.id, u.nom, u.prenom
                ORDER BY total_ca DESC";

        return Database::query($sql, false) ?: [];
    }
}
