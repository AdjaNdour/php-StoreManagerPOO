<?php

namespace App\Service;

use App\Model\Repository\FournisseurRepository;
use App\Model\Entity\Fournisseur;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;

class FournisseurService
{
    public static function getAllFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        return FournisseurRepository::selectAllFiltered($filtered, $pagination);
    }

    public static function getAll(): array
    {
        return FournisseurRepository::selectAll();
    }

    public static function getById(int $id): ?Fournisseur
    {
        return FournisseurRepository::selectById($id);
    }

    public static function getByTelephone(string $telephone): ?Fournisseur
    {
        return FournisseurRepository::selectByTelephone($telephone);
    }

    public static function enregistrer(Fournisseur $fournisseur): int
    {
        if (trim($fournisseur->getNom()) === '') {
            throw new Exception("Le nom de l'entreprise est obligatoire.");
        }
        if (trim($fournisseur->getTelephone()) === '') {
            throw new Exception("Le numéro de téléphone est obligatoire.");
        }
        return FournisseurRepository::insert($fournisseur);
    }

    public static function save(Fournisseur $fournisseur): int
    {
        return self::enregistrer($fournisseur);
    }

    public static function modifier(Fournisseur $fournisseur): bool
    {
        return FournisseurRepository::update($fournisseur);
    }

    public static function supprimer(int $id): bool
    {
        return FournisseurRepository::delete($id);
    }
}
