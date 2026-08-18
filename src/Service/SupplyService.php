<?php

require_once dirname(__DIR__) . "/Model/Repository/ApprovisionnementRepository.php";
require_once dirname(__DIR__) . "/Model/Entity/Approvisionnement.php";
require_once dirname(__DIR__) . "/Model/Entity/LigneApprovisionnement.php";

class SupplyService
{

    public static function getAll(): array
    {
        return ApprovisionnementRepository::selectAll();
    }

    public static function getById(int $id): ?Approvisionnement
    {
        return ApprovisionnementRepository::selectById($id);
    }

    public static function getStatistiques(): array
    {
        return ApprovisionnementRepository::selectStatistiques();
    }

}
