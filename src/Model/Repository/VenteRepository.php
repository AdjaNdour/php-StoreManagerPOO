<?php

namespace App\Model\Repository;

use App\Core\Database;
use App\Model\Entity\Vente;
use App\Model\Entity\LigneVente;
use App\Model\Entity\Produit;
use App\Model\Entity\Client;
use App\Model\Entity\ModePaiement;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;
use Throwable;

class VenteRepository
{
    public static function selectAllVentesFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        $search = $filtered->getFilter('search');
        $statut = $filtered->getFilter('statut');
        $clientId = (int)($filtered->getFilter('client_id') ?? 0);

        $limit = $pagination->getLimit();
        $offset = $pagination->getOffset();

        $where = ["1=1"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(v.numero_facture ILIKE :search OR c.nom ILIKE :search OR c.prenom ILIKE :search OR c.telephone ILIKE :search)";
            $params['search'] = "%$search%";
        }

        if (!empty($statut) && $statut !== "0" && $statut !== "ALL") {
            $where[] = "v.statut = :statut";
            $params['statut'] = $statut;
        }

        if ($clientId > 0) {
            $where[] = "v.client_id = :client_id";
            $params['client_id'] = $clientId;
        }

        $whereClause = implode(" AND ", $where);

        $sqlCount = "SELECT COUNT(DISTINCT v.id) AS total
                     FROM ventes v
                     LEFT JOIN clients c ON c.id = v.client_id
                     WHERE $whereClause";

        $countResult = Database::executeQuery($sqlCount, $params, true);
        $totalElements = (int)($countResult->total ?? 0);
        $pagination->setTotalElements($totalElements);

        $sql = "SELECT v.id AS vente_id, v.id, v.numero_facture, v.montant_total, v.montant_verse, v.statut, v.date_vente, v.date_echeance, v.mode_paiement_id,
                       c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, c.email AS client_email, c.limite_credit AS client_limite,
                       mp.id AS mode_id, mp.nom AS mode_paiement_nom, mp.nom AS mode_nom
                FROM ventes v
                LEFT JOIN clients c ON c.id = v.client_id
                LEFT JOIN modes_paiement mp ON mp.id = v.mode_paiement_id
                WHERE $whereClause
                ORDER BY v.id DESC
                LIMIT $limit OFFSET $offset";

        $results = Database::executeQuery($sql, $params, false);
        return (!empty($results) && is_array($results)) ? array_map(function ($row) {
            $vente = Vente::toEntity($row);
            $vente->setLignes(self::selectLignesByVenteId((int)$vente->getId()));
            return $vente;
        }, $results) : [];
    }

    public static function selectLignesByVenteId(int $venteId): array
    {
        $sql = "SELECT lv.id AS ligne_id, lv.id, lv.vente_id, lv.quantite, lv.prix_unitaire,
                       p.id AS produit_id, p.id, p.code AS produit_code, p.code, p.libelle AS produit_libelle, p.libelle, p.categorie AS produit_categorie, p.categorie, p.prix_vente, p.cout_achat, p.stock_initial, p.seuil_alerte
                FROM lignes_vente lv
                JOIN produits p ON p.id = lv.produit_id
                WHERE lv.vente_id = :vente_id
                ORDER BY lv.id ASC";

        $results = Database::executeQuery($sql, ['vente_id' => $venteId], false);
        return (!empty($results) && is_array($results)) ? array_map(fn($row) => LigneVente::toEntity($row), $results) : [];
    }

    public static function selectAllVente(): array
    {
        $sql = "SELECT v.id AS vente_id, v.id, v.numero_facture, v.montant_total, v.montant_verse, v.statut, v.date_vente, v.date_echeance, v.mode_paiement_id,
                       c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, c.email AS client_email, c.limite_credit AS client_limite,
                       mp.id AS mode_id, mp.nom AS mode_paiement_nom, mp.nom AS mode_nom
                FROM ventes v
                LEFT JOIN clients c ON c.id = v.client_id
                LEFT JOIN modes_paiement mp ON mp.id = v.mode_paiement_id
                ORDER BY v.id DESC";

        $results = Database::query($sql, false);
        return (!empty($results) && is_array($results)) ? array_map(function ($row) {
            $vente = Vente::toEntity($row);
            $vente->setLignes(self::selectLignesByVenteId((int)$vente->getId()));
            return $vente;
        }, $results) : [];
    }

    public static function selectAll(): array
    {
        return self::selectAllVente();
    }

    public static function selectById(int $id): ?Vente
    {
        $sql = "SELECT v.id AS vente_id, v.id, v.numero_facture, v.montant_total, v.montant_verse, v.statut, v.date_vente, v.date_echeance, v.mode_paiement_id,
                       c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, c.email AS client_email, c.limite_credit AS client_limite,
                       mp.id AS mode_id, mp.nom AS mode_paiement_nom, mp.nom AS mode_nom
                FROM ventes v
                LEFT JOIN clients c ON c.id = v.client_id
                LEFT JOIN modes_paiement mp ON mp.id = v.mode_paiement_id
                WHERE v.id = :id";

        $row = Database::executeQuery($sql, ['id' => $id], true);
        if (!$row) return null;

        $vente = Vente::toEntity($row);
        $vente->setLignes(self::selectLignesByVenteId($id));
        return $vente;
    }

    public static function selectByNumeroFacture(string $numeroFacture): ?Vente
    {
        $sql = "SELECT v.id AS vente_id, v.id, v.numero_facture, v.montant_total, v.montant_verse, v.statut, v.date_vente, v.date_echeance, v.mode_paiement_id,
                       c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, c.email AS client_email, c.limite_credit AS client_limite,
                       mp.id AS mode_id, mp.nom AS mode_paiement_nom, mp.nom AS mode_nom
                FROM ventes v
                LEFT JOIN clients c ON c.id = v.client_id
                LEFT JOIN modes_paiement mp ON mp.id = v.mode_paiement_id
                WHERE v.numero_facture = :numero LIMIT 1";

        $row = Database::executeQuery($sql, ['numero' => $numeroFacture], true);
        if (!$row) return null;

        $vente = Vente::toEntity($row);
        $vente->setLignes(self::selectLignesByVenteId((int)$vente->getId()));
        return $vente;
    }

    public static function insert(Vente $vente): int
    {
        $pdo = Database::getInstance();
        $startedTx = false;
        if (!$pdo->inTransaction()) {
            $pdo->beginTransaction();
            $startedTx = true;
        }

        try {
            $sqlVente = "INSERT INTO ventes(numero_facture, montant_total, montant_verse, statut, date_vente, date_echeance, client_id, utilisateur_id, mode_paiement_id) 
                         VALUES(:numero_facture, :montant_total, :montant_verse, :statut, :date_vente, :date_echeance, :client_id, :utilisateur_id, :mode_paiement_id) RETURNING id";

            $dateVente = $vente->getDateVente() ?? date('Y-m-d');
            $dateEcheance = $vente->getDateEcheance();
            if (empty($dateEcheance) && $vente->getMontantVerse() < $vente->getMontantTotal()) {
                $dateEcheance = date('Y-m-d', strtotime('+30 days'));
            }

            $statut = 'PAYEE';
            if ($vente->getMontantVerse() <= 0) {
                $statut = 'CREDIT';
            } elseif ($vente->getMontantVerse() < $vente->getMontantTotal()) {
                $statut = 'AVANCE';
            }
            $vente->setStatut($statut);

            $resVente = Database::executeQuery($sqlVente, [
                'numero_facture' => $vente->getNumeroFacture(),
                'montant_total' => $vente->getMontantTotal(),
                'montant_verse' => $vente->getMontantVerse(),
                'statut' => $statut,
                'date_vente' => $dateVente,
                'date_echeance' => $dateEcheance,
                'client_id' => $vente->getClientId(),
                'utilisateur_id' => $vente->getUtilisateurId() ?? 2,
                'mode_paiement_id' => $vente->getModePaiementId() ?? 1
            ], true);

            $venteId = (int)($resVente->id ?? 0);
            if ($venteId <= 0) {
                throw new Exception("Erreur lors de l'enregistrement de la vente.");
            }

            // Insert lignes
            $sqlLigne = "INSERT INTO lignes_vente (vente_id, produit_id, quantite, prix_unitaire)
                         VALUES (:vente_id, :produit_id, :quantite, :prix_unitaire)";
            $sqlUpdateStock = "UPDATE produits SET stock_initial = stock_initial - :quantite WHERE id = :id AND stock_initial >= :quantite";

            foreach ($vente->getLignes() as $ligne) {
                Database::executeUpdate($sqlLigne, [
                    'vente_id' => $venteId,
                    'produit_id' => $ligne->getProduitId(),
                    'quantite' => $ligne->getQuantite(),
                    'prix_unitaire' => $ligne->getPrixUnitaire()
                ]);

                $affected = Database::executeUpdate($sqlUpdateStock, [
                    'quantite' => $ligne->getQuantite(),
                    'id' => $ligne->getProduitId()
                ]);

                if ($affected === 0) {
                    throw new Exception("Stock insuffisant pour le produit #" . $ligne->getProduitId());
                }
            }

            // If remaining balance > 0, create dette and payment if any advance
            if ($vente->getMontantVerse() < $vente->getMontantTotal()) {
                $montantRestant = $vente->getMontantTotal() - $vente->getMontantVerse();
                $refDette = 'DT-' . $venteId;

                $sqlDette = "INSERT INTO dettes (ref, montant_initial, montant_verse, montant_restant, date_dette, date_echeance, vente_id, client_id, statut_dette_id)
                             VALUES (:ref, :montant_initial, :montant_verse, :montant_restant, :date_dette, :date_echeance, :vente_id, :client_id, :statut_dette_id) RETURNING id";

                $resDette = Database::executeQuery($sqlDette, [
                    'ref' => $refDette,
                    'montant_initial' => $vente->getMontantTotal(),
                    'montant_verse' => $vente->getMontantVerse(),
                    'montant_restant' => $montantRestant,
                    'date_dette' => $dateVente,
                    'date_echeance' => $dateEcheance,
                    'vente_id' => $venteId,
                    'client_id' => $vente->getClientId(),
                    'statut_dette_id' => 1
                ], true);

                $detteId = (int)($resDette->id ?? 0);

                if ($vente->getMontantVerse() > 0 && $detteId > 0) {
                    $sqlPaiement = "INSERT INTO paiements (montant, notes, date_paiement, dette_id, mode_paiement_id, utilisateur_id)
                                    VALUES (:montant, :notes, :date_paiement, :dette_id, :mode_paiement_id, :utilisateur_id)";
                    Database::executeUpdate($sqlPaiement, [
                        'montant' => $vente->getMontantVerse(),
                        'notes' => 'Acompte versé à la création de la vente ' . $vente->getNumeroFacture(),
                        'date_paiement' => $dateVente,
                        'dette_id' => $detteId,
                        'mode_paiement_id' => $vente->getModePaiementId() ?? 1,
                        'utilisateur_id' => $vente->getUtilisateurId() ?? 2
                    ]);
                }
            }

            if ($startedTx && $pdo->inTransaction()) {
                $pdo->commit();
            }
            return $venteId;
        } catch (Throwable $e) {
            if ($startedTx && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function selectStatistiques(): object
    {
        $sql = "SELECT COUNT(*) AS nbr_ventes,
                       COALESCE(SUM(montant_total), 0) AS montant_total,
                       COALESCE(SUM(montant_verse), 0) AS montant_encaisse
                FROM ventes";
        $res = Database::query($sql, true);
        return $res ?: (object)['nbr_ventes' => 0, 'montant_total' => 0, 'montant_encaisse' => 0];
    }
}
