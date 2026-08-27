<?php

namespace App\Model\Repository;

use Adja\Core\Database;
use App\Model\Entity\Dette;
use App\Model\Entity\Paiement;
use App\Model\Entity\LigneVente;
use App\Model\Entity\Client;
use App\Model\Entity\StatutDette;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;

class DetteRepository
{
    public static function selectAllFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        $search = $filtered->getFilter('search');
        $statut = $filtered->getFilter('statut');
        $clientId = (int)($filtered->getFilter('client_id') ?? 0);

        $limit = $pagination->getLimit();
        $offset = $pagination->getOffset();

        $params = [];
        $sqlFilter = " 1=1 ";

        if (!empty($search)) {
            $sqlFilter .= " AND (d.ref ILIKE :search OR c.nom ILIKE :search OR c.prenom ILIKE :search OR c.telephone ILIKE :search OR v.numero_facture ILIKE :search)";
            $params['search'] = "%$search%";
        }

        if (!empty($statut) && $statut !== "0" && $statut !== "ALL") {
            if ($statut === 'SOLDEE') {
                $sqlFilter .= " AND d.statut_dette_id = 2";
            } elseif ($statut === 'NON_SOLDEE' || $statut === 'NON SOLDEE' || $statut === 'EN_COURS' || $statut === 'EN COURS') {
                $sqlFilter .= " AND d.statut_dette_id = 1";
            }
        }

        if ($clientId > 0) {
            $sqlFilter .= " AND d.client_id = :client_id";
            $params['client_id'] = $clientId;
        }

        $sqlCount = "SELECT COUNT(DISTINCT d.id) AS total
                     FROM dettes d
                     JOIN clients c ON c.id = d.client_id
                     JOIN ventes v ON v.id = d.vente_id
                     WHERE $sqlFilter";

        $countResult = Database::executeQuery($sqlCount, $params);
        $totalElements = (int)($countResult->total ?? 0);
        $pagination->setTotalElements($totalElements);

        $sql = "SELECT d.id AS dette_id, d.id, d.ref, d.montant_initial, d.montant_verse, d.montant_restant, d.date_dette, d.date_echeance, d.vente_id, d.client_id, d.statut_dette_id,
                       c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, c.email AS client_email, c.limite_credit AS client_limite,
                       sd.id AS statut_dette_id, sd.nom AS statut_nom,
                       v.id AS vente_id, v.numero_facture, v.statut AS vente_statut, v.montant_total, v.montant_verse AS vente_montant_verse, v.date_vente, v.mode_paiement_id
                FROM dettes d
                JOIN clients c ON c.id = d.client_id
                JOIN statuts_dette sd ON sd.id = d.statut_dette_id
                JOIN ventes v ON v.id = d.vente_id
                WHERE $sqlFilter
                ORDER BY d.id DESC
                LIMIT $limit OFFSET $offset";

        $results = Database::executeQuery($sql, $params, false);

        if (!empty($results)) {
            return array_map(function ($row) {
                $dette = Dette::toEntity($row);
                $dette->setPaiements(self::selectPaiementsByDetteId((int)$dette->getId()));
                $dette->setLignes(self::selectProduitsByDetteId((int)$dette->getId()));
                return $dette;
            }, $results);
        }
        return [];
    }


    public static function selectPaiementsByDetteId(int $detteId): array
    {
        $sql = "SELECT p.id AS paiement_id, p.id, p.montant, p.notes, p.date_paiement, p.dette_id, p.mode_paiement_id, p.utilisateur_id,
                       mp.id AS mode_id, mp.nom AS mode_nom, mp.nom AS mode_paiement_nom
                FROM paiements p
                LEFT JOIN modes_paiement mp ON mp.id = p.mode_paiement_id
                WHERE p.dette_id = :dette_id
                ORDER BY p.id ASC";

        $results = Database::executeQuery($sql, ['dette_id' => $detteId], false);
        return (!empty($results) && is_array($results)) ? array_map(fn($row) => Paiement::toEntity($row), $results) : [];
    }

    public static function selectProduitsByDetteId(int $detteId): array
    {
        $sql = "SELECT p.id AS produit_id, p.id, p.code AS produit_code, p.code, p.libelle AS produit_libelle, p.libelle, p.categorie AS produit_categorie, p.categorie, p.prix_vente, p.cout_achat, p.stock_initial, p.seuil_alerte,
                       lv.quantite, lv.prix_unitaire, (lv.quantite * lv.prix_unitaire) AS sous_total, lv.id AS ligne_id, lv.vente_id
                FROM dettes d
                JOIN ventes v ON v.id = d.vente_id
                JOIN lignes_vente lv ON lv.vente_id = v.id
                JOIN produits p ON p.id = lv.produit_id
                WHERE d.id = :dette_id
                ORDER BY lv.id ASC";

        $results = Database::executeQuery($sql, ['dette_id' => $detteId], false);
        return (!empty($results) && is_array($results)) ? array_map(fn($row) => LigneVente::toEntity($row), $results) : [];
    }

    public static function selectStatistiques(): object
    {
        $sql = "SELECT COUNT(*) AS nbr_dettes,
                       COALESCE(SUM(montant_initial), 0) AS total_initial,
                       COALESCE(SUM(montant_verse), 0) AS total_verse,
                       COALESCE(SUM(montant_restant), 0) AS total_restant
                FROM dettes";
        $res = Database::query($sql, true);
        return $res ?: (object)['nbr_dettes' => 0, 'total_initial' => 0, 'total_verse' => 0, 'total_restant' => 0];
    }
}
