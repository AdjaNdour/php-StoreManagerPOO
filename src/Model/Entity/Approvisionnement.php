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

    private int $fournisseurId;
    private ?Fournisseur $fournisseur = null;
    
    private ?int $utilisateurId;
    private ?Utilisateur $utilisateur = null;

    private array $lignes = [];

    public function __construct(string $referenceBl, int $fournisseurId, float $coutAchat = 0.0, ?string $dateReception = null, 
        ?int $utilisateurId = null, ?int $id = null, ?string $dateAppro = null
    ) {
        $this->id = $id;
        $this->referenceBl = $referenceBl;
        $this->fournisseurId = $fournisseurId;
        $this->coutAchat = $coutAchat;
        $this->dateReception = $dateReception;
        $this->utilisateurId = $utilisateurId;
        $this->dateAppro = $dateAppro;
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

    public function getFournisseurId(): int
    {
        return $this->fournisseurId;
    }

    public function setFournisseurId(int $fournisseurId): void
    {
        $this->fournisseurId = $fournisseurId;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): void
    {
        $this->fournisseur = $fournisseur;
        if ($fournisseur !== null && $fournisseur->getId() !== null) {
            $this->fournisseurId = $fournisseur->getId();
        }
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
