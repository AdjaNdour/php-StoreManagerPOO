<?php

require_once __DIR__ . '/Fournisseur.php';

class Produit
{
    private ?int $id;
    private string $code;
    private string $libelle;
    private string $categorie;
    private float $prixVente;
    private float $coutAchat;
    private int $stockInitial;
    private int $seuilAlerte;
    
    private ?Fournisseur $fournisseur = null;
    private ?int $fournisseurId;

    public function __construct(string $code, string $libelle, string $categorie, float $prixVente, 
                                float $coutAchat = 0.0, int $stockInitial = 0, int $seuilAlerte = 5, 
                                ?int $fournisseurId = null, ?int $id = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->libelle = $libelle;
        $this->categorie = $categorie;
        $this->prixVente = $prixVente;
        $this->coutAchat = $coutAchat;
        $this->stockInitial = $stockInitial;
        $this->seuilAlerte = $seuilAlerte;
        $this->fournisseurId = $fournisseurId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getCategorie(): string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): void
    {
        $this->categorie = $categorie;
    }

    public function getPrixVente(): float
    {
        return $this->prixVente;
    }

    public function setPrixVente(float $prixVente): void
    {
        $this->prixVente = max(0.0, $prixVente);
    }

    public function getCoutAchat(): float
    {
        return $this->coutAchat;
    }

    public function setCoutAchat(float $coutAchat): void
    {
        $this->coutAchat = max(0.0, $coutAchat);
    }

    public function getStockInitial(): int
    {
        return $this->stockInitial;
    }

    public function setStockInitial(int $stockInitial): void
    {
        $this->stockInitial = max(0, $stockInitial);
    }

    public function getSeuilAlerte(): int
    {
        return $this->seuilAlerte;
    }

    public function setSeuilAlerte(int $seuilAlerte): void
    {
        $this->seuilAlerte = max(0, $seuilAlerte);
    }

    public function getFournisseurId(): ?int
    {
        return $this->fournisseurId;
    }

    public function setFournisseurId(?int $fournisseurId): void
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


}
