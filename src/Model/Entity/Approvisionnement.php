<?php

namespace App\Model\Entity;

use stdClass;

class Approvisionnement
{
    private ?int $id;
    private string $referenceBl;
    private float $coutAchat;
    private ?string $dateAppro;
    private ?string $dateReception;
    private Fournisseur $fournisseur;
    private ?Utilisateur $utilisateur = null;
    private array $lignes = [];

    public function __construct(
        string $referenceBl,
        Fournisseur $fournisseur,
        ?string $dateReception = null,
        ?int $id = null,
        ?string $dateAppro = null,
        ?Utilisateur $utilisateur = null,
        float $coutAchat = 0.0
    ) {
        $this->id = $id;
        $this->referenceBl = $referenceBl;
        $this->coutAchat = $coutAchat;
        $this->dateReception = $dateReception;
        $this->dateAppro = $dateAppro;
        $this->fournisseur = $fournisseur;
        $this->utilisateur = $utilisateur;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getReferenceBl(): string
    {
        return $this->referenceBl;
    }

    public function setReferenceBl(string $referenceBl): void
    {
        $this->referenceBl = $referenceBl;
    }

    public function getCoutAchat(): float
    {
        return $this->coutAchat;
    }

    public function setCoutAchat(float $coutAchat): void
    {
        $this->coutAchat = max(0.0, $coutAchat);
    }

    public function getDateAppro(): ?string
    {
        return $this->dateAppro;
    }

    public function setDateAppro(?string $dateAppro): void
    {
        $this->dateAppro = $dateAppro;
    }

    public function getDateReception(): ?string
    {
        return $this->dateReception;
    }

    public function setDateReception(?string $dateReception): void
    {
        $this->dateReception = $dateReception;
    }

    public function getFournisseur(): Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(Fournisseur $fournisseur): void
    {
        $this->fournisseur = $fournisseur;
    }

    public function getFournisseurId(): int
    {
        return $this->fournisseur->getId() ?? 0;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

    public function getLignes(): array
    {
        return $this->lignes;
    }

    public function setLignes(array $lignes): void
    {
        $this->lignes = $lignes;
    }

    public function ajouterLigne(LigneApprovisionnement $ligne): void
    {
        $this->lignes[] = $ligne;
    }

    public static function toEntity(stdClass $obj): self
    {
        $id = $obj->appro_id ?? $obj->approvisionnement_id ?? $obj->id ?? null;
        $referenceBl = $obj->reference_bl ?? '';
        $coutAchat = $obj->cout_achat ?? 0;
        $dateAppro = $obj->date_appro ?? null;
        $dateReception = $obj->date_reception ?? null;

        $fournisseur = Fournisseur::toEntity($obj);
        $hasUser = isset($obj->nom_utilisateur) || isset($obj->utilisateur_id);
        $utilisateur = $hasUser ? Utilisateur::toEntity($obj) : null;

        return new self(
            referenceBl: (string)$referenceBl,
            fournisseur: $fournisseur,
            dateReception: $dateReception ? (string)$dateReception : null,
            id: $id !== null ? (int)$id : null,
            dateAppro: $dateAppro ? (string)$dateAppro : null,
            utilisateur: $utilisateur,
            coutAchat: (float)$coutAchat
        );
    }
}
