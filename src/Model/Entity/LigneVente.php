<?php

namespace App\Model\Entity;

use stdClass;

class LigneVente
{
    private ?int $id;
    private int $venteId;
    private int $quantite;
    private float $prixUnitaire;
    private Produit $produit;

    public function __construct(
        Produit $produit,
        int $quantite,
        float $prixUnitaire,
        int $venteId = 0,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->venteId = $venteId;
        $this->produit = $produit;
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
        $this->venteId = $venteId ?? 0;
    }

    public function getProduitId(): int
    {
        return $this->produit->getId() ?? 0;
    }

    public function getProduit(): Produit
    {
        return $this->produit;
    }

    public function setProduit(Produit $produit): void
    {
        $this->produit = $produit;
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

    public static function toEntity(stdClass $obj): self
    {
        $id = $obj->ligne_id ?? $obj->ligne_vente_id ?? $obj->id ?? null;
        $venteId = $obj->vente_id ?? 0;
        $quantite = $obj->quantite ?? 1;
        $prixUnitaire = $obj->prix_unitaire ?? 0;

        $produit = Produit::toEntity($obj);

        return new self(
            produit: $produit,
            quantite: (int)$quantite,
            prixUnitaire: (float)$prixUnitaire,
            venteId: (int)$venteId,
            id: $id !== null ? (int)$id : null
        );
    }
}
