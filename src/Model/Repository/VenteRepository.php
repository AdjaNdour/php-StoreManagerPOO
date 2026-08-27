<?php

namespace App\Model\Repository;

use Adja\Core\Database;
use App\Model\Entity\Vente;
use App\Model\Entity\LigneVente;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;
use Throwable;

class VenteRepository
{
    public static function selectStatistiques(): object
    {
        $sql = "SELECT COUNT(*) AS nbr_ventes,
                       COALESCE(SUM(montant_total), 0) AS montant_total,
                       COALESCE(SUM(montant_verse), 0) AS montant_encaisse
                FROM ventes";
        $res = Database::query($sql, true);
        return $res ?: (object)['nbr_ventes' => 0, 'montant_total' => 0, 'montant_encaisse' => 0];
    }

    public static function insert(Vente $vente): int
    {
        $pdo = Database::getInstance();
        $pdo->beginTransaction();

        try {
            $sqlVente = "INSERT INTO ventes(numero_facture, montant_total, montant_verse, statut, date_vente, date_echeance, client_id, utilisateur_id, mode_paiement_id) 
                         VALUES(:numero_facture, :montant_total, :montant_verse, :statut, :date_vente, :date_echeance, :client_id, :utilisateur_id, :mode_paiement_id) RETURNING id";

            $vente->setDateVente(date('Y-m-d'));
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
                'statut' => $vente->getStatut(),
                'date_vente' => $vente->getDateVente(),
                'date_echeance' => $dateEcheance,
                'client_id' => $vente->getClientId(),
                'utilisateur_id' => $vente->getUtilisateurId() ?? 2,
                'mode_paiement_id' => $vente->getModePaiementId() ?? 1
            ], true);

            $venteId = (int)($resVente->id ?? 0);
            if ($venteId <= 0) {
                throw new Exception("Erreur lors de l'enregistrement de la vente.");
            }

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
                    'date_dette' => $vente->getDateVente(),
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
                        'date_paiement' => $vente->getDateVente(),
                        'dette_id' => $detteId,
                        'mode_paiement_id' => $vente->getModePaiementId() ?? 1,
                        'utilisateur_id' => $vente->getUtilisateurId() ?? 2
                    ]);
                }
            }

            $pdo->commit();

            return $venteId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }



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
            $sqlFilter .= " AND (v.numero_facture ILIKE :search OR c.nom ILIKE :search OR c.prenom ILIKE :search OR c.telephone ILIKE :search)";
            $params['search'] = "%$search%";
        }

        if (!empty($statut) && $statut !== "0" && $statut !== "ALL") {
            $sqlFilter .= " AND v.statut = :statut";
            $params['statut'] = $statut;
        }

        if ($clientId > 0) {
            $sqlFilter .= " AND v.client_id = :client_id";
            $params['client_id'] = $clientId;
        }

        $sqlCount = "SELECT COUNT(DISTINCT v.id) AS total
                     FROM ventes v
                     LEFT JOIN clients c ON c.id = v.client_id
                     WHERE $sqlFilter";

        $countResult = Database::executeQuery($sqlCount, $params);

        $totalElements = (int)($countResult->total ?? 0);
        $pagination->setTotalElements($totalElements);

        $sql = "SELECT v.id AS vente_id, v.id, v.numero_facture, v.montant_total, v.montant_verse, v.statut, v.date_vente, v.date_echeance, v.mode_paiement_id,
                       c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, c.email AS client_email, c.limite_credit AS client_limite,
                       mp.id AS mode_id, mp.nom AS mode_paiement_nom, mp.nom AS mode_nom
                FROM ventes v
                LEFT JOIN clients c ON c.id = v.client_id
                LEFT JOIN modes_paiement mp ON mp.id = v.mode_paiement_id
                WHERE $sqlFilter
                ORDER BY v.id DESC
                LIMIT $limit OFFSET $offset";

        $results = Database::executeQuery($sql, $params, false);

        if (!empty($results)) {
            return array_map(function ($uneVente) {
                $vente = Vente::toEntity($uneVente);
                $vente->setLignes(self::selectLignesByVenteId((int)$vente->getId()));
                return $vente;
            }, $results);
        }
        return [];
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
        return (!empty($results) && is_array($results)) ? array_map(fn($ligne) => LigneVente::toEntity($ligne), $results) : [];
    }


}
