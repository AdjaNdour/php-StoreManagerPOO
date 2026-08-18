<?php

require_once dirname(__DIR__) . "/Entity/Produit.php";

class ProduitRepository
{
    public static function insert(Produit $produit): int
    {
        $pdo = Database::connexionDB();

        $sql = "INSERT INTO produits (code, libelle, categorie, prix_vente, cout_achat, stock_initial, seuil_alerte, fournisseur_id)
                VALUES (:code, :libelle, :categorie, :prix_vente, :cout_achat, :stock_initial, :seuil_alerte, :fournisseur_id)";

        Database::executeUpdate($pdo, $sql, [
            'code' => $produit->getCode(),
            'libelle' => $produit->getLibelle(),
            'categorie' => $produit->getCategorie(),
            'prix_vente' => $produit->getPrixVente(),
            'cout_achat' => $produit->getCoutAchat(),
            'stock_initial' => $produit->getStockInitial(),
            'seuil_alerte' => $produit->getSeuilAlerte(),
            'fournisseur_id' => $produit->getFournisseurId()
        ]);

        $id = (int) $pdo->lastInsertId();

        $produit->setId($id);

        return $id;
    }

    public static function selectById(int $id): ?Produit
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT * FROM produits WHERE id = :id";

        $produit = Database::executeQuery($pdo, $sql, ['id' => $id]);

        if (!$produit) return null;

        return self::toObjet($produit);
    }

    public static function selectAll(): array
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT * FROM produits ORDER BY libelle ASC";

        $tableauProduits = Database::query($pdo, $sql, false);

        $produits = [];

        if (empty($tableauProduits)) return $produits;

        foreach ($tableauProduits as $produit) {
            $produits[] = self::toObjet($produit);
        }

        return $produits;
    }

    public static function update(Produit $produit): bool
    {
        $pdo = Database::connexionDB();

        $sql = "UPDATE produits 
                SET code = :code,
                    libelle = :libelle,
                    categorie = :categorie,
                    prix_vente = :prix_vente,
                    cout_achat = :cout_achat,
                    stock_initial = :stock_initial,
                    seuil_alerte = :seuil_alerte,
                    fournisseur_id = :fournisseur_id
                WHERE id = :id";

        $nbrRowsAffecte = Database::executeUpdate(
            $pdo,
            $sql,
            [
                'id' => $produit->getId(),
                'code' => $produit->getCode(),
                'libelle' => $produit->getLibelle(),
                'categorie' => $produit->getCategorie(),
                'prix_vente' => $produit->getPrixVente(),
                'cout_achat' => $produit->getCoutAchat(),
                'stock_initial' => $produit->getStockInitial(),
                'seuil_alerte' => $produit->getSeuilAlerte(),
                'fournisseur_id' => $produit->getFournisseurId()
            ]
        );

        return $nbrRowsAffecte > 0 ? true : false;
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connexionDB();

        $sql = "DELETE FROM produits WHERE id = :id";

        $nbrRowsAffecte = Database::executeUpdate($pdo, $sql, ['id' => $id]);

        return $nbrRowsAffecte > 0 ? true : false;
    }

    private function toObjet(array $produit): Produit
    {
        return new Produit(
            $produit['code'],
            $produit['libelle'],
            $produit['categorie'],
            (float) $produit['prix_vente'],
            (float) $produit['cout_achat'],
            (int) $produit['stock_initial'],
            (int) $produit['seuil_alerte'],
            $produit['fournisseur_id'] !== null ? (int) $produit['fournisseur_id'] : null,
            (int) $produit['id']
        );
    }

    public static function getStock(int $produitId): int
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT stock_initial FROM produits WHERE id = :id";
        $resultat = Database::executeQuery($pdo, $sql, ['id' => $produitId]);
        if (!$resultat) {
            throw new Exception("Produit introuvable.");
        }
        return (int) $resultat['stock_initial'];
    }

    public static function updateStock(int $produitId, int $quantite): void
    {
        $pdo = Database::connexionDB();

        $sql = " UPDATE produits SET stock_initial = stock_initial - :quantite
                WHERE id = :id AND stock_initial >= :quantite";

        Database::executeUpdate(
            $pdo,
            $sql,
            [
                'quantite' => $quantite,
                'id' => $produitId
            ]
        );
    }
}
