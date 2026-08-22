<?php

namespace App\Model\Entity;

use stdClass;

class LigneApprovisionnement
{
    private ?int $id;
    private int $approvisionnementId;
    private int $quantiteAppro;
    private int $quantiteRecue;
    private float $prixAchat;
    private float $sousTotal;
    private Produit $produit;

    public function __construct(
        int $approvisionnementId,
        int $quantiteAppro,
        float $prixAchat,
        Produit $produit,
        ?float $sousTotal = null,
        ?int $id = null,
        int $quantiteRecue = 0
    ) {
        $this->id = $id;
        $this->produit = $produit;
        $this->approvisionnementId = $approvisionnementId;
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
        $this->approvisionnementId = $approvisionnementId ?? 0;
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

    public static function toEntity(stdClass $obj): self
    {
        $id = $obj->ligne_appro_id ?? $obj->id ?? null;
        $approId = $obj->approvisionnement_id ?? 0;
        $quantiteAppro = $obj->quantite_appro ?? 1;
        $quantiteRecue = $obj->quantite_recue ?? 0;
        $prixAchat = $obj->prix_achat ?? 0;
        $sousTotal = $obj->sous_total ?? null;

        $produit = Produit::toEntity($obj);

        return new self(
            approvisionnementId: (int)$approId,
            quantiteAppro: (int)$quantiteAppro,
            prixAchat: (float)$prixAchat,
            produit: $produit,
            sousTotal: $sousTotal !== null ? (float)$sousTotal : null,
            id: $id !== null ? (int)$id : null,
            quantiteRecue: (int)$quantiteRecue
        );
    }
}
