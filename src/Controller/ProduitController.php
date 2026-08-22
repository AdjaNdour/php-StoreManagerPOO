<?php

namespace App\Controller;

class ProduitController
{
    public static function index(): void
    {
        CatalogController::index();
    }

    public static function addProduct(): void
    {
        CatalogController::addProduct();
    }

    public static function addClient(): void
    {
        CatalogController::addClient();
    }

    public static function addSupplier(): void
    {
        CatalogController::addSupplier();
    }
}
