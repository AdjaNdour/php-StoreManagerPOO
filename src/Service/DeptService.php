<?php

require_once dirname(__DIR__) . "/Model/Repository/DetteRepository.php";
require_once dirname(__DIR__) . "/Model/Repository/PaiementRepository.php";
require_once __DIR__ . "/ModePaiementService.php";

class DebtService
{


    public static function getAll(): array
    {
        return DetteRepository::selectAll();
    }

    public static function getActiveDebts(): array
    {
        return DetteRepository::selectActiveDettes();
    }

    public static function getById(int $id): ?Dette
    {
        return DetteRepository::selectById($id);
    }

    public static function getStatistiques(): array
    {
        return DetteRepository::selectStatistiques();
    }

    public static function enregistrerPaiement(int $detteId, float $montant, int $modePaiementId, ?int $utilisateurId = null, ?string $notes = null): bool
    {
        if ($montant <= 0) {
            throw new Exception("Le montant du versement doit être strictement positif.");
        }

        if ($modePaiementId <= 0) {
            throw new Exception("Le mode de règlement est obligatoire.");
        }

        return PaiementRepository::enregistrerPaiement($detteId, $montant, $modePaiementId, $utilisateurId, $notes);
    }

    public static function getAllProduitsDette(int $detteId)
    {
        return DetteRepository::selectProduitsByDetteId($detteId);
    }
}
