<?php

namespace App\Model\Repository;

use App\Core\Database;
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

        $where = ["1=1"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(c.nom ILIKE :search OR c.prenom ILIKE :search OR c.telephone ILIKE :search OR c.email ILIKE :search)";
            $params['search'] = "%$search%";
        }

        $whereClause = implode(" AND ", $where);

        $sqlCount = "SELECT COUNT(DISTINCT c.id) AS total FROM clients c WHERE $whereClause";
        $countRes = Database::executeQuery($sqlCount, $params, true);
        $total = (int)($countRes->total ?? 0);
        $pagination->setTotalElements($total);

        $sql = "SELECT c.id AS client_id, c.id, c.nom AS client_nom, c.nom, c.prenom AS client_prenom, c.prenom, c.telephone AS client_telephone, c.telephone, c.email AS client_email, c.email, c.limite_credit AS client_limite, c.limite_credit
                FROM clients c
                WHERE $whereClause
                ORDER BY c.nom ASC
                LIMIT $limit OFFSET $offset";

        $results = Database::executeQuery($sql, $params, false);
        return (!empty($results) && is_array($results)) ? array_map(fn($row) => Client::toEntity($row), $results) : [];
    }

    public static function insert(Client $client): int
    {
        $sql = "INSERT INTO clients (nom, prenom, telephone, email, limite_credit)
                VALUES(:nom, :prenom, :telephone, :email, :limite_credit) RETURNING id";

        $res = Database::executeQuery($sql, [
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'telephone' => $client->getTelephone(),
            'email' => $client->getEmail(),
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

    public static function selectClientById(int $id): ?Client
    {
        return self::selectById($id);
    }

    public static function selectClientByTelephone(string $telephone): ?Client
    {
        $sql = "SELECT id AS client_id, id, nom AS client_nom, nom, prenom AS client_prenom, prenom, telephone AS client_telephone, telephone, email AS client_email, email, limite_credit AS client_limite, limite_credit
                FROM clients WHERE telephone = :telephone LIMIT 1";

        $obj = Database::executeQuery($sql, ['telephone' => $telephone], true);
        return $obj ? Client::toEntity($obj) : null;
    }

    public static function selectAll(): array
    {
        $sql = "SELECT id AS client_id, id, nom AS client_nom, nom, prenom AS client_prenom, prenom, telephone AS client_telephone, telephone, email AS client_email, email, limite_credit AS client_limite, limite_credit
                FROM clients ORDER BY nom ASC";

        $results = Database::query($sql, false);
        return (!empty($results) && is_array($results)) ? array_map(fn($row) => Client::toEntity($row), $results) : [];
    }

    public static function update(Client $client): bool
    {
        $sql = "UPDATE clients
                SET nom = :nom, prenom = :prenom, telephone = :telephone, email = :email, limite_credit = :limite_credit
                WHERE id = :id";

        $affected = Database::executeUpdate($sql, [
            'id' => $client->getId(),
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'telephone' => $client->getTelephone(),
            'email' => $client->getEmail(),
            'limite_credit' => $client->getLimiteCredit()
        ]);

        return $affected > 0;
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM clients WHERE id = :id";
        $affected = Database::executeUpdate($sql, ['id' => $id]);
        return $affected > 0;
    }

    public static function getColonneClient(int $clientId, string $colonne): mixed
    {
        if ($colonne === 'credit') {
            $sqlCredit = "SELECT COALESCE(SUM(montant_restant), 0) AS total_dette FROM dettes WHERE client_id = :id AND montant_restant > 0";
            $res = Database::executeQuery($sqlCredit, ['id' => $clientId], true);
            return (float)($res->total_dette ?? 0);
        }

        $validCols = ['id', 'nom', 'prenom', 'telephone', 'email', 'limite_credit'];
        if (!in_array($colonne, $validCols)) {
            throw new Exception("Colonne invalide.");
        }

        $sql = "SELECT $colonne FROM clients WHERE id = :id";
        $resultat = Database::executeQuery($sql, ['id' => $clientId], true);

        if (!$resultat) {
            throw new Exception("Client introuvable.");
        }

        return $resultat->$colonne ?? null;
    }
}
