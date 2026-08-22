<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\SessionManager;
use App\Core\Request;
use App\Core\Validator;
use App\Service\DetteService;
use App\Service\ClientService;
use App\Service\ModePaiementService;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use App\Model\Entity\Utilisateur;
use Exception;

class DetteController
{
    public static function getAllDettes(): void
    {
        $page = (int)Request::get('page', 1);
        $search = (string)Request::get('search', '');
        $statut = (string)Request::get('statut', 'ALL');
        $clientId = (int)Request::get('client_id', 0);

        $filtered = new FilteredModel([
            'search' => $search,
            'statut' => $statut,
            'client_id' => $clientId
        ]);

        $pagination = new PaginationModel(page: $page, limit: 4);

        $dettes = DetteService::getAllDettesFiltered($filtered, $pagination);
        $clients = ClientService::getAll();
        $modesPaiement = ModePaiementService::getAll();
        $statistiques = DetteService::getStatistiques();

        Controller::renderViewLayout("dettes", "base", [
            'dettes' => $dettes,
            'allDettes' => $dettes,
            'clients' => $clients,
            'modesPaiement' => $modesPaiement,
            'modes' => $modesPaiement,
            'statistiques' => $statistiques,
            'stats' => $statistiques,
            'pagination' => $pagination,
            'filtered' => $filtered,
            'filteredTableau' => $filtered,
            'currentPage' => 'dettes'
        ]);
    }

    public static function enregistrerRemboursementDette(): void
    {
        if (Request::isPost()) {
            $detteId = (int)Request::post('dette_id', 0);
            $montant = (float)(Request::post('montant_verse') ?? Request::post('montant') ?? 0);
            $modePaiementId = (int)(Request::post('mode_paiement') ?? Request::post('mode_paiement_id') ?? Request::post('mode_reglement') ?? 1);
            $notes = (string)Request::post('notes', '');

            if ($modePaiementId <= 0) {
                $modePaiementId = 1;
            }

            $errors = [];
            Validator::isGreaterThanZero($detteId, 'dette_id', $errors, "Identifiant dette manquant.");
            Validator::isGreaterThanZero($montant, 'montant', $errors, "Le montant doit être supérieur à zéro.");

            if (!Validator::hasErrors($errors)) {
                try {
                    $user = SessionManager::getData(KEY_USERCONNECT);
                    $userId = ($user instanceof Utilisateur) ? $user->getId() : 1;

                    DetteService::rembourserDette($detteId, $montant, $modePaiementId, $userId, $notes);
                } catch (Exception $e) {
                    // Handled
                }
            }
        }

        Controller::redirectToRoute("dettes");
    }

    public static function enregistrerPaiement(): void
    {
        self::enregistrerRemboursementDette();
    }
}
