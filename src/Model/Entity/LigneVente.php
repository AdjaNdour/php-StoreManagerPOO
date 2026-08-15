<?php

require_once __DIR__ . '/Produit.php';

class LigneVente
{
    private ?int $id;
    private ?int $venteId;
    private int $quantite;
    private float $prixUnitaire;

    private int $produitId;
    private ?Produit $produit = null;

    public function __construct(int $produitId, int $quantite, float $prixUnitaire, ?int $venteId = null, ?int $id = null
    ) {
        $this->id = $id;
        $this->venteId = $venteId;
        $this->produitId = $produitId;
        $this->quantite = $quantite;
        $this->prixUnitaire = $prixUnitaire;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getVenteId(): ?int
    {
        return $this->venteId;
    }

    public function setVenteId(?int $venteId): void
    {
        $this->venteId = $venteId;
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

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): void
    {
        $this->quantite = max(1, $quantite);
    }

    public function getPrixUnitaire(): float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): void
    {
        $this->prixUnitaire = max(0.0, $prixUnitaire);
    }

    public function getSousTotal(): float
    {
        return $this->quantite * $this->prixUnitaire;
    }
}
