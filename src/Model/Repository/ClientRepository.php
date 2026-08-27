<?php

namespace App\Model\Repository;

use Adja\Core\Database;
use App\Model\Entity\Client;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;

class ClientRepository
{
    public static function selectAllFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        $search = $filtered->getFilter('search');
        $limit = $pagination->getLimit();
        $offset = $pagination->getOffset();

        $params = [];
        $sqlFilter = " 1=1 ";

        if (!empty($search)) {
            $sqlFilter .= " AND (c.nom ILIKE :search OR c.prenom ILIKE :search OR c.telephone ILIKE :search OR c.email ILIKE :search)";
            $params['search'] = "%$search%";
        }

        $sqlCount = "SELECT COUNT(DISTINCT c.id) AS total FROM clients c WHERE $sqlFilter";
        $countRes = Database::executeQuery($sqlCount, $params);
        $total = (int)($countRes->total ?? 0);
        $pagination->setTotalElements($total);

        $sql = "SELECT c.id AS client_id, c.id, c.nom AS client_nom, c.nom, c.prenom AS client_prenom, c.prenom, c.telephone AS client_telephone, c.telephone, c.email AS client_email, c.email, c.limite_credit AS client_limite, c.limite_credit
                FROM clients c
                WHERE $sqlFilter
                ORDER BY c.nom ASC
                LIMIT $limit OFFSET $offset";

        $results = Database::executeQuery($sql, $params, false);

        if (!empty($results)) {
            return array_map(fn($row) => Client::toEntity($row), $results);
        }
        return [];
    }

    public static function insert(Client $client): int
    {
        $sql = "INSERT INTO clients (nom, prenom, telephone, email, limite_credit, credit)
                VALUES(:nom, :prenom, :telephone, :email, :limite_credit, 0) RETURNING id";

        $res = Database::executeQuery($sql, [
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'telephone' => $client->getTelephone(),
            'email' => !empty($client->getEmail()) ? $client->getEmail() : null,
            'limite_credit' => $client->getLimiteCredit()
        ], true);

        $id = (int)($res->id ?? 0);
        $client->setId($id);
        return $id;
    }

    public static function selectById(int $id): ?Client
    {
        $sql = "SELECT id AS client_id, id, nom AS client_nom, nom, prenom AS client_prenom, prenom, telephone AS client_telephone, telephone, email AS client_email, email, limite_credit AS client_limite, limite_credit
                FROM clients WHERE id = :id";

        $obj = Database::executeQuery($sql, ['id' => $id], true);
        return $obj ? Client::toEntity($obj) : null;
    }

    public static function selectAll(): array
    {
        $sql = "SELECT id AS client_id, id, nom AS client_nom, nom, prenom AS client_prenom, prenom, telephone AS client_telephone, telephone, email AS client_email, email, limite_credit AS client_limite, limite_credit
                FROM clients ORDER BY nom ASC";

        $results = Database::query($sql, false);
        if (!empty($results)) {
            return array_map(fn($row) => Client::toEntity($row), $results);
        }
        return [];
    }



    public static function getColonneClient(int $clientId, string $colonne): mixed
    {
        $sql = $colonne === 'credit'
            ? "SELECT COALESCE(SUM(montant_restant), 0) AS total_dette FROM dettes WHERE client_id = :id AND montant_restant > 0"
            : "SELECT $colonne FROM clients WHERE id = :id";

        $result = Database::executeQuery($sql, ['id' => $clientId], true);

        return $colonne === 'credit' ? (float)$result->total_dette : $result->$colonne;
    }
}
