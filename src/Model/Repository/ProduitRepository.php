<?php

require_once dirname(__DIR__) . "/Entity/Produit.php";

class ProduitRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connexionDB();
    }

    public function insert(Produit $produit): int
    {
        $sql = "INSERT INTO produits (code, libelle, categorie, prix_vente, cout_achat, stock_initial, seuil_alerte, fournisseur_id)
                VALUES (:code, :libelle, :categorie, :prix_vente, :cout_achat, :stock_initial, :seuil_alerte, :fournisseur_id)";

        Database::executeUpdate($this->pdo, $sql, [
            'code' => $produit->getCode(),
            'libelle' => $produit->getLibelle(),
            'categorie' => $produit->getCategorie(),
            'prix_vente' => $produit->getPrixVente(),
            'cout_achat' => $produit->getCoutAchat(),
            'stock_initial' => $produit->getStockInitial(),
            'seuil_alerte' => $produit->getSeuilAlerte(),
            'fournisseur_id' => $produit->getFournisseurId()
        ]);

        $id = (int) $this->pdo->lastInsertId();

        $produit->setId($id);

        return $id;
    }

    public function selectById(int $id): ?Produit
    {
        $sql = "SELECT * FROM produits WHERE id = :id";

        $produit = Database::executeQuery($this->pdo, $sql, ['id' => $id]);

        if (!$produit) return null;

        return $this->toObjet($produit);
    }

    public function selectAll(): array
    {
        $sql = "SELECT * FROM produits ORDER BY libelle ASC";

        $tableauProduits = Database::query($this->pdo, $sql, false);

        $produits = [];

        if (empty($tableauProduits)) return $produits;

        foreach ($tableauProduits as $produit) {
            $produits[] = $this->toObjet($produit);
        }

        return $produits;
    }

    public function update(Produit $produit): bool
    {
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
            $this->pdo,
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

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM produits WHERE id = :id";

        $nbrRowsAffecte = Database::executeUpdate($this->pdo, $sql, ['id' => $id]);

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

    public function getStock(int $produitId): int
    {
        $sql = "SELECT stock_initial FROM produits WHERE id = :id";
        $resultat = Database::executeQuery($this->pdo, $sql, ['id' => $produitId]);
        if (!$resultat) {
            throw new Exception("Produit introuvable.");
        }
        return (int) $resultat['stock_initial'];
    }

    public function updateStock(int $produitId, int $quantite): void
    {
        $sql = " UPDATE produits SET stock_initial = stock_initial - :quantite
                WHERE id = :id AND stock_initial >= :quantite";

        Database::executeUpdate( $this->pdo, $sql,
            [
                'quantite' => $quantite,
                'id' => $produitId
            ]
        );
    }
}
