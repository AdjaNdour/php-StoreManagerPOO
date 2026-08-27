<?php

namespace App\Service;

use App\Model\Repository\ClientRepository;
use App\Model\Entity\Client;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;

class ClientService
{
    public static function getAllFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        return ClientRepository::selectAllFiltered($filtered, $pagination);
    }

    public static function getAll(): array
    {
        return ClientRepository::selectAll();
    }

    public static function getById(int $id): ?Client
    {
        return ClientRepository::selectById($id);
    }

    public static function save(Client $client): int
    {
        if (trim($client->getNom()) === '' || trim($client->getPrenom()) === '') {
            throw new Exception("Le prénom et le nom du client sont obligatoires.");
        }
        if (trim($client->getTelephone()) === '') {
            throw new Exception("Le numéro de téléphone est obligatoire.");
        }
        if ($client->getLimiteCredit() < 0) {
            throw new Exception("La limite de crédit ne peut pas être négative.");
        }
        return ClientRepository::insert($client);
    }
}