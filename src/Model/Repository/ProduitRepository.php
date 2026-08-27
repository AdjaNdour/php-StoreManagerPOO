<?php

namespace App\Model\Repository;

use Adja\Core\Database;
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

        $params = [];
        $sqlFilter = " 1=1 ";

        if (!empty($search)) {
            $sqlFilter .= " AND (p.code ILIKE :search OR p.libelle ILIKE :search OR p.categorie ILIKE :search)";
            $params['search'] = "%$search%";
        }

        $sqlCount = "SELECT COUNT(DISTINCT p.id) AS total FROM produits p WHERE $sqlFilter";

        $countRes = Database::executeQuery($sqlCount, $params);
        $total = (int)($countRes->total ?? 0);
        $pagination->setTotalElements($total);

        $sql = "SELECT p.id AS produit_id, p.id, p.code AS produit_code, p.code, p.libelle AS produit_libelle, p.libelle, p.categorie AS produit_categorie, p.categorie, p.prix_vente, p.cout_achat, p.stock_initial, p.seuil_alerte, p.fournisseur_id,
                       f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.telephone AS fournisseur_telephone, f.email AS fournisseur_email, f.adresse AS fournisseur_adresse
                FROM produits p
                LEFT JOIN fournisseurs f ON f.id = p.fournisseur_id
                WHERE $sqlFilter
                ORDER BY p.id DESC
                LIMIT $limit OFFSET $offset";

        $results = Database::executeQuery($sql, $params, false);

        if (!empty($results)) {
            return array_map(fn($produit) => Produit::toEntity($produit), $results);
        }
        return [];
    }

    public static function selectStatistiques(): object
    {
        $sqlProduits = "SELECT COUNT(*) AS total_articles,
                               COALESCE(SUM(stock_initial * CASE WHEN cout_achat > 0 THEN cout_achat ELSE prix_vente END), 0) AS valeur_stock
                        FROM produits";
        $resProduits = Database::query($sqlProduits, true);

        $sqlClients = "SELECT COUNT(*) AS total_clients FROM clients";
        $resClients = Database::query($sqlClients, true);

        $sqlFournisseurs = "SELECT COUNT(*) AS total_fournisseurs FROM fournisseurs";
        $resFournisseurs = Database::query($sqlFournisseurs, true);

        return (object)[
            'valeurTotaleStock' => (float)($resProduits->valeur_stock ?? 0),
            'totalArticles' => (int)($resProduits->total_articles ?? 0),
            'totalClients' => (int)($resClients->total_clients ?? 0),
            'totalFournisseurs' => (int)($resFournisseurs->total_fournisseurs ?? 0),
        ];
    }

    public static function insert(Produit $produit): int
    {
        $sql = "INSERT INTO produits (code, libelle, categorie, prix_vente, cout_achat, stock_initial, seuil_alerte, fournisseur_id)
                VALUES (:code, :libelle, :categorie, :prix_vente, :cout_achat, :stock_initial, :seuil_alerte, :fournisseur_id) RETURNING id";

        $fournisseurId = $produit->getFournisseurId();
        if ($fournisseurId !== null && $fournisseurId <= 0) {
            $fournisseurId = null;
        }

        $res = Database::executeQuery($sql, [
            'code' => $produit->getCode(),
            'libelle' => $produit->getLibelle(),
            'categorie' => $produit->getCategorie(),
            'prix_vente' => $produit->getPrixVente(),
            'cout_achat' =>  $produit->getCoutAchat(),
            'stock_initial' => $produit->getStockInitial(),
            'seuil_alerte' =>  5,
            'fournisseur_id' => $fournisseurId
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

    public static function selectAll(): array
    {
        $sql = "SELECT p.id AS produit_id, p.id, p.code AS produit_code, p.code, p.libelle AS produit_libelle, p.libelle, p.categorie AS produit_categorie, p.categorie, p.prix_vente, p.cout_achat, p.stock_initial, p.seuil_alerte, p.fournisseur_id,
                       f.id AS fournisseur_id, f.nom AS fournisseur_nom, f.telephone AS fournisseur_telephone, f.email AS fournisseur_email, f.adresse AS fournisseur_adresse
                FROM produits p
                LEFT JOIN fournisseurs f ON f.id = p.fournisseur_id
                ORDER BY p.id ASC";

        $results = Database::query($sql, false);
        if (!empty($results)) {
            return array_map(fn($produit) => Produit::toEntity($produit), $results);
        }
        return [];
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
}
