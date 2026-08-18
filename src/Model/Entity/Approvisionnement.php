<?php

require_once __DIR__ . '/Fournisseur.php';
require_once __DIR__ . '/Utilisateur.php';
require_once __DIR__ . '/LigneApprovisionnement.php';

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

    public function __construct( string $referenceBl, Fournisseur $fournisseur,
                                ?string $dateReception,?int $id , ?string $dateAppro , ?Utilisateur $utilisateur,
                                 float $coutAchat = 0.0,
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
        return $this->fournisseur->getId();
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
}
