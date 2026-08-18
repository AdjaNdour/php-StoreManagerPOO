<?php

require_once dirname(__DIR__) . "/Entity/Client.php";

class ClientRepository
{
    public static function insert(Client $client): int
    {
        $pdo = Database::connexionDB();

        $sql = "INSERT INTO clients (nom, prenom, telephone, email, limite_credit)
                VALUES(:nom, :prenom, :telephone, :email, :limite_credit)";

        Database::executeUpdate($pdo, $sql, [
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'telephone' => $client->getTelephone(),
            'email' => $client->getEmail(),
            'limite_credit' => $client->getLimiteCredit()
        ]);

        $id = (int) $pdo->lastInsertId();
        $client->setId($id);
        return $id;
    }

    public static function selectById(int $id): ?Client
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT * FROM clients WHERE id = :id";

        $client = Database::executeQuery($pdo, $sql, ['id' => $id]);

        if (!$client) return null;

        return self::toObjet($client);
    }

    public static function selectAll(): array
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT * FROM clients ORDER BY nom ASC";

        $tableauClients = Database::query($pdo, $sql, false);

        $clients = [];

        if (empty($tableauClients)) return $clients;

        foreach ($tableauClients as $client) {
            $clients[] = self::toObjet($client);
        }

        return $clients;
    }

    public static function update(Client $client): bool
    {
        $pdo = Database::connexionDB();

        $sql = "UPDATE clients
                SET nom = :nom, prenom = :prenom, telephone = :telephone, email = :email, limite_credit = :limite_credit
                WHERE id = :id";

        $nbrRowsAffecte = Database::executeUpdate(
            $pdo,
            $sql,
            [
                'id' => $client->getId(),
                'nom' => $client->getNom(),
                'prenom' => $client->getPrenom(),
                'telephone' => $client->getTelephone(),
                'email' => $client->getEmail(),
                'limite_credit' => $client->getLimiteCredit()
            ]
        );

        return $nbrRowsAffecte > 0 ? true : false;
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connexionDB();

        $sql = "DELETE FROM clients WHERE id = :id";

        $nbrRowsAffecte = Database::executeUpdate($pdo, $sql, ['id' => $id]);

        return $nbrRowsAffecte > 0 ? true : false;
    }

    private function toObjet(array $client): Client
    {
        return new Client(
            $client['nom'],
            $client['prenom'],
            $client['telephone'],
            $client['email'],
            (float) $client['limite_credit'],
            (int) $client['id']
        );
    }

    public static function getColonneClient(int $clientId, string $colonne): mixed
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT $colonne FROM clients WHERE id = :id";

        $resultat = Database::executeQuery($pdo, $sql, ['id' => $clientId]);

        if (!$resultat) {
            throw new Exception("Client introuvable.");
        }

        return $resultat[$colonne];
    }
}
