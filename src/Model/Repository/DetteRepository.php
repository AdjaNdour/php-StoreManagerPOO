<?php

require_once dirname(__DIR__) . "/Entity/Dette.php";
require_once dirname(__DIR__) . "/Entity/Paiement.php";
require_once dirname(__DIR__) . "/Entity/ModePaiement.php";
require_once dirname(__DIR__) . "/Entity/LigneVente.php";
require_once dirname(__DIR__) . "/Entity/Client.php";

class DetteRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connexionDB();
    }

    public function selectById(int $id): ?Dette
    {
        $sql = "SELECT d.*, 
                       c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, c.email AS client_email, c.limite_credit AS client_limite,
                       sd.nom AS statut_nom,
                       v.numero_facture, v.statut AS vente_statut
                FROM dettes d
                JOIN clients c ON c.id = d.client_id
                JOIN statuts_dette sd ON sd.id = d.statut_dette_id
                JOIN ventes v ON v.id = d.vente_id
                WHERE d.id = :id";

        $dept = Database::executeQuery($this->pdo, $sql, ['id' => $id]);
        if (!$dept) return null;

        $dette = $this->toObjet($dept);
        $dette->setPaiements($this->selectPaiementsByDetteId($id));
        return $dette;
    }


    public function selectAll(): array
    {
        $sql = "SELECT d.*,
                       c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, c.email AS client_email, c.limite_credit AS client_limite,
                       sd.nom AS statut_nom,
                       v.numero_facture, v.statut AS vente_statut
                FROM dettes d
                JOIN clients c ON c.id = d.client_id
                JOIN statuts_dette sd ON sd.id = d.statut_dette_id
                JOIN ventes v ON v.id = d.vente_id
                ORDER BY d.id DESC";

        $tableauDettes = Database::query($this->pdo, $sql, false);
        $dettes = [];
        if (empty($tableauDettes)) return $dettes;

        foreach ($tableauDettes as $dept) {
            $dette = $this->toObjet($dept);
            $dette->setPaiements($this->selectPaiementsByDetteId((int)$dept['id']));
            $dettes[] = $dette;
        }

        return $dettes;
    }

    public function selectPaiementsByDetteId(int $detteId): array
    {
        $sql = "SELECT p.*, mp.nom AS mode_nom
                FROM paiements p
                JOIN modes_paiement mp ON mp.id = p.mode_paiement_id
                WHERE p.dette_id = :dette_id
                ORDER BY p.id ASC";

        $tableauPaiements = Database::executeQuery($this->pdo, $sql, ['dette_id' => $detteId], false);
        $paiements = [];
        if (empty($tableauPaiements)) return $paiements;

        foreach ($tableauPaiements as $paiement) {
            $p = new Paiement(
                (int)$paiement['dette_id'],
                (int)$paiement['mode_paiement_id'],
                (float)$paiement['montant'],
                isset($paiement['utilisateur_id']) ? (int)$paiement['utilisateur_id'] : null,
                $paiement['notes'] ?? null,
                (int)$paiement['id'],
                $paiement['date_paiement']
            );
            $mode = new ModePaiement($paiement['mode_nom'], (int)$paiement['mode_paiement_id']);
            $p->setModePaiement($mode);
            $paiements[] = $p;
        }

        return $paiements;
    }

    public function selectProduitsByDetteId(int $detteId): array
    {
        $sql = "SELECT p.*,lv.quantite as quantity,lv.prix_unitaire,(lv.quantite*lv.prix_unitaire) AS sous_total
                    FROM dettes d
                    INNER JOIN ventes v ON v.id=d.vente_id
                    INNER JOIN lignes_vente lv ON lv.vente_id=v.id
                    INNER JOIN produits p ON p.id=lv.produit_id
                WHERE d.id=:dette_id";

        $tableauProduits = Database::executeQuery($this->pdo, $sql, ['dette_id' => $detteId], false);
        $produits = [];
        if (empty($tableauProduits)) return $produits;

        foreach ($tableauProduits as $prod) {
            $p = new Produit(
                $prod['code'],
                $prod['libelle'],
                $prod['categorie'],
                $prod['prix_vente'],
                $prod['sous_total'],
                $prod['quantity'],
                $prod['seuil_alerte'],
                null,
                $prod["id"]
            );
            $produits[] = $p;
        }
        return $produits;
    }

    public function selectActiveDettes(): array
    {
        $sql = "SELECT d.*, p.libelle,
                    c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, c.email AS client_email, c.limite_credit AS client_limite,
                       sd.nom AS statut_nom,
                       v.numero_facture, v.statut AS vente_statut
                FROM dettes d
                INNER JOIN clients c ON c.id = d.client_id
                INNER JOIN statuts_dette sd ON sd.id = d.statut_dette_id
                INNER JOIN ventes v ON v.id = d.vente_id
                INNER JOIN lignes_vente lv ON lv.vente_id = v.id
                INNER JOIN produits p ON lv.produit_id = p.id
                WHERE d.montant_restant > 0
                ORDER BY d.id DESc";

        $tableauDettesActives = Database::query($this->pdo, $sql, false);
        $dettes = [];
        if (empty($tableauDettesActives)) return $dettes;

        foreach ($tableauDettesActives as $deptActive) {
            $dette = $this->toObjet($deptActive);
            $dette->setPaiements($this->selectPaiementsByDetteId((int)$deptActive['id']));
            $dettes[] = $dette;
        }

        return $dettes;
    }

    public function selectStatistiques(): array
    {
        $sql = "SELECT 
                    COALESCE(SUM(montant_restant), 0) AS somme_Montant_Restant_Dettes,
                    COUNT(DISTINCT client_id) AS nbr_Clients_Dettes,
                    COALESCE(SUM(montant_verse), 0) AS somme_Montant_Verser_Dettes
                FROM dettes
                WHERE montant_restant > 0";

        return Database::query($this->pdo, $sql);
    }

    private function toObjet(array $data): Dette
    {
        $dette = new Dette(
            $data['ref'],
            (int) $data['vente_id'],
            (int) $data['client_id'],
            (int) $data['statut_dette_id'],
            (float) $data['montant_initial'],
            (float) ($data['montant_verse'] ?? 0),
            (float) ($data['montant_restant'] ?? 0),
            $data['date_echeance'] ?? null,
            (int) $data['id'],
            $data['date_dette'] ?? null
        );

        if (isset($data['client_nom'])) {
            $client = new Client(
                $data['client_nom'],
                $data['client_prenom'] ?? '',
                $data['client_telephone'] ?? '',
                $data['client_email'] ?? null,
                (float) ($data['client_limite'] ?? 0),
                (int) $data['client_id']
            );
            $dette->setClient($client);
        }

        if (isset($data['statut_nom'])) {
            $statut = new StatutDette($data['statut_nom'], (int) $data['statut_dette_id']);
            $dette->setStatutDette($statut);
        }

        if (isset($data['numero_facture'])) {
            $vente = new Vente(
                $data['numero_facture'],
                (float) $data['montant_initial'],
                (float) ($data['montant_verse'] ?? 0),
                $data['vente_statut'] ?? 'AVANCE',
                $data['date_echeance'] ?? null,
                (int) $data['client_id'],
                null,
                (int) $data['vente_id'],
                $data['date_dette'] ?? null
            );
            $dette->setVente($vente);
        }

        return $dette;
    }
}
