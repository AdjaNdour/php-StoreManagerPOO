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
    public static function getAllDettesFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        return DetteRepository::selectAllDettesFiltered($filtered, $pagination);
    }

    public static function getAll(): array
    {
        return DetteRepository::selectAll();
    }

    public static function getActiveDebts(): array
    {
        return DetteRepository::selectActiveDettes();
    }

    public static function getActiveDettes(): array
    {
        return DetteRepository::selectActiveDettes();
    }

    public static function getById(int $id): ?Dette
    {
        return DetteRepository::selectById($id);
    }

    public static function getByClientId(int $clientId): array
    {
        return DetteRepository::selectByClientId($clientId);
    }

    public static function getStatistiques(): object
    {
        return DetteRepository::selectStatistiques();
    }


    public static function rembourserDette(int $detteId, float $montant, int $modePaiementId, ?int $utilisateurId = null, ?string $notes = null): bool
    {
        if ($montant <= 0) {
            throw new Exception("Le montant du versement doit être strictement positif.");
        }

        if ($modePaiementId <= 0) {
            throw new Exception("Le mode de règlement est obligatoire.");
        }

        return PaiementRepository::enregistrerPaiement($detteId, $montant, $modePaiementId, $utilisateurId, $notes);
    }

    public static function getAllProduitsDette(int $detteId): array
    {
        return DetteRepository::selectProduitsByDetteId($detteId);
    }

    public static function getPaiementsByDette(int $detteId): array
    {
        return DetteRepository::selectPaiementsByDetteId($detteId);
    }

    public static function creerDette(Dette $dette): int
    {
        return DetteRepository::insert($dette);
    }
}
