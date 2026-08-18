<?php

require_once __DIR__ . '/Utilisateur.php';
require_once __DIR__ . '/ModePaiement.php';

class Paiement
{
    private ?int $id;
    private float $montant;
    private ?string $notes;
    private ?string $datePaiement;
    
    private ?Dette $dette ;
    private ModePaiement $modePaiement;
    private ?Utilisateur $utilisateur;

    public function __construct(ModePaiement $modePaiement, float $montant,
                                ?Utilisateur $utilisateur = null,?Dette $dette=null,
                                ?string $notes = null, ?int $id = null, ?string $datePaiement = null
    ) {
        $this->id = $id;
        $this->dette = $dette;
        $this->montant = $montant;
        $this->notes = $notes;
        $this->datePaiement = $datePaiement;
        $this->utilisateur = $utilisateur;
        $this->modePaiement = $modePaiement;

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDette(): ?Dette
    {
        return $this->dette;
    }

    public function setDette(?Dette $dette): void
    {
        $this->dette = $dette;
    }

    public function getModePaiementId(): int
    {
        return $this->modePaiement->getId();
    }

    public function getModePaiement(): ModePaiement
    {
        return $this->modePaiement;
    }

    public function setModePaiement(ModePaiement $modePaiement): void
    {
        $this->modePaiement = $modePaiement;
    
    }

    public function getMontant(): float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): void
    {
        $this->montant = max(0.0, $montant);
    }

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateur->getId();
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    public function getDatePaiement(): ?string
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(?string $datePaiement): void
    {
        $this->datePaiement = $datePaiement;
    }
}
