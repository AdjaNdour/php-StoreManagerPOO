<?php

require_once dirname(__DIR__) . "/Model/Repository/ProduitRepository.php";

class ProduitService
{
    private ProduitRepository $repoProduit;

    public function __construct()
    {
        $this->repoProduit = new ProduitRepository();
    }

    public function getAll(): array
    {
        return $this->repoProduit->selectAll();
    }

    public function getById(int $id): Produit
    {
        return $this->repoProduit->selectById($id);
    }
}