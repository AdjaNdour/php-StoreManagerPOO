<?php

namespace App\Model\Repository;

use App\Core\Database;
use App\Model\Entity\Approvisionnement;
use App\Model\Entity\LigneApprovisionnement;
use App\Model\Entity\Fournisseur;
use App\Model\Entity\Produit;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;
use Throwable;

class ApprovisionnementRepository
{
    public static function selectAllFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        $search = $filtered->getFilter('search');
        $statut = $filtered->getFilter('statut');
        $limit = $pagination->getLimit();
        $offset = $pagination->getOffset();

        $where = ["1=1"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(a.reference_bl ILIKE :search OR f.nom ILIKE :search OR f.telephone ILIKE :search)";
            $params['search'] = "%$search%";
        }

        if (!empty($statut) && $statut !== "0" && $statut !== "ALL") {
            if ($statut === 'RECU') {
                $where[] = "a.date_reception IS NOT NULL";
            } elseif ($statut === 'EN_ATTENTE') {
                $where[] = "a.date_reception IS NULL";
            }
        }

        $whereClause = implode(" AND ", $where);

        $sqlCount = "SELECT COUNT(DISTINCT a.id) AS total
                     FROM approvisionnements a
                     JOIN fournisseurs f ON f.id = a.fournisseur_id
                     WHERE $whereClause";

        $countRes = Database::executeQuery($sqlCount, $params, true);
        $total = (int)($countRes->total ?? 0);
        $pagination->setTotalElements($total);

        $sql = "SELECT a.id AS appro_id, a.id, a.reference_bl, a.cout_achat, a.date_appro, a.date_reception, a.fournisseur_id, a.utilisateur_id,
                       f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.email AS fournisseur_email, f.telephone AS fournisseur_telephone, f.adresse AS fournisseur_adresse
                FROM approvisionnements a
                JOIN fournisseurs f ON f.id = a.fournisseur_id
                WHERE $whereClause
                ORDER BY a.id DESC
                LIMIT $limit OFFSET $offset";

        $results = Database::executeQuery($sql, $params, false);
        return (!empty($results) && is_array($results)) ? array_map(function ($row) {
            $appro = Approvisionnement::toEntity($row);
            $appro->setLignes(self::selectLignesByApproId((int)$appro->getId()));
            return $appro;
        }, $results) : [];
    }

    public static function insert(Approvisionnement $appro): int
    {
        $pdo = Database::getInstance();
        $startedTx = false;
        if (!$pdo->inTransaction()) {
            $pdo->beginTransaction();
            $startedTx = true;
        }

        try {
            $sqlAppro = "INSERT INTO approvisionnements (reference_bl, cout_achat, date_appro, date_reception, fournisseur_id, utilisateur_id)
                         VALUES (:reference_bl, :cout_achat, :date_appro, :date_reception, :fournisseur_id, :utilisateur_id) RETURNING id";

            $res = Database::executeQuery($sqlAppro, [
                'reference_bl' => $appro->getReferenceBl(),
                'cout_achat' => $appro->getCoutAchat(),
                'date_appro' => $appro->getDateAppro() ?? date('Y-m-d'),
                'date_reception' => $appro->getDateReception(),
                'fournisseur_id' => $appro->getFournisseurId(),
                'utilisateur_id' => $appro->getUtilisateur()?->getId() ?? 3
            ], true);

            $approId = (int)($res->id ?? 0);
            $appro->setId($approId);

            $sqlLigne = "INSERT INTO lignes_approvisionnement (approvisionnement_id, produit_id, quantite_appro, quantite_recue, prix_achat, sous_total)
                         VALUES (:approvisionnement_id, :produit_id, :quantite_appro, :quantite_recue, :prix_achat, :sous_total)";

            foreach ($appro->getLignes() as $ligne) {
                Database::executeUpdate($sqlLigne, [
                    'approvisionnement_id' => $approId,
                    'produit_id' => $ligne->getProduitId(),
                    'quantite_appro' => $ligne->getQuantiteAppro(),
                    'quantite_recue' => $ligne->getQuantiteRecue(),
                    'prix_achat' => $ligne->getPrixAchat(),
                    'sous_total' => $ligne->getSousTotal()
                ]);

                // If date_reception was already set upon insert, increment stock immediately
                if (!empty($appro->getDateReception()) && $ligne->getQuantiteRecue() > 0) {
                    Database::executeUpdate("UPDATE produits SET stock_initial = stock_initial + :qty WHERE id = :id", [
                        'qty' => $ligne->getQuantiteRecue(),
                        'id' => $ligne->getProduitId()
                    ]);
                }
            }

            if ($startedTx && $pdo->inTransaction()) {
                $pdo->commit();
            }
            return $approId;
        } catch (Throwable $e) {
            if ($startedTx && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function receptionnerBL(int $approId, array $quantitesRecues = []): bool
    {
        $pdo = Database::getInstance();
        $startedTx = false;
        if (!$pdo->inTransaction()) {
            $pdo->beginTransaction();
            $startedTx = true;
        }

        try {
            $sqlAppro = "SELECT * FROM approvisionnements WHERE id = :id FOR UPDATE";
            $approRow = Database::executeQuery($sqlAppro, ['id' => $approId], true);
            if (!$approRow) {
                throw new Exception("Approvisionnement introuvable.");
            }

            $lignes = self::selectLignesByApproId($approId);

            foreach ($lignes as $ligne) {
                $ligneId = $ligne->getId();
                $qteRecue = isset($quantitesRecues[$ligneId]) ? (int)$quantitesRecues[$ligneId] : $ligne->getQuantiteAppro();
                if ($qteRecue < 0) $qteRecue = 0;

                Database::executeUpdate("UPDATE lignes_approvisionnement SET quantite_recue = :qte WHERE id = :id", [
                    'qte' => $qteRecue,
                    'id' => $ligneId
                ]);

                if ($qteRecue > 0) {
                    Database::executeUpdate("UPDATE produits SET stock_initial = stock_initial + :qte WHERE id = :id", [
                        'qte' => $qteRecue,
                        'id' => $ligne->getProduitId()
                    ]);
                }
            }

            Database::executeUpdate("UPDATE approvisionnements SET date_reception = CURRENT_DATE WHERE id = :id", [
                'id' => $approId
            ]);

            if ($startedTx && $pdo->inTransaction()) {
                $pdo->commit();
            }
            return true;
        } catch (Throwable $e) {
            if ($startedTx && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function selectAll(): array
    {
        $sql = "SELECT a.id AS appro_id, a.id, a.reference_bl, a.cout_achat, a.date_appro, a.date_reception, a.fournisseur_id, a.utilisateur_id,
                       f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.email AS fournisseur_email, f.telephone AS fournisseur_telephone, f.adresse AS fournisseur_adresse
                FROM approvisionnements a
                JOIN fournisseurs f ON f.id = a.fournisseur_id
                ORDER BY a.id DESC";

        $results = Database::query($sql, false);
        return (!empty($results) && is_array($results)) ? array_map(function ($row) {
            $appro = Approvisionnement::toEntity($row);
            $appro->setLignes(self::selectLignesByApproId((int)$appro->getId()));
            return $appro;
        }, $results) : [];
    }

    public static function selectById(int $id): ?Approvisionnement
    {
        $sql = "SELECT a.id AS appro_id, a.id, a.reference_bl, a.cout_achat, a.date_appro, a.date_reception, a.fournisseur_id, a.utilisateur_id,
                       f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.email AS fournisseur_email, f.telephone AS fournisseur_telephone, f.adresse AS fournisseur_adresse
                FROM approvisionnements a
                JOIN fournisseurs f ON f.id = a.fournisseur_id
                WHERE a.id = :id";

        $row = Database::executeQuery($sql, ['id' => $id], true);
        if (!$row) return null;

        $appro = Approvisionnement::toEntity($row);
        $appro->setLignes(self::selectLignesByApproId($id));
        return $appro;
    }

    public static function selectByReferenceBl(string $referenceBl): ?Approvisionnement
    {
        $sql = "SELECT a.id AS appro_id, a.id, a.reference_bl, a.cout_achat, a.date_appro, a.date_reception, a.fournisseur_id, a.utilisateur_id,
                       f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.email AS fournisseur_email, f.telephone AS fournisseur_telephone, f.adresse AS fournisseur_adresse
                FROM approvisionnements a
                JOIN fournisseurs f ON f.id = a.fournisseur_id
                WHERE a.reference_bl = :reference_bl LIMIT 1";

        $row = Database::executeQuery($sql, ['reference_bl' => $referenceBl], true);
        if (!$row) return null;

        $appro = Approvisionnement::toEntity($row);
        $appro->setLignes(self::selectLignesByApproId((int)$appro->getId()));
        return $appro;
    }

    public static function selectLignesByApproId(int $approId): array
    {
        $sql = "SELECT la.id AS ligne_appro_id, la.id, la.approvisionnement_id, la.quantite_appro, la.quantite_recue, la.prix_achat, la.sous_total,
                       p.id AS produit_id, p.id, p.code AS produit_code, p.code, p.libelle AS produit_libelle, p.libelle, p.categorie AS produit_categorie, p.categorie, p.prix_vente, p.cout_achat, p.stock_initial, p.seuil_alerte
                FROM lignes_approvisionnement la
                JOIN produits p ON p.id = la.produit_id
                WHERE la.approvisionnement_id = :approvisionnement_id
                ORDER BY la.id ASC";

        $rows = Database::executeQuery($sql, ['approvisionnement_id' => $approId], false);
        return (!empty($rows) && is_array($rows)) ? array_map(fn($row) => LigneApprovisionnement::toEntity($row), $rows) : [];
    }

    public static function selectStatistiques(): object
    {
        $sql = "SELECT COUNT(*) AS total_bl,
                       COALESCE(SUM(cout_achat), 0) AS total_cout_appro,
                       COUNT(DISTINCT fournisseur_id) AS total_fournisseurs_actifs
                FROM approvisionnements";
        $res = Database::query($sql, true);
        return $res ?: (object)['total_bl' => 0, 'total_cout_appro' => 0, 'total_fournisseurs_actifs' => 0];
    }
}
