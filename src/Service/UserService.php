<?php

namespace App\Service;

use App\Model\Repository\UserRepository;
use App\Model\Entity\Utilisateur;
use Exception;

class UserService
{
    public static function getByEmail(string $email): ?Utilisateur
    {
        return UserRepository::selectByEmail($email);
    }
}
