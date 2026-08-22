<?php

namespace App\Model\Entity;

use stdClass;

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

    public function __construct(
        string $code,
        string $libelle,
        string $categorie,
        float $prixVente,
        float $coutAchat = 0.0,
        int $stockInitial = 0,
        int $seuilAlerte = 5,
        ?Fournisseur $fournisseur = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->libelle = $libelle;
        $this->categorie = $categorie;
        $this->prixVente = $prixVente;
        $this->coutAchat = $coutAchat;
        $this->stockInitial = $stockInitial;
        $this->seuilAlerte = $seuilAlerte;
        $this->fournisseur = $fournisseur;
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
        return $this->fournisseur?->getId();
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): void
    {
        $this->fournisseur = $fournisseur;
    }

    public static function toEntity(stdClass $obj): self
    {
        $id = $obj->produit_id ?? $obj->id ?? null;
        $code = $obj->produit_code ?? $obj->code ?? '';
        $libelle = $obj->produit_libelle ?? $obj->libelle ?? '';
        $categorie = $obj->produit_categorie ?? $obj->categorie ?? '';
        $prixVente = $obj->prix_vente ?? 0;
        $coutAchat = $obj->cout_achat ?? 0;
        $stockInitial = $obj->stock_initial ?? $obj->stock ?? $obj->quantite ?? 0;
        $seuilAlerte = $obj->seuil_alerte ?? 5;

        $hasFournisseur = isset($obj->fournisseur_nom);
        $fournisseur = $hasFournisseur ? Fournisseur::toEntity($obj) : null;

        return new self(
            code: (string)$code,
            libelle: (string)$libelle,
            categorie: (string)$categorie,
            prixVente: (float)$prixVente,
            coutAchat: (float)$coutAchat,
            stockInitial: (int)$stockInitial,
            seuilAlerte: (int)$seuilAlerte,
            fournisseur: $fournisseur,
            id: $id !== null ? (int)$id : null
        );
    }
}
