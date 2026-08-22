<?php

namespace App\Model\Repository;

use App\Core\Database;
use App\Model\Entity\ModePaiement;

class ModePaiementRepository
{
    public static function selectAll(): array
    {
        $sql = "SELECT id AS mode_paiement_id, id, nom AS mode_paiement_nom, nom FROM modes_paiement ORDER BY id ASC";
        $results = Database::query($sql, false);
        return (!empty($results) && is_array($results)) ? array_map(fn($row) => ModePaiement::toEntity($row), $results) : [];
    }

    public static function selectById(int $id): ?ModePaiement
    {
        $sql = "SELECT id AS mode_paiement_id, id, nom AS mode_paiement_nom, nom FROM modes_paiement WHERE id = :id";
        $obj = Database::executeQuery($sql, ['id' => $id], true);
        return $obj ? ModePaiement::toEntity($obj) : null;
    }

    public static function selectByNom(string $nom): ?ModePaiement
    {
        $sql = "SELECT id AS mode_paiement_id, id, nom AS mode_paiement_nom, nom FROM modes_paiement WHERE nom ILIKE :nom LIMIT 1";
        $obj = Database::executeQuery($sql, ['nom' => $nom], true);
        return $obj ? ModePaiement::toEntity($obj) : null;
    }
}
