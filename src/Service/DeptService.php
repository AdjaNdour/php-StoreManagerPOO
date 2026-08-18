<?php

require_once dirname(__DIR__) . "/Model/Repository/DetteRepository.php";
require_once dirname(__DIR__) . "/Model/Repository/PaiementRepository.php";
require_once __DIR__ . "/ModePaiementService.php";

class DebtService
{
    private DetteRepository $repoDette;
    private PaiementRepository $repoPaiement;

    public function __construct()
    {
        $this->repoDette = new DetteRepository();
        $this->repoPaiement = new PaiementRepository();
    }

    public function getAll(): array
    {
        return $this->repoDette->selectAll();
    }

    public function getActiveDebts(): array
    {
        return $this->repoDette->selectActiveDettes();
    }

    public function getById(int $id): ?Dette
    {
        return $this->repoDette->selectById($id);
    }

    public function getStatistiques(): array
    {
        return $this->repoDette->selectStatistiques();
    }

    public function enregistrerPaiement(int $detteId, float $montant, int $modePaiementId, ?int $utilisateurId = null, ?string $notes = null): bool
    {
        if ($montant <= 0) {
            throw new Exception("Le montant du versement doit être strictement positif.");
        }

        if ($modePaiementId <= 0) {
            throw new Exception("Le mode de règlement est obligatoire.");
        }

        return $this->repoPaiement->enregistrerPaiement($detteId, $montant, $modePaiementId, $utilisateurId, $notes);
    }

    public function getAllProduitsDette(int $detteId)
    {
        return $this->repoDette->selectProduitsByDetteId($detteId);
    }
}
