<?php

namespace App\Model\Repository;

use Adja\Core\Database;
use App\Model\Entity\Paiement;
use Exception;
use Throwable;

class PaiementRepository
{

    public static function insertPaiement(int $detteId, float $montant, int $modePaiementId, ?int $utilisateurId = null, ?string $notes = null): bool
    {
        $pdo = Database::getInstance();
        $pdo->beginTransaction();

        try {
            $sqlDette = "SELECT d.*, v.id AS vente_id, v.montant_total, v.montant_verse AS vente_montant_verse
                         FROM dettes d
                         JOIN ventes v ON v.id = d.vente_id
                         WHERE d.id = :id FOR UPDATE";

            $detteSelectionne = Database::executeQuery($sqlDette, ['id' => $detteId], true);

            if (!$detteSelectionne) {
                throw new Exception("Dette introuvable.");
            }

            $montantRestantActuel = (float)$detteSelectionne->montant_restant;//a demander

         
            if ($montant > $montantRestantActuel) {
                throw new Exception("Le montant versé ($montant FCFA) est supérieur au montant restant ($montantRestantActuel FCFA).");
            }

            $sqlInsertPaiement = "INSERT INTO paiements (montant, notes, date_paiement, dette_id, mode_paiement_id, utilisateur_id)
                                  VALUES (:montant, :notes, CURRENT_DATE, :dette_id, :mode_paiement_id, :utilisateur_id)";

            Database::executeUpdate($sqlInsertPaiement, [
                'montant' => $montant,
                'notes' => $notes ?: 'Règlement dette #' . $detteSelectionne->ref,
                'dette_id' => $detteId,
                'mode_paiement_id' => $modePaiementId,
                'utilisateur_id' => $utilisateurId
            ]);

            $nouveauMontantVerse = (float)$detteSelectionne->montant_verse + $montant;

            $nouveauMontantRestant = max(0.0, (float)$detteSelectionne->montant_initial - $nouveauMontantVerse);

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

            $venteId = (int)$detteSelectionne->vente_id;
            $nouveauVerseVente = (float)$detteSelectionne->vente_montant_verse + $montant;
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

            $pdo->commit();

            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
