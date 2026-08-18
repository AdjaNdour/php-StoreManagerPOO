<?php

require_once dirname(__DIR__) . "/Model/Repository/ApprovisionnementRepository.php";
require_once dirname(__DIR__) . "/Model/Entity/Approvisionnement.php";
require_once dirname(__DIR__) . "/Model/Entity/LigneApprovisionnement.php";

class SupplyService
{
    private ApprovisionnementRepository $approRepo;

    public function __construct()
    {
        $this->approRepo = new ApprovisionnementRepository();
    }

    public function getAll(): array
    {
        return $this->approRepo->selectAll();
    }

    public function getById(int $id): ?Approvisionnement
    {
        return $this->approRepo->selectById($id);
    }

    public function getStatistiques(): array
    {
        return $this->approRepo->selectStatistiques();
    }

}
