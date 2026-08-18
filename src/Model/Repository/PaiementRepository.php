<?php

require_once dirname(__DIR__) . "/Entity/Paiement.php";

class PaiementRepository
{

    public static function enregistrerPaiement(int $detteId, float $montant, int $modePaiementId, ?int $utilisateurId = null, ?string $notes = null): bool
    {
        $pdo = Database::connexionDB();

        try {
            $pdo->beginTransaction();

            $sqlDette = "SELECT * FROM dettes WHERE id = :id ";
            $dette = Database::executeQuery($pdo, $sqlDette, ['id' => $detteId]);

            if (!$dette) {
                throw new Exception("Dette introuvable.");
            }

            $montantRestantActuel = (float) $dette['montant_restant'];
            if ($montant > $montantRestantActuel) {
                throw new Exception("Le montant du versement dépasse le reste dû (" . $montantRestantActuel . " FCFA).");
            }

            $sqlPaiement = "INSERT INTO paiements (montant, notes, date_paiement, dette_id, mode_paiement_id, utilisateur_id)
                            VALUES (:montant, :notes, CURRENT_DATE, :dette_id, :mode_paiement_id, :utilisateur_id)";

            Database::executeUpdate($pdo, $sqlPaiement, [
                'montant' => $montant,
                'notes' => $notes ?? 'Règlement de dette #' . $dette['ref'],
                'dette_id' => $detteId,
                'mode_paiement_id' => $modePaiementId,
                'utilisateur_id' => $utilisateurId
            ]);

            $nouveauMontantVerse = (float)$dette['montant_verse'] + $montant;
            $nouveauMontantRestant = (float)$dette['montant_restant'] - $montant;
            $nouveauStatutId = ($nouveauMontantRestant <= 0.0) ? 2 : 1;

            $sqlUpdateDette = "UPDATE dettes SET montant_verse = :montant_verse,
                                   montant_restant = :montant_restant,
                                   statut_dette_id = :statut_dette_id
                               WHERE id = :id";

            Database::executeUpdate($pdo, $sqlUpdateDette, [
                'montant_verse' => $nouveauMontantVerse,
                'montant_restant' => $nouveauMontantRestant,
                'statut_dette_id' => $nouveauStatutId,
                'id' => $detteId
            ]);

            if ($nouveauMontantRestant <= 0.0) {
                $sqlUpdateVente = "UPDATE ventes SET statut = 'PAYEE' WHERE id = :vente_id";
                Database::executeUpdate($pdo, $sqlUpdateVente, ['vente_id' => $dette['vente_id']]);
            } else {
                $sqlUpdateVente = "UPDATE ventes SET statut = 'AVANCE' WHERE id = :vente_id";
                Database::executeUpdate($pdo, $sqlUpdateVente, ['vente_id' => $dette['vente_id']]);
            }

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
