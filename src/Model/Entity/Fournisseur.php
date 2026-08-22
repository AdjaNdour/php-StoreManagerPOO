<?php

namespace App\Model\Entity;

use stdClass;

class Fournisseur
{
    private ?int $id;
    private string $nom;
    private ?string $email;
    private string $telephone;
    private ?string $adresse;

    public function __construct(
        string $nom,
        string $telephone,
        ?string $email = null,
        ?string $adresse = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->adresse = $adresse;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public static function toEntity(stdClass $obj): self
    {
        $id = $obj->fournisseur_id ?? $obj->id ?? null;
        $nom = $obj->fournisseur_nom ?? $obj->nom ?? '';
        $telephone = $obj->fournisseur_telephone ?? $obj->telephone ?? '';
        $email = $obj->fournisseur_email ?? $obj->email ?? null;
        $adresse = $obj->fournisseur_adresse ?? $obj->adresse ?? null;

        return new self(
            nom: (string)$nom,
            telephone: (string)$telephone,
            email: $email ? (string)$email : null,
            adresse: $adresse ? (string)$adresse : null,
            id: $id !== null ? (int)$id : null
        );
    }
}
