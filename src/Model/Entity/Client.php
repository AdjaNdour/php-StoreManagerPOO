<?php

namespace App\Model\Entity;

use stdClass;

class Client
{
    private ?int $id;
    private string $nom;
    private string $prenom;
    private string $telephone;
    private ?string $email;
    private float $limiteCredit;

    public function __construct(
        string $nom,
        string $prenom,
        string $telephone,
        ?string $email = null,
        ?int $id = null,
        float $limiteCredit = 0.0
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->limiteCredit = $limiteCredit;
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

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getNomComplet(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getLimiteCredit(): float
    {
        return $this->limiteCredit;
    }

    public function setLimiteCredit(float $limiteCredit): void
    {
        $this->limiteCredit = max(0.0, $limiteCredit);
    }

    public static function toEntity(stdClass $obj): self
    {
        $id = $obj->client_id ?? $obj->id ?? null;
        $nom = $obj->client_nom ?? $obj->nom ?? '';
        $prenom = $obj->client_prenom ?? $obj->prenom ?? '';
        $telephone = $obj->client_telephone ?? $obj->telephone ?? '';
        $email = $obj->client_email ?? $obj->email ?? null;
        $limite = $obj->client_limite ?? $obj->limite_credit ?? 0;

        return new self(
            nom: (string)$nom,
            prenom: (string)$prenom,
            telephone: (string)$telephone,
            email: $email ? (string)$email : null,
            id: $id !== null ? (int)$id : null,
            limiteCredit: (float)$limite
        );
    }
}
