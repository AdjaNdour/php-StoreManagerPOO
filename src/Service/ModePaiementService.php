<?php

require_once dirname(__DIR__) . "/Model/Entity/ModePaiement.php";
require_once dirname(__DIR__) . "/Core/Database.php";

class ModePaiementService
{

    public static function getAll(): array
    {
        return PaiementModeRepository::selectAll();
    }
  
}
