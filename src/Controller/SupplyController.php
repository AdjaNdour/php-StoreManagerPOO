<?php

namespace App\Controller;

use Adja\Core\Controller;
use Adja\Core\SessionManager;
use Adja\Core\Request;
use Adja\Core\Validator;
use App\Service\SupplyService;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;

class SupplyController
{
    public static function index(): void
    {
        $viewsPath = PATHBASE . '/src/Views';

        $page = (int)Request::get('page', 1);
        $search = (string)Request::get('search', '');
        $statut = (string)Request::get('statut', 0);

        $filtered = new FilteredModel(['search' => $search, 'statut' => $statut]);
        $pagination = new PaginationModel(page: $page);

        $appros = SupplyService::getAllFiltered($filtered, $pagination);
        $statistiques = SupplyService::getStatistiques();

        Controller::renderViewLayout("appros", "base", [
            'appros' => $appros,
            'statistiques' => $statistiques,
            'pagination' => $pagination,
            'filteredTableau' => $filtered,
            'currentPage' => 'appros'
        ], $viewsPath);
    }

    public static function receiveAppro(): void
    {
        if (Request::isPost()) {
            $approId = (int) Request::post('approvisionnement_id') ;
            $quantitesDemandees = Request::post('quantites_demandees') ?? [];

            $errors = [];

            if (!Validator::hasErrors($errors)) {
                try {
                    SupplyService::receptionnerBL($approId, $quantitesDemandees);
                } catch (Exception $e) {
                    SessionManager::setData('error', $e->getMessage());
                }
            }
        }

        Controller::redirectToRoute("appros", WEB_ROUTE);
    }
}
