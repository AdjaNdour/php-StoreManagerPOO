<?php

require_once dirname(__DIR__) . "/Model/Repository/ClientRepository.php";

class ClientService
{
    private ClientRepository $repoClient;

    public function __construct()
    {
        $this->repoClient = new ClientRepository();
    }

    public function getAll(): array
    {
        return $this->repoClient->selectAll();
    }

    public function getById(int $id): Client
    {

        return $this->repoClient->selectById($id);
    }
}