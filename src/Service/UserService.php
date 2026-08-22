<?php

namespace App\Service;

use App\Model\Repository\UserRepository;
use App\Model\Entity\Utilisateur;
use Exception;

class UserService
{
    public static function getUserByEmail(string $email): ?Utilisateur
    {
        return UserRepository::selectByEmail($email);
    }

    public static function getByEmail(string $email): ?Utilisateur
    {
        return UserRepository::selectByEmail($email);
    }

    public static function getById(int $id): ?Utilisateur
    {
        return UserRepository::selectById($id);
    }

    public static function getAll(): array
    {
        return UserRepository::selectAll();
    }

    public static function enregistrer(Utilisateur $user): int
    {
        if (trim($user->getNom()) === '' || trim($user->getPrenom()) === '') {
            throw new Exception("Le nom et prénom sont obligatoires.");
        }
        if (trim($user->getEmail()) === '') {
            throw new Exception("L'adresse email est obligatoire.");
        }
        return UserRepository::insert($user);
    }

    public static function save(Utilisateur $user): int
    {
        return self::enregistrer($user);
    }

    public static function modifier(Utilisateur $user): bool
    {
        return UserRepository::update($user);
    }

    public static function supprimer(int $id): bool
    {
        return UserRepository::delete($id);
    }
}
