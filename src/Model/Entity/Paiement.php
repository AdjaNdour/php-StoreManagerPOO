<?php

require_once __DIR__ . '/Utilisateur.php';
require_once __DIR__ . '/ModePaiement.php';

class Paiement
{
    private ?int $id;
    private int $detteId;
    private float $montant;
    private ?string $notes;
    private ?string $datePaiement;

    private int $modePaiementId;
    private ?ModePaiement $modePaiement = null;
    
    private ?int $utilisateurId;
    private ?Utilisateur $utilisateur = null;

    public function __construct(int $detteId, int $modePaiementId, float $montant, ?int $utilisateurId = null,
                                ?string $notes = null, ?int $id = null, ?string $datePaiement = null
    ) {
        $this->id = $id;
        $this->detteId = $detteId;
        $this->montant = $montant;
        $this->notes = $notes;
        $this->datePaiement = $datePaiement;
        $this->utilisateurId = $utilisateurId;
        $this->modePaiementId = $modePaiementId;

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDetteId(): int
    {
        return $this->detteId;
    }

    public function setDetteId(int $detteId): void
    {
        $this->detteId = $detteId;
    }

    public function getModePaiementId(): int
    {
        return $this->modePaiementId;
    }

    public function setModePaiementId(int $modePaiementId): void
    {
        $this->modePaiementId = $modePaiementId;
    }

    public function getModePaiement(): ?ModePaiement
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?ModePaiement $modePaiement): void
    {
        $this->modePaiement = $modePaiement;
        if ($modePaiement !== null && $modePaiement->getId() !== null) {
            $this->modePaiementId = $modePaiement->getId();
        }
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
        return $this->utilisateurId;
    }

    public function setUtilisateurId(?int $utilisateurId): void
    {
        $this->utilisateurId = $utilisateurId;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
        if ($utilisateur !== null && $utilisateur->getId() !== null) {
            $this->utilisateurId = $utilisateur->getId();
        }
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
