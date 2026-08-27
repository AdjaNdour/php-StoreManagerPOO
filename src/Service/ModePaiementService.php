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
}
