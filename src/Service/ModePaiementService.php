<?php

namespace App\Service;

use App\Model\Repository\ModePaiementRepository;
use App\Model\Entity\ModePaiement;

class ModePaiementService
{
    public static function getAll(): array
    {
        return ModePaiementRepository::selectAll();
    }

    public static function getById(int $id): ?ModePaiement
    {
        return ModePaiementRepository::selectById($id);
    }

    public static function getByNom(string $nom): ?ModePaiement
    {
        return ModePaiementRepository::selectByNom($nom);
    }
}
