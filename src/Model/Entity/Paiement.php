<?php

namespace App\Model\Entity;

use stdClass;

class Paiement
{
    private ?int $id;
    private float $montant;
    private ?string $notes;
    private ?string $datePaiement;
    private ?Dette $dette;
    private ModePaiement $modePaiement;
    private ?Utilisateur $utilisateur;

    public function __construct(
        ModePaiement $modePaiement,
        float $montant,
        ?Utilisateur $utilisateur = null,
        ?Dette $dette = null,
        ?string $notes = null,
        ?int $id = null,
        ?string $datePaiement = null
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
        return $this->modePaiement->getId() ?? 0;
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
        return $this->utilisateur?->getId();
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

    public static function toEntity(stdClass $obj): self
    {
        $id = $obj->paiement_id ?? $obj->id ?? null;
        $montant = $obj->montant ?? 0;
        $notes = $obj->notes ?? null;
        $datePaiement = $obj->date_paiement ?? null;

        $modePaiement = ModePaiement::toEntity($obj);

        $hasUser = isset($obj->nom_utilisateur) || isset($obj->utilisateur_id);
        $utilisateur = $hasUser ? Utilisateur::toEntity($obj) : null;

        return new self(
            modePaiement: $modePaiement,
            montant: (float)$montant,
            utilisateur: $utilisateur,
            dette: null,
            notes: $notes ? (string)$notes : null,
            id: $id !== null ? (int)$id : null,
            datePaiement: $datePaiement ? (string)$datePaiement : null
        );
    }
}
