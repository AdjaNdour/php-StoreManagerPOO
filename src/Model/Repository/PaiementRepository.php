<?php

namespace App\Model\Repository;

use App\Core\Database;
use Exception;
use Throwable;

class PaiementRepository
{
    public static function enregistrerPaiement(int $detteId, float $montant, int $modePaiementId, ?int $utilisateurId = null, ?string $notes = null): bool
    {
        $pdo = Database::getInstance();
        $startedTx = false;
        if (!$pdo->inTransaction()) {
            $pdo->beginTransaction();
            $startedTx = true;
        }

        try {
            $sqlDette = "SELECT d.*, v.id AS vente_id, v.montant_total, v.montant_verse AS vente_montant_verse
                         FROM dettes d
                         JOIN ventes v ON v.id = d.vente_id
                         WHERE d.id = :id FOR UPDATE";

            $detteRow = Database::executeQuery($sqlDette, ['id' => $detteId], true);
            if (!$detteRow) {
                throw new Exception("Dette introuvable.");
            }

            $montantRestantActuel = (float)$detteRow->montant_restant;
            if ($montantRestantActuel <= 0) {
                throw new Exception("Cette dette est déjà intégralement soldée.");
            }

            if ($montant > $montantRestantActuel) {
                throw new Exception("Le montant versé ($montant FCFA) est supérieur au montant restant ($montantRestantActuel FCFA).");
            }

            $sqlInsertPaiement = "INSERT INTO paiements (montant, notes, date_paiement, dette_id, mode_paiement_id, utilisateur_id)
                                  VALUES (:montant, :notes, CURRENT_DATE, :dette_id, :mode_paiement_id, :utilisateur_id)";

            Database::executeUpdate($sqlInsertPaiement, [
                'montant' => $montant,
                'notes' => $notes ?: 'Règlement dette #' . $detteRow->ref,
                'dette_id' => $detteId,
                'mode_paiement_id' => $modePaiementId,
                'utilisateur_id' => $utilisateurId ?? 2
            ]);

            $nouveauMontantVerse = (float)$detteRow->montant_verse + $montant;
            $nouveauMontantRestant = max(0.0, (float)$detteRow->montant_initial - $nouveauMontantVerse);
            $nouveauStatutDetteId = ($nouveauMontantRestant <= 0.0) ? 2 : 1;

            $sqlUpdateDette = "UPDATE dettes 
                               SET montant_verse = :verse, 
                                   montant_restant = :restant, 
                                   statut_dette_id = :statut
                               WHERE id = :id";

            Database::executeUpdate($sqlUpdateDette, [
                'verse' => $nouveauMontantVerse,
                'restant' => $nouveauMontantRestant,
                'statut' => $nouveauStatutDetteId,
                'id' => $detteId
            ]);

            $venteId = (int)$detteRow->vente_id;
            $nouveauVerseVente = (float)$detteRow->vente_montant_verse + $montant;
            $nouveauStatutVente = ($nouveauMontantRestant <= 0.0) ? 'PAYEE' : 'AVANCE';

            $sqlUpdateVente = "UPDATE ventes 
                               SET montant_verse = :verse, 
                                   statut = :statut
                               WHERE id = :id";

            Database::executeUpdate($sqlUpdateVente, [
                'verse' => $nouveauVerseVente,
                'statut' => $nouveauStatutVente,
                'id' => $venteId
            ]);

            if ($startedTx && $pdo->inTransaction()) {
                $pdo->commit();
            }

            return true;
        } catch (Throwable $e) {
            if ($startedTx && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
