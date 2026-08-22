<?php

namespace App\Model\Repository;

use App\Core\Database;
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

    public static function selectUserByEmail(string $email): ?Utilisateur
    {
        return self::selectByEmail($email);
    }

    public static function selectById(int $id): ?Utilisateur
    {
        $sql = "SELECT u.id AS utilisateur_id, u.id, u.nom, u.prenom, u.email, u.password, u.adresse, u.telephone, u.role_id,
                       r.id AS role_id, r.nom AS role_nom
                FROM utilisateurs u
                JOIN roles r ON r.id = u.role_id
                WHERE u.id = :id";

        $obj = Database::executeQuery($sql, ['id' => $id], true);
        return $obj ? Utilisateur::toEntity($obj) : null;
    }

    public static function selectAll(): array
    {
        $sql = "SELECT u.id AS utilisateur_id, u.id, u.nom, u.prenom, u.email, u.password, u.adresse, u.telephone, u.role_id,
                       r.id AS role_id, r.nom AS role_nom
                FROM utilisateurs u
                JOIN roles r ON r.id = u.role_id
                ORDER BY u.id ASC";

        $results = Database::query($sql, false);
        return (!empty($results) && is_array($results)) ? array_map(fn($row) => Utilisateur::toEntity($row), $results) : [];
    }

    public static function insert(Utilisateur $user): int
    {
        $sql = "INSERT INTO utilisateurs (nom, prenom, email, password, adresse, telephone, role_id)
                VALUES (:nom, :prenom, :email, :password, :adresse, :telephone, :role_id) RETURNING id";

        $res = Database::executeQuery($sql, [
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'adresse' => $user->getAdresse(),
            'telephone' => $user->getTelephone(),
            'role_id' => $user->getRoleId() ?? 2
        ], true);

        $id = (int)($res->id ?? 0);
        $user->setId($id);
        return $id;
    }

    public static function update(Utilisateur $user): bool
    {
        $sql = "UPDATE utilisateurs
                SET nom = :nom, prenom = :prenom, email = :email, adresse = :adresse, telephone = :telephone, role_id = :role_id
                WHERE id = :id";

        $affected = Database::executeUpdate($sql, [
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'email' => $user->getEmail(),
            'adresse' => $user->getAdresse(),
            'telephone' => $user->getTelephone(),
            'role_id' => $user->getRoleId() ?? 2
        ]);

        return $affected > 0;
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM utilisateurs WHERE id = :id";
        $affected = Database::executeUpdate($sql, ['id' => $id]);
        return $affected > 0;
    }
}
