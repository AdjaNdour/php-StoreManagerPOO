<?php

namespace App\Model\Entity;

use stdClass;

class StatutDette
{
    private ?int $id;
    private string $nom;

    public function __construct(string $nom, ?int $id = null)
    {
        $this->id = $id;
        $this->nom = $nom;
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

    public static function toEntity(stdClass $obj): self
    {
        $id = $obj->statut_dette_id ?? $obj->id ?? null;
        $nom = $obj->statut_nom ?? $obj->statut_dette_nom ?? $obj->statut ?? $obj->nom ?? 'NON SOLDEE';

        return new self(
            nom: (string)$nom,
            id: $id !== null ? (int)$id : null
        );
    }
}
