<?php

namespace App\Model\Repository;

use Adja\Core\Database;
use App\Model\Entity\ModePaiement;

class ModePaiementRepository
{
    public static function selectAll(): array
    {
        $sql = "SELECT id AS mode_paiement_id, id, nom AS mode_paiement_nom, nom FROM modes_paiement ORDER BY id ASC";
        $results = Database::query($sql, false);
        if (!empty($results)) {
            return array_map(fn($mode) => ModePaiement::toEntity($mode), $results);
        }
        return [];
    }
}
