<?php

require_once dirname(__DIR__) . "/Entity/Fournisseur.php";

class FournisseurRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connexionDB();
    }

    public function insert(Fournisseur $fournisseur): int
    {
        $sql = "INSERT INTO fournisseurs (nom, email, telephone, adresse)
                VALUES (:nom, :email, :telephone, :adresse)";

        Database::executeUpdate($this->pdo, $sql, [
            'nom' => $fournisseur->getNom(),
            'email' => $fournisseur->getEmail(),
            'telephone' => $fournisseur->getTelephone(),
            'adresse' => $fournisseur->getAdresse()
        ]);

        $id = (int) $this->pdo->lastInsertId();

        $fournisseur->setId($id);

        return $id;
    }

    public function selectById(int $id): ?Fournisseur
    {
        $sql = "SELECT * FROM fournisseurs WHERE id = :id";
       
        $fournisseur = Database::executeQuery($this->pdo, $sql, ['id' => $id]);
        
        if (!$fournisseur) return null;
        
        return $this->toObjet($fournisseur);
    }

    public function selectAll(): array
    {
        $sql = "SELECT * FROM fournisseurs ORDER BY nom ASC";

        $tableauFournisseurs = Database::query($this->pdo, $sql, false);
        
        $fournisseurs = [];
        if (empty($tableauFournisseurs)) return $fournisseurs;
        
        foreach ($tableauFournisseurs as $fournisseur) {
            $fournisseurs[] = $this->toObjet($fournisseur);
        }
        return $fournisseurs;
    }

    public function update(Fournisseur $fournisseur): bool
    {
        $sql = "UPDATE fournisseurs SET nom = :nom, email = :email, telephone = :telephone, adresse = :adresse 
                WHERE id = :id";

        $nbrRowsAffecte = Database::executeUpdate( $this->pdo, $sql,
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

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM fournisseurs
                WHERE id = :id";

        $nbrRowsAffecte = Database::executeUpdate($this->pdo, $sql, ['id' => $id]);

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