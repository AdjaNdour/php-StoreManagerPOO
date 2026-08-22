<?php

namespace App\Model\Entity;

use stdClass;

class ModePaiement
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
        $id = $obj->mode_paiement_id ?? $obj->mode_id ?? $obj->id ?? null;
        $nom = $obj->mode_paiement_nom ?? $obj->mode_nom ?? $obj->nom ?? 'Espèces';

        return new self(
            nom: (string)$nom,
            id: $id !== null ? (int)$id : null
        );
    }
}
