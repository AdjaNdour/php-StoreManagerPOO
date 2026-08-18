<?php

require_once dirname(__DIR__) . "/Entity/Vente.php";

class VenteRepository
{

    public static function insert(Vente $vente): int
    {
        try {
            $pdo = Database::connexionDB();

            $pdo->beginTransaction();
            $sqlVente = "INSERT INTO ventes(numero_facture,montant_total,montant_verse,statut,date_vente,date_echeance,client_id,utilisateur_id,mode_paiement_id) 
                         VALUES(:numero_facture,:montant_total,:montant_verse,:statut,:date_vente,:date_echeance,:client_id,:utilisateur_id,:mode_paiement_id)";

            Database::executeUpdate($pdo, $sqlVente, [
                "numero_facture" => $vente->getNumeroFacture(),
                "montant_total" => $vente->getMontantTotal(),
                "montant_verse" => $vente->getMontantVerse(),
                "statut" => $vente->getStatut(),
                "date_vente" => $vente->getDateVente(),
                "date_echeance" => $vente->getDateEcheance(),
                "client_id" => $vente->getClientId(),
                "utilisateur_id" => $vente->getUtilisateurId(),
                "mode_paiement_id" => $vente->getModePaiementId()
            ]);

            $venteId = (int)$pdo->lastInsertId();
            if ($vente->getMontantVerse() < $vente->getMontantTotal()) {

                $montantRestant = $vente->getMontantTotal() - $vente->getMontantVerse();

                $sqlDette = "INSERT INTO dettes (ref,montant_initial,montant_verse,montant_restant,date_dette,date_echeance,vente_id,client_id,statut_dette_id)
                VALUES (:ref,:montant_initial,:montant_verse,:montant_restant,:date_dette,:date_echeance,:vente_id,:client_id,:statut_dette_id)";

                $res = Database::executeUpdate(
                    $pdo,
                    $sqlDette,
                    [
                        'ref' => 'DT-' . $venteId,
                        'montant_initial' => $vente->getMontantTotal(),
                        'montant_verse' => $vente->getMontantVerse(),
                        'montant_restant' => $montantRestant,
                        'date_dette' => $vente->getDateVente(),
                        'date_echeance' => $vente->getDateEcheance(),
                        'vente_id' => $venteId,
                        'client_id' => $vente->getClientId(),
                        'statut_dette_id' => 1
                    ]
                );
                if ($res === 0) {
                    throw new Exception(
                        "dette nom inserer"
                    );
                }
            }
            $sqlLigneVente = "INSERT INTO lignes_vente (vente_id, produit_id, quantite, prix_unitaire)
                              VALUES (:vente_id, :produit_id, :quantite, :prix_unitaire)";

            $sqlProduitUpdate = "UPDATE produits SET stock_initial = stock_initial - :quantite
                                 WHERE id = :id AND stock_initial >= :quantite";

            $tableauLigne = $vente->getLignes();
            foreach ($tableauLigne as $ligne) {
                Database::executeUpdate(
                    $pdo,
                    $sqlLigneVente,
                    [
                        'vente_id' => $venteId,
                        'produit_id' => $ligne->getProduitId(),
                        'quantite' => $ligne->getQuantite(),
                        'prix_unitaire' => $ligne->getPrixUnitaire()
                    ]
                );
                $res = Database::executeUpdate(
                    $pdo,
                    $sqlProduitUpdate,
                    [
                        'quantite' => $ligne->getQuantite(),
                        'id' => $ligne->getProduitId()
                    ]
                );
                if ($res === 0) {
                    throw new Exception(
                        "Stock insuffisant pour le produit " . $ligne->getProduitId()
                    );
                }
            }
            $pdo->commit();

            return $venteId;
        } catch (Exception $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function selectAllVente()
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT  v.id AS vente_id, v.numero_facture, v.date_vente, v.statut, v.montant_total,
                        v.montant_verse, v.date_echeance,v.utilisateur_id,v.mode_paiement_id,mp.nom AS mode_paiement_nom,
                        c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone,
                        p.id AS produit_id,p.libelle AS produit_libelle,
                        lv.quantite,lv.prix_unitaire,(lv.quantite * lv.prix_unitaire) AS sous_total
                FROM ventes v
                JOIN clients c ON c.id = v.client_id
                JOIN lignes_vente lv ON lv.vente_id = v.id
                JOIN produits p ON p.id = lv.produit_id
                LEFT JOIN modes_paiement mp ON mp.id=v.mode_paiement_id
                ORDER BY v.id";

        $tableauVentes = Database::query($pdo, $sql, false);

        $ventes = [];
        if (empty($tableauVentes)) return $ventes;
        $venteIdRepere = 0;
        foreach ($tableauVentes as $vente) {

            $venteId = (int) $vente['vente_id'];

            if ($venteId !== $venteIdRepere) {
                $ventes[$venteId] = self::toObjet($vente);
                $venteIdRepere = $venteId;
            }

            $ligneVente = new LigneVente(
                (int) $vente['produit_id'],
                (int) $vente['quantite'],
                (float) $vente['prix_unitaire'],
                $venteId
            );
            $ventes[$venteId]->ajouterLigne($ligneVente);
        }

        return $ventes;
    }

    private function toObjet(array $data): Vente
    {
        $vente = new Vente(
            $data['numero_facture'],
            (float) $data['montant_total'],
            (float) ($data['montant_verse'] ?? 0),
            $data['statut'],
            $data['date_echeance'] ?? null,
            (int) $data['client_id'],
            (int) ($data['utilisateur_id'] ?? 0),
            (int) $data['vente_id'],
            $data['date_vente']
        );

        $client = new Client(
            $data['client_nom'],
            $data['client_prenom'],
            $data['client_telephone'],
            null,
            0,
            (int) $data['client_id']
        );
        $vente->setClient($client);
        $vente->setModePaiementId((int)$data['mode_paiement_id']);
        $vente->setLignes([]);
        return $vente;
    }

    public static function selectStatistiques()
    {
        $pdo = Database::connexionDB();

        $sql = "SELECT  COUNT(*) AS nbr_ventes,
                        COALESCE(SUM(montant_total), 0) AS montant_total,
                        COALESCE(SUM(montant_verse), 0) AS montant_encaisse
                    FROM ventes";
        return Database::query($pdo, $sql);
    }
}
