<?php

require_once dirname(__DIR__) . "/Entity/Approvisionnement.php";
require_once dirname(__DIR__) . "/Entity/LigneApprovisionnement.php";
require_once dirname(__DIR__) . "/Entity/Fournisseur.php";
require_once dirname(__DIR__) . "/Entity/Produit.php";

class ApprovisionnementRepository
{

    public static function insert(Approvisionnement $appro): int
    {
        $pdo = Database::connexionDB();

        try {
            $pdo->beginTransaction();

            $sqlAppro = "INSERT INTO approvisionnements (reference_bl, cout_achat, date_appro, date_reception, fournisseur_id, utilisateur_id)
                         VALUES (:reference_bl, :cout_achat, :date_appro, :date_reception, :fournisseur_id, :utilisateur_id)";

            Database::executeUpdate($pdo, $sqlAppro, [
                'reference_bl' => $appro->getReferenceBl(),
                'cout_achat' => $appro->getCoutAchat(),
                'date_appro' => $appro->getDateAppro() ?? date('Y-m-d'),
                'date_reception' => $appro->getDateReception(),
                'fournisseur_id' => $appro->getFournisseurId(),
                'utilisateur_id' => $appro->getUtilisateurId()
            ]);

            $approId = (int)$pdo->lastInsertId();
            $appro->setId($approId);

            $sqlLigne = "INSERT INTO lignes_approvisionnement (approvisionnement_id, produit_id, quantite_appro, quantite_recue, prix_achat, sous_total)
                         VALUES (:approvisionnement_id, :produit_id, :quantite_appro, :quantite_recue, :prix_achat, :sous_total)";

            foreach ($appro->getLignes() as $ligne) {
                Database::executeUpdate($pdo, $sqlLigne, [
                    'approvisionnement_id' => $approId,
                    'produit_id' => $ligne->getProduitId(),
                    'quantite_appro' => $ligne->getQuantiteAppro(),
                    'quantite_recue' => $ligne->getQuantiteRecue(),
                    'prix_achat' => $ligne->getPrixAchat(),
                    'sous_total' => $ligne->getSousTotal()
                ]);
            }

            $pdo->commit();
            return $approId;
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function selectAll(): array
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT a.*, 
                       f.nom AS fournisseur_nom, f.email AS fournisseur_email, f.telephone AS fournisseur_telephone, f.adresse AS fournisseur_adresse
                FROM approvisionnements a
                JOIN fournisseurs f ON f.id = a.fournisseur_id
                ORDER BY a.id DESC";

        $tableauAppros = Database::query($pdo, $sql, false);
        $appros = [];
        if (empty($tableauAppros)) return $appros;

        foreach ($tableauAppros as $approv) {
            $appro = self::toObjet($approv);
            $appro->setLignes(self::selectLignesByApproId((int)$approv['id']));
            $appros[] = $appro;
        }

        return $appros;
    }

    public static function selectById(int $id): ?Approvisionnement
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT a.*, 
                       f.nom AS fournisseur_nom, f.email AS fournisseur_email, f.telephone AS fournisseur_telephone, f.adresse AS fournisseur_adresse
                FROM approvisionnements a
                JOIN fournisseurs f ON f.id = a.fournisseur_id
                WHERE a.id = :id";

        $tableauAppro = Database::executeQuery($pdo, $sql, ['id' => $id]);
        if (!$tableauAppro) return null;

        $appro = self::toObjet($tableauAppro);
        $appro->setLignes(self::selectLignesByApproId($id));
        return $appro;
    }

    private static function selectLignesByApproId(int $approId): array
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT la.*, p.libelle AS produit_libelle, p.code AS produit_code
                FROM lignes_approvisionnement la
                JOIN produits p ON p.id = la.produit_id
                WHERE la.approvisionnement_id = :approvisionnement_id
                ORDER BY la.id ASC";

        $rows = Database::executeQuery($pdo, $sql, ['approvisionnement_id' => $approId], false);
        $lignes = [];
        if (empty($rows)) return $lignes;

        foreach ($rows as $row) {
            $la = new LigneApprovisionnement(
                (int)$row['produit_id'],
                (int)$row['quantite_appro'],
                (float)$row['prix_achat'],
                (int)$row['quantite_recue'],
                (float)$row['sous_total'],
                (int)$row['approvisionnement_id'],
                (int)$row['id']
            );
            $produit = new Produit(
                $row['produit_code'],
                $row['produit_libelle'],
                '',
                (float)$row['prix_achat'],
                (float)$row['prix_achat'],
                0,
                5,
                null,
                (int)$row['produit_id']
            );
            $la->setProduit($produit);
            $lignes[] = $la;
        }

        return $lignes;
    }

    public static function receptionnerBL(int $approvisionnementId, array $quantitesRecues): bool
    {
        $pdo = Database::connexionDB();

        try {
            $pdo->beginTransaction();

            $sqlAppro = "SELECT * FROM approvisionnements WHERE id = :id FOR UPDATE";
            $appro = Database::executeQuery($pdo, $sqlAppro, ['id' => $approvisionnementId]);

            if (!$appro) {
                throw new Exception("Bordereau de livraison introuvable.");
            }

            // 1. Update date_reception on approvisionnement
            $sqlUpdateAppro = "UPDATE approvisionnements SET date_reception = CURRENT_DATE WHERE id = :id";
            Database::executeUpdate($pdo, $sqlUpdateAppro, ['id' => $approvisionnementId]);

            // 2. Update each ligne and increment stock_initial on produits
            $sqlSelectLignes = "SELECT * FROM lignes_approvisionnement WHERE approvisionnement_id = :appro_id";
            $lignes = Database::executeQuery($pdo, $sqlSelectLignes, ['appro_id' => $approvisionnementId], false);

            $sqlUpdateLigne = "UPDATE lignes_approvisionnement SET quantite_recue = :quantite_recue WHERE id = :id";
            $sqlUpdateStock = "UPDATE produits SET stock_initial = stock_initial + :quantite WHERE id = :id";

            foreach ($lignes as $ligne) {
                $produitId = (int)$ligne['produit_id'];
                $qteRecue = isset($quantitesRecues[$produitId]) ? (int)$quantitesRecues[$produitId] : (int)$ligne['quantite_appro'];

                Database::executeUpdate($pdo, $sqlUpdateLigne, [
                    'quantite_recue' => $qteRecue,
                    'id' => $ligne['id']
                ]);

                Database::executeUpdate($pdo, $sqlUpdateStock, [
                    'quantite' => $qteRecue,
                    'id' => $produitId
                ]);
            }

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function selectStatistiques(): array
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT 
                    COALESCE(SUM(cout_achat), 0) AS cout_total_entrees,
                    COUNT(CASE WHEN date_reception IS NOT NULL THEN 1 END) AS bl_recus,
                    COUNT(DISTINCT fournisseur_id) AS fournisseurs_actifs
                FROM approvisionnements";

        $res = Database::query($pdo, $sql);
        return [
            'cout_total_entrees' => (float)($res['cout_total_entrees'] ?? 0),
            'bl_recus' => (int)($res['bl_recus'] ?? 0),
            'fournisseurs_actifs' => (int)($res['fournisseurs_actifs'] ?? 0)
        ];
    }

    public static function selectFournisseursSolde(): array
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT f.id, f.nom, f.telephone, f.email, f.adresse,
                       COALESCE(SUM(a.cout_achat), 0) AS total_du,
                       COUNT(a.id) AS nbr_factures
                FROM fournisseurs f
                LEFT JOIN approvisionnements a ON a.fournisseur_id = f.id
                GROUP BY f.id, f.nom, f.telephone, f.email, f.adresse
                ORDER BY total_du DESC";

        $rows = Database::query($pdo, $sql, false);
        $result = [];
        if (empty($rows)) return $result;

        foreach ($rows as $r) {
            $fournisseurId = (int)$r['id'];
            $factures = self::selectByFournisseurId($fournisseurId);
            $result[] = [
                'fournisseur_id' => $fournisseurId,
                'nom' => $r['nom'],
                'telephone' => $r['telephone'],
                'email' => $r['email'],
                'adresse' => $r['adresse'],
                'total_du' => (float)$r['total_du'],
                'nbr_factures' => (int)$r['nbr_factures'],
                'factures' => $factures
            ];
        }

        return $result;
    }

    public static function selectByFournisseurId(int $fournisseurId): array
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT a.*, f.nom AS fournisseur_nom, f.telephone AS fournisseur_telephone
                FROM approvisionnements a
                JOIN fournisseurs f ON f.id = a.fournisseur_id
                WHERE a.fournisseur_id = :fournisseur_id
                ORDER BY a.id DESC";

        $rows = Database::executeQuery($pdo, $sql, ['fournisseur_id' => $fournisseurId], false);
        $appros = [];
        if (empty($rows)) return $appros;

        foreach ($rows as $row) {
            $appro = self::toObjet($row);
            $appro->setLignes(self::selectLignesByApproId((int)$row['id']));
            $appros[] = $appro;
        }

        return $appros;
    }

    private function toObjet(array $data): Approvisionnement
    {
        $appro = new Approvisionnement(
            $data['reference_bl'],
            (int) $data['fournisseur_id'],
            (float) $data['cout_achat'],
            $data['date_reception'] ?? null,
            isset($data['utilisateur_id']) ? (int) $data['utilisateur_id'] : null,
            (int) $data['id'],
            $data['date_appro'] ?? null
        );

        if (isset($data['fournisseur_nom'])) {
            $fournisseur = new Fournisseur(
                $data['fournisseur_nom'],
                $data['fournisseur_telephone'] ?? '',
                $data['fournisseur_email'] ?? null,
                $data['fournisseur_adresse'] ?? null,
                (int) $data['fournisseur_id']
            );
            $appro->setFournisseur($fournisseur);
        }

        return $appro;
    }
}
