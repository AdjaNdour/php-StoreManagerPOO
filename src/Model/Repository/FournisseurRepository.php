<?php

namespace App\Model\Repository;

use App\Core\Database;
use App\Model\Entity\Fournisseur;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;

class FournisseurRepository
{
    public static function selectAllFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        $search = $filtered->getFilter('search');
        $limit = $pagination->getLimit();
        $offset = $pagination->getOffset();

        $where = ["1=1"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(f.nom ILIKE :search OR f.telephone ILIKE :search OR f.adresse ILIKE :search OR f.email ILIKE :search)";
            $params['search'] = "%$search%";
        }

        $whereClause = implode(" AND ", $where);

        $sqlCount = "SELECT COUNT(DISTINCT f.id) AS total FROM fournisseurs f WHERE $whereClause";
        $countRes = Database::executeQuery($sqlCount, $params, true);
        $total = (int)($countRes->total ?? 0);
        $pagination->setTotalElements($total);

        $sql = "SELECT f.id AS fournisseur_id, f.id, f.nom AS fournisseur_nom, f.nom, f.email AS fournisseur_email, f.email, f.telephone AS fournisseur_telephone, f.telephone, f.adresse AS fournisseur_adresse, f.adresse
                FROM fournisseurs f
                WHERE $whereClause
                ORDER BY f.nom ASC
                LIMIT $limit OFFSET $offset";

        $results = Database::executeQuery($sql, $params, false);
        return (!empty($results) && is_array($results)) ? array_map(fn($row) => Fournisseur::toEntity($row), $results) : [];
    }

    public static function insert(Fournisseur $fournisseur): int
    {
        $sql = "INSERT INTO fournisseurs (nom, email, telephone, adresse)
                VALUES (:nom, :email, :telephone, :adresse) RETURNING id";

        $res = Database::executeQuery($sql, [
            'nom' => $fournisseur->getNom(),
            'email' => $fournisseur->getEmail(),
            'telephone' => $fournisseur->getTelephone(),
            'adresse' => $fournisseur->getAdresse()
        ], true);

        $id = (int)($res->id ?? 0);
        $fournisseur->setId($id);
        return $id;
    }

    public static function selectById(int $id): ?Fournisseur
    {
        $sql = "SELECT id AS fournisseur_id, id, nom AS fournisseur_nom, nom, email AS fournisseur_email, email, telephone AS fournisseur_telephone, telephone, adresse AS fournisseur_adresse, adresse
                FROM fournisseurs WHERE id = :id";

        $obj = Database::executeQuery($sql, ['id' => $id], true);
        return $obj ? Fournisseur::toEntity($obj) : null;
    }

    public static function selectByTelephone(string $telephone): ?Fournisseur
    {
        $sql = "SELECT id AS fournisseur_id, id, nom AS fournisseur_nom, nom, email AS fournisseur_email, email, telephone AS fournisseur_telephone, telephone, adresse AS fournisseur_adresse, adresse
                FROM fournisseurs WHERE telephone = :telephone LIMIT 1";

        $obj = Database::executeQuery($sql, ['telephone' => $telephone], true);
        return $obj ? Fournisseur::toEntity($obj) : null;
    }

    public static function selectAll(): array
    {
        $sql = "SELECT id AS fournisseur_id, id, nom AS fournisseur_nom, nom, email AS fournisseur_email, email, telephone AS fournisseur_telephone, telephone, adresse AS fournisseur_adresse, adresse
                FROM fournisseurs ORDER BY nom ASC";

        $results = Database::query($sql, false);
        return (!empty($results) && is_array($results)) ? array_map(fn($row) => Fournisseur::toEntity($row), $results) : [];
    }

    public static function update(Fournisseur $fournisseur): bool
    {
        $sql = "UPDATE fournisseurs SET nom = :nom, email = :email, telephone = :telephone, adresse = :adresse 
                WHERE id = :id";

        $affected = Database::executeUpdate($sql, [
            'id' => $fournisseur->getId(),
            'nom' => $fournisseur->getNom(),
            'email' => $fournisseur->getEmail(),
            'telephone' => $fournisseur->getTelephone(),
            'adresse' => $fournisseur->getAdresse()
        ]);

        return $affected > 0;
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM fournisseurs WHERE id = :id";
        $affected = Database::executeUpdate($sql, ['id' => $id]);
        return $affected > 0;
    }
}
