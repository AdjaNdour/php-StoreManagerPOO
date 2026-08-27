<?php

namespace App\Service;

use App\Model\Repository\ProduitRepository;
use App\Model\Entity\Produit;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Adja\Core\Database;
use Exception;

class ProduitService
{
    public static function save(Produit $produit): int
    {

        if (empty($produit->getCode())) {
            $produit->setCode(self::genererCodeProduit($produit->getLibelle()));
        }

        return ProduitRepository::insert($produit);
    }
    
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

    public static function getStatistiques(): object
    {
        return ProduitRepository::selectStatistiques();
    }

    public static function genererCodeProduit(string $libelle = ''): string
    {
        $id = Database::getLastId("produits") + 1;
        $prefix = !empty($libelle) ? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $libelle), 0, 5)) : 'ART';
        return "PRD-" . $prefix . $id;
    }
}
