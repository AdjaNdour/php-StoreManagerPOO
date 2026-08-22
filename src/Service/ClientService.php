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

    public static function getByTelephone(string $telephone): ?Client
    {
        return ClientRepository::selectClientByTelephone($telephone);
    }

    public static function getCreditActuel(int $clientId): float
    {
        return (float)ClientRepository::getColonneClient($clientId, 'credit');
    }

    public static function getLimiteCredit(int $clientId): float
    {
        return (float)ClientRepository::getColonneClient($clientId, 'limite_credit');
    }

    public static function peutPrendreCredit(int $clientId, float $montantCredit): bool
    {
        $creditActuel = self::getCreditActuel($clientId);
        $limite = self::getLimiteCredit($clientId);
        return ($creditActuel + $montantCredit) <= $limite;
    }

    public static function enregistrer(Client $client): int
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

    public static function save(Client $client): int
    {
        return self::enregistrer($client);
    }

    public static function modifier(Client $client): bool
    {
        return ClientRepository::update($client);
    }

    public static function supprimer(int $id): bool
    {
        return ClientRepository::delete($id);
    }
}