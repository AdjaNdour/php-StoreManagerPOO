<?php

namespace App\Controller;

use Adja\Core\Controller;
use Adja\Core\SessionManager;
use Adja\Core\Request;
use Adja\Core\Validator;
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
        $viewsPath = PATHBASE . '/src/Views' ;

        $page = (int)Request::get('page', 1);
        $search = (string)Request::get('search', '');
        $statut = (string)Request::get('statut', 0);
        $clientId = (int)Request::get('client_id', 0);

        $filtered = new FilteredModel(['search' => $search,'statut' => $statut,'client_id' => $clientId]);

        $pagination = new PaginationModel(page: $page);

        $dettes = DetteService::getAllFiltered($filtered, $pagination);
        $modesPaiement = ModePaiementService::getAll();
        $statistiques = DetteService::getStatistiques();

        $errors = SessionManager::getData('errors') ?? [];
        if (SessionManager::hasKey('errors')) {
            SessionManager::removeData('errors');
        }

        Controller::renderViewLayout("dettes", "base", [
            'dettes' => $dettes,
            'modesPaiement' => $modesPaiement,
            'statistiques' => $statistiques,
            'errors' => $errors,
            'pagination' => $pagination,
            'filteredTableau' => $filtered,
            'currentPage' => 'dettes'
        ], $viewsPath);
    }

    public static function enregistrerRemboursementDette(): void
    {

        if (Request::isPost()) {
            $detteId = (int)Request::post('dette_id', 0);
            $montant = (float)Request::post('montant_verse', 0) ;
            $modePaiementId = (int)Request::post('mode_paiement', 0) ;
            $notes = (string)Request::post('notes', '');

            $errors = [];

            Validator::isGreaterThanZero($modePaiementId, 'mode_paiement', $errors, "Mode de paiement manquant.");
            Validator::isGreaterThanZero($detteId, 'dette_id', $errors, "Identifiant dette manquant.");
            Validator::isGreaterThanZero($montant, 'montant_verse', $errors, "Le montant doit être supérieur à zéro.");

            if (!Validator::hasErrors($errors)) {
                try {
                    $user = SessionManager::getData(KEY_USERCONNECT);
                    $userId = ($user instanceof Utilisateur) ? $user->getId() : 1;

                    DetteService::rembourserDette($detteId, $montant, $modePaiementId, $userId, $notes);
                } catch (Exception $e) {
                    SessionManager::setData('error', $e->getMessage());
                }
            } else {
                SessionManager::setData('errors', $errors);
            }
        }

        Controller::redirectToRoute("dettes", WEB_ROUTE);
    }


}
