<?php

require_once dirname(__DIR__) . "/Model/Repository/ClientRepository.php";

class ClientService
{
  
    public static function getAll(): array
    {
        return ClientRepository::selectAll();
    }

    public static function getById(int $id): Client
    {

        return ClientRepository::selectById($id);
    }
}