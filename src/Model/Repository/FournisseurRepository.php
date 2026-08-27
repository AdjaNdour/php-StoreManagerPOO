<?php

namespace App\Model\Repository;

use Adja\Core\Database;
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

        $params = [];
        $sqlFilter = " 1=1 ";

        if (!empty($search)) {
            $sqlFilter .= " AND (f.nom ILIKE :search OR f.telephone ILIKE :search OR f.adresse ILIKE :search OR f.email ILIKE :search)";
            $params['search'] = "%$search%";
        }

        $sqlCount = "SELECT COUNT(DISTINCT f.id) AS total FROM fournisseurs f WHERE $sqlFilter";
        $countRes = Database::executeQuery($sqlCount, $params);
        $total = (int)($countRes->total ?? 0);
        $pagination->setTotalElements($total);

        $sql = "SELECT f.id AS fournisseur_id, f.id, f.nom AS fournisseur_nom, f.nom, f.email AS fournisseur_email, f.email, f.telephone AS fournisseur_telephone, f.telephone, f.adresse AS fournisseur_adresse, f.adresse
                FROM fournisseurs f
                WHERE $sqlFilter
                ORDER BY f.nom ASC
                LIMIT $limit OFFSET $offset";

        $results = Database::executeQuery($sql, $params, false);

        if (!empty($results)) {
            return array_map(fn($fournisseur) => Fournisseur::toEntity($fournisseur), $results);
        }
        return [];
    }

    public static function insert(Fournisseur $fournisseur): int
    {
        $sql = "INSERT INTO fournisseurs (nom, email, telephone, adresse)
                VALUES (:nom, :email, :telephone, :adresse) RETURNING id";

        $res = Database::executeQuery($sql, [
            'nom' => $fournisseur->getNom(),
            'email' => !empty($fournisseur->getEmail()) ? $fournisseur->getEmail() : null,
            'telephone' => $fournisseur->getTelephone(),
            'adresse' => !empty($fournisseur->getAdresse()) ? $fournisseur->getAdresse() : null
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

    public static function selectAll(): array
    {
        $sql = "SELECT id AS fournisseur_id, id, nom AS fournisseur_nom, nom, email AS fournisseur_email, email, telephone AS fournisseur_telephone, telephone, adresse AS fournisseur_adresse, adresse
                FROM fournisseurs ORDER BY nom ASC";

        $results = Database::query($sql, false);
        if (!empty($results)) {
            return array_map(fn($fournisseur) => Fournisseur::toEntity($fournisseur), $results);
        }
        return [];
    }
}
