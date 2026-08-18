<?php

require_once dirname(__DIR__) . "/Entity/Client.php";

class PaiementModeRepository
{
   public static function selectAll(){
       $pdo = Database::connexionDB();

        $sql = "SELECT * FROM modes_paiement ORDER BY id ASC";
        $tableauMode = Database::query($pdo, $sql, false);
        $modes = [];

        if (empty($tableauMode)) {
            return $modes;
        }

        foreach ($tableauMode as $mode) {
            $modes[] = new ModePaiement($mode['nom'], (int) $mode['id']);
        }

        return $modes;
   }
}
