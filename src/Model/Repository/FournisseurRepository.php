<?php

require_once dirname(__DIR__) . "/Entity/Fournisseur.php";

class FournisseurRepository
{

    public static function insert(Fournisseur $fournisseur): int
    {
        $pdo = Database::connexionDB();

        $sql = "INSERT INTO fournisseurs (nom, email, telephone, adresse)
                VALUES (:nom, :email, :telephone, :adresse)";

        Database::executeUpdate($pdo, $sql, [
            'nom' => $fournisseur->getNom(),
            'email' => $fournisseur->getEmail(),
            'telephone' => $fournisseur->getTelephone(),
            'adresse' => $fournisseur->getAdresse()
        ]);

        $id = (int) $pdo->lastInsertId();

        $fournisseur->setId($id);

        return $id;
    }

    public static function selectById(int $id): ?Fournisseur
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT * FROM fournisseurs WHERE id = :id";

        $fournisseur = Database::executeQuery($pdo, $sql, ['id' => $id]);

        if (!$fournisseur) return null;

        return self::toObjet($fournisseur);
    }

    public static function selectAll(): array
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT * FROM fournisseurs ORDER BY nom ASC";

        $tableauFournisseurs = Database::query($pdo, $sql, false);

        $fournisseurs = [];
        if (empty($tableauFournisseurs)) return $fournisseurs;

        foreach ($tableauFournisseurs as $fournisseur) {
            $fournisseurs[] = self::toObjet($fournisseur);
        }
        return $fournisseurs;
    }

    public static function update(Fournisseur $fournisseur): bool
    {
        $pdo = Database::connexionDB();

        $sql = "UPDATE fournisseurs SET nom = :nom, email = :email, telephone = :telephone, adresse = :adresse 
                WHERE id = :id";

        $nbrRowsAffecte = Database::executeUpdate(
            $pdo,
            $sql,
            [
                'id' => $fournisseur->getId(),
                'nom' => $fournisseur->getNom(),
                'email' => $fournisseur->getEmail(),
                'telephone' => $fournisseur->getTelephone(),
                'adresse' => $fournisseur->getAdresse()
            ]
        );
        return $nbrRowsAffecte > 0 ? true : false;
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::connexionDB();

        $sql = "DELETE FROM fournisseurs
                WHERE id = :id";

        $nbrRowsAffecte = Database::executeUpdate($pdo, $sql, ['id' => $id]);

        return $nbrRowsAffecte > 0 ? true : false;
    }

    private function toObjet(array $fournisseur): Fournisseur
    {
        return new Fournisseur(
            $fournisseur['nom'],
            $fournisseur['email'],
            $fournisseur['telephone'],
            $fournisseur['adresse'],
            (int) $fournisseur['id']
        );
    }
}
