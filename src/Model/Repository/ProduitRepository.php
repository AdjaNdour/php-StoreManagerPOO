<?php

namespace App\Model\Repository;

use App\Core\Database;
use App\Model\Entity\Produit;
use App\Model\Entity\Fournisseur;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;

class ProduitRepository
{
    public static function selectAllFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        $search = $filtered->getFilter('search');
        $limit = $pagination->getLimit();
        $offset = $pagination->getOffset();

        $where = ["1=1"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(p.code ILIKE :search OR p.libelle ILIKE :search OR p.categorie ILIKE :search)";
            $params['search'] = "%$search%";
        }

        $whereClause = implode(" AND ", $where);

        $sqlCount = "SELECT COUNT(DISTINCT p.id) AS total FROM produits p WHERE $whereClause";
        $countRes = Database::executeQuery($sqlCount, $params, true);
        $total = (int)($countRes->total ?? 0);
        $pagination->setTotalElements($total);

        $sql = "SELECT p.id AS produit_id, p.id, p.code AS produit_code, p.code, p.libelle AS produit_libelle, p.libelle, p.categorie AS produit_categorie, p.categorie, p.prix_vente, p.cout_achat, p.stock_initial, p.seuil_alerte, p.fournisseur_id,
                       f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.telephone AS fournisseur_telephone, f.email AS fournisseur_email, f.adresse AS fournisseur_adresse
                FROM produits p
                LEFT JOIN fournisseurs f ON f.id = p.fournisseur_id
                WHERE $whereClause
                ORDER BY p.id ASC
                LIMIT $limit OFFSET $offset";

        $results = Database::executeQuery($sql, $params, false);
        return (!empty($results) && is_array($results)) ? array_map(fn($row) => Produit::toEntity($row), $results) : [];
    }

    public static function insert(Produit $produit): int
    {
        $sql = "INSERT INTO produits (code, libelle, categorie, prix_vente, cout_achat, stock_initial, seuil_alerte, fournisseur_id)
                VALUES (:code, :libelle, :categorie, :prix_vente, :cout_achat, :stock_initial, :seuil_alerte, :fournisseur_id) RETURNING id";

        $res = Database::executeQuery($sql, [
            'code' => $produit->getCode(),
            'libelle' => $produit->getLibelle(),
            'categorie' => $produit->getCategorie(),
            'prix_vente' => $produit->getPrixVente(),
            'cout_achat' => $produit->getCoutAchat(),
            'stock_initial' => $produit->getStockInitial(),
            'seuil_alerte' => $produit->getSeuilAlerte(),
            'fournisseur_id' => $produit->getFournisseurId()
        ], true);

        $id = (int)($res->id ?? 0);
        $produit->setId($id);
        return $id;
    }

    public static function selectById(int $id): ?Produit
    {
        $sql = "SELECT p.id AS produit_id, p.id, p.code AS produit_code, p.code, p.libelle AS produit_libelle, p.libelle, p.categorie AS produit_categorie, p.categorie, p.prix_vente, p.cout_achat, p.stock_initial, p.seuil_alerte, p.fournisseur_id,
                       f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.telephone AS fournisseur_telephone, f.email AS fournisseur_email, f.adresse AS fournisseur_adresse
                FROM produits p
                LEFT JOIN fournisseurs f ON f.id = p.fournisseur_id
                WHERE p.id = :id";

        $obj = Database::executeQuery($sql, ['id' => $id], true);
        return $obj ? Produit::toEntity($obj) : null;
    }

    public static function selectByCode(string $code): ?Produit
    {
        $sql = "SELECT p.id AS produit_id, p.id, p.code AS produit_code, p.code, p.libelle AS produit_libelle, p.libelle, p.categorie AS produit_categorie, p.categorie, p.prix_vente, p.cout_achat, p.stock_initial, p.seuil_alerte, p.fournisseur_id,
                       f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.telephone AS fournisseur_telephone, f.email AS fournisseur_email, f.adresse AS fournisseur_adresse
                FROM produits p
                LEFT JOIN fournisseurs f ON f.id = p.fournisseur_id
                WHERE p.code = :code LIMIT 1";

        $obj = Database::executeQuery($sql, ['code' => $code], true);
        return $obj ? Produit::toEntity($obj) : null;
    }

    public static function selectAll(): array
    {
        $sql = "SELECT p.id AS produit_id, p.id, p.code AS produit_code, p.code, p.libelle AS produit_libelle, p.libelle, p.categorie AS produit_categorie, p.categorie, p.prix_vente, p.cout_achat, p.stock_initial, p.seuil_alerte, p.fournisseur_id,
                       f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.telephone AS fournisseur_telephone, f.email AS fournisseur_email, f.adresse AS fournisseur_adresse
                FROM produits p
                LEFT JOIN fournisseurs f ON f.id = p.fournisseur_id
                ORDER BY p.id ASC";

        $results = Database::query($sql, false);
        return (!empty($results) && is_array($results)) ? array_map(fn($row) => Produit::toEntity($row), $results) : [];
    }

    public static function update(Produit $produit): bool
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

        $affected = Database::executeUpdate($sql, [
            'id' => $produit->getId(),
            'code' => $produit->getCode(),
            'libelle' => $produit->getLibelle(),
            'categorie' => $produit->getCategorie(),
            'prix_vente' => $produit->getPrixVente(),
            'cout_achat' => $produit->getCoutAchat(),
            'stock_initial' => $produit->getStockInitial(),
            'seuil_alerte' => $produit->getSeuilAlerte(),
            'fournisseur_id' => $produit->getFournisseurId()
        ]);

        return $affected > 0;
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM produits WHERE id = :id";
        $affected = Database::executeUpdate($sql, ['id' => $id]);
        return $affected > 0;
    }

    public static function getStock(int $produitId): int
    {
        $sql = "SELECT stock_initial FROM produits WHERE id = :id";
        $res = Database::executeQuery($sql, ['id' => $produitId], true);
        if (!$res) {
            throw new Exception("Produit introuvable.");
        }
        return (int)($res->stock_initial ?? 0);
    }

    public static function updateStock(int $produitId, int $quantite): void
    {
        self::diminuerStock($produitId, $quantite);
    }

    public static function diminuerStock(int $produitId, int $quantite): void
    {
        $sql = "UPDATE produits SET stock_initial = stock_initial - :quantite
                WHERE id = :id AND stock_initial >= :quantite";

        Database::executeUpdate($sql, [
            'quantite' => $quantite,
            'id' => $produitId
        ]);
    }

    public static function augmenterStock(int $produitId, int $quantite): void
    {
        $sql = "UPDATE produits SET stock_initial = stock_initial + :quantite
                WHERE id = :id";

        Database::executeUpdate($sql, [
            'quantite' => $quantite,
            'id' => $produitId
        ]);
    }
}
