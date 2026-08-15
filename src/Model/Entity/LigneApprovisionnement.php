<?php

require_once __DIR__ . '/Produit.php';

class LigneApprovisionnement
{
    private ?int $id;
    private ?int $approvisionnementId;
    private int $quantiteAppro;
    private int $quantiteRecue;
    private float $prixAchat;
    private float $sousTotal;
    
    private int $produitId;
    private ?Produit $produit = null;

    public function __construct(int $produitId, int $quantiteAppro, float $prixAchat, int $quantiteRecue = 0,
        ?float $sousTotal = null, ?int $approvisionnementId = null, ?int $id = null
    ) {
        $this->id = $id;
        $this->approvisionnementId = $approvisionnementId;
        $this->produitId = $produitId;
        $this->quantiteAppro = $quantiteAppro;
        $this->quantiteRecue = $quantiteRecue;
        $this->prixAchat = $prixAchat;
        $this->sousTotal = $sousTotal ?? ($quantiteAppro * $prixAchat);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getApprovisionnementId(): ?int
    {
        return $this->approvisionnementId;
    }

    public function setApprovisionnementId(?int $approvisionnementId): void
    {
        $this->approvisionnementId = $approvisionnementId;
    }

    public function getProduitId(): int
    {
        return $this->produitId;
    }

    public function setProduitId(int $produitId): void
    {
        $this->produitId = $produitId;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): void
    {
        $this->produit = $produit;
        if ($produit !== null && $produit->getId() !== null) {
            $this->produitId = $produit->getId();
        }
    }

    public function getQuantiteAppro(): int
    {
        return $this->quantiteAppro;
    }

    public function setQuantiteAppro(int $quantiteAppro): void
    {
        $this->quantiteAppro = max(1, $quantiteAppro);
        $this->sousTotal = $this->quantiteAppro * $this->prixAchat;
    }

    public function getQuantiteRecue(): int
    {
        return $this->quantiteRecue;
    }

    public function setQuantiteRecue(int $quantiteRecue): void
    {
        $this->quantiteRecue = max(0, $quantiteRecue);
    }

    public function getPrixAchat(): float
    {
        return $this->prixAchat;
    }

    public function setPrixAchat(float $prixAchat): void
    {
        $this->prixAchat = max(0.0, $prixAchat);
        $this->sousTotal = $this->quantiteAppro * $this->prixAchat;
    }

    public function getSousTotal(): float
    {
        return $this->sousTotal;
    }

    public function setSousTotal(float $sousTotal): void
    {
        $this->sousTotal = max(0.0, $sousTotal);
    }
}
