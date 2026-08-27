<?php

namespace App\Service;

use App\Model\Repository\FournisseurRepository;
use App\Model\Entity\Fournisseur;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;

class FournisseurService
{
    public static function getAllFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        return FournisseurRepository::selectAllFiltered($filtered, $pagination);
    }

    public static function getAll(): array
    {
        return FournisseurRepository::selectAll();
    }

    public static function getById(int $id): ?Fournisseur
    {
        return FournisseurRepository::selectById($id);
    }

    public static function save(Fournisseur $fournisseur): int
    {
        return FournisseurRepository::insert($fournisseur);
    }
}
