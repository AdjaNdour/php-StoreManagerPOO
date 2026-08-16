<?php

require_once dirname(__DIR__) . "/Model/Entity/ModePaiement.php";
require_once dirname(__DIR__) . "/Core/Database.php";

class ModePaiementService
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connexionDB();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM modes_paiement ORDER BY id ASC";
        $tableau = Database::query($this->pdo, $sql, false);
        $modes = [];

        if (empty($tableau)) {
            return $modes;
        }

        foreach ($tableau as $row) {
            $modes[] = new ModePaiement($row['nom'], (int) $row['id']);
        }

        return $modes;
    }
}
