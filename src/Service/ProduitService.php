<?php

require_once dirname(__DIR__) . "/Model/Repository/ProduitRepository.php";

class ProduitService
{
  
    public static function getAll(): array
    {
        return ProduitRepository::selectAll();
    }

    public static function getById(int $id): Produit
    {
        return ProduitRepository::selectById($id);
    }
}