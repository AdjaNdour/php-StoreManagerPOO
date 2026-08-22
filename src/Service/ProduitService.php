<?php

namespace App\Service;

use App\Model\Repository\ProduitRepository;
use App\Model\Entity\Produit;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use App\Core\Database;
use Exception;

class ProduitService
{
    public static function getAllFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        return ProduitRepository::selectAllFiltered($filtered, $pagination);
    }

    public static function getAll(): array
    {
        return ProduitRepository::selectAll();
    }

    public static function getById(int $id): ?Produit
    {
        return ProduitRepository::selectById($id);
    }

    public static function getByCode(string $code): ?Produit
    {
        return ProduitRepository::selectByCode($code);
    }

    public static function getStock(int $produitId): int
    {
        return ProduitRepository::getStock($produitId);
    }

    public static function updateStock(int $produitId, int $quantite): void
    {
        ProduitRepository::updateStock($produitId, $quantite);
    }

    public static function diminuerStock(int $produitId, int $quantite): void
    {
        ProduitRepository::diminuerStock($produitId, $quantite);
    }

    public static function augmenterStock(int $produitId, int $quantite): void
    {
        ProduitRepository::augmenterStock($produitId, $quantite);
    }

    public static function verifierDisponibilite(int $produitId, int $quantiteDemandee): bool
    {
        $stock = self::getStock($produitId);
        return $stock >= $quantiteDemandee;
    }

    public static function genererCodeProduit(string $libelle = ''): string
    {
        $id = Database::getLastId("produits") + 1;
        $prefix = !empty($libelle) ? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $libelle), 0, 5)) : 'ART';
        return "PRD-" . $prefix . $id;
    }

    public static function enregistrer(Produit $produit): int
    {
        if (trim($produit->getLibelle()) === '') {
            throw new Exception("Le libellé de l'article est obligatoire.");
        }
        if ($produit->getPrixVente() < 0) {
            throw new Exception("Le prix de vente ne peut pas être négatif.");
        }
        if ($produit->getStockInitial() < 0) {
            throw new Exception("La quantité en stock ne peut pas être négative.");
        }

        if (empty($produit->getCode())) {
            $produit->setCode(self::genererCodeProduit($produit->getLibelle()));
        }

        return ProduitRepository::insert($produit);
    }

    public static function save(Produit $produit): int
    {
        return self::enregistrer($produit);
    }

    public static function modifier(Produit $produit): bool
    {
        return ProduitRepository::update($produit);
    }

    public static function supprimer(int $id): bool
    {
        return ProduitRepository::delete($id);
    }
}