<?php

require_once dirname(__DIR__) . "/Model/Repository/DetteRepository.php";
require_once dirname(__DIR__) . "/Model/Repository/PaiementRepository.php";
require_once __DIR__ . "/ModePaiementService.php";

class DebtService
{


    public function getAll(): array
    {
        return DetteRepository::selectAll();
    }

    public function getActiveDebts(): array
    {
        return DetteRepository::selectActiveDettes();
    }

    public function getById(int $id): ?Dette
    {
        return DetteRepository::selectById($id);
    }

    public function getStatistiques(): array
    {
        return DetteRepository::selectStatistiques();
    }

    public function enregistrerPaiement(int $detteId, float $montant, int $modePaiementId, ?int $utilisateurId = null, ?string $notes = null): bool
    {
        if ($montant <= 0) {
            throw new Exception("Le montant du versement doit être strictement positif.");
        }

        if ($modePaiementId <= 0) {
            throw new Exception("Le mode de règlement est obligatoire.");
        }

        return PaiementRepository::enregistrerPaiement($detteId, $montant, $modePaiementId, $utilisateurId, $notes);
    }

    public function getAllProduitsDette(int $detteId)
    {
        return DetteRepository::selectProduitsByDetteId($detteId);
    }
}
