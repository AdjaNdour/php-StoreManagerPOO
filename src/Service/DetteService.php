<?php

namespace App\Service;

use App\Model\Repository\DetteRepository;
use App\Model\Repository\PaiementRepository;
use App\Model\Entity\Dette;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;

class DetteService
{
    public static function getAllFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        return DetteRepository::selectAllFiltered($filtered, $pagination);
    }

    public static function getStatistiques(): object
    {
        return DetteRepository::selectStatistiques();
    }

    public static function rembourserDette(int $detteId, float $montant, int $modePaiementId, ?int $utilisateurId = null, ?string $notes = null): bool
    {
        return PaiementRepository::insertPaiement($detteId, $montant, $modePaiementId, $utilisateurId, $notes);
    }
}
