<?php

namespace App\Model\Repository;

use Adja\Core\Database;
use App\Model\Entity\Utilisateur;

class UserRepository
{
    public static function selectByEmail(string $email): ?Utilisateur
    {
        $sql = "SELECT u.id AS utilisateur_id, u.id, u.nom, u.prenom, u.email, u.password, u.adresse, u.telephone, u.role_id,
                       r.id AS role_id, r.nom AS role_nom
                FROM utilisateurs u
                JOIN roles r ON r.id = u.role_id
                WHERE u.email = :email";

        $obj = Database::executeQuery($sql, ['email' => $email], true);
        return $obj ? Utilisateur::toEntity($obj) : null;
    }
}
