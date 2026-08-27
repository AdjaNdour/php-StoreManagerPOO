<?php

namespace App\Controller;

use Adja\Core\Controller;
use Adja\Core\SessionManager;
use Adja\Core\Request;
use Adja\Core\Validator;
use App\Service\DashboardService;
use App\Service\SupplyService;
use Exception;

class DashboardController
{
    public static function index(): void
    {
        $viewsPath = defined('PATHBASE') ? PATHBASE . '/src/Views' : 'src/Views';
        $kpis = DashboardService::getKpis();
        $dernieresVentes = DashboardService::getDernieresVentes(5);
        $dettesDuJour = DashboardService::getDettesDuJour();
        $rupturesEtAlertes = DashboardService::getRupturesEtAlertes();
        $livraisonsDuJour = DashboardService::getLivraisonsDuJour();
        $clientsDebiteurs = DashboardService::getClientsDebiteurs();
        $soldeFournisseurs = DashboardService::getSoldeFournisseurs();
        $performanceVendeurs = DashboardService::getPerformanceVendeurs();

        $errors = SessionManager::getData('errors') ?? [];
        if (SessionManager::hasKey('errors')) {
            SessionManager::removeData('errors');
        }

        Controller::renderViewLayout("dashboard", "base", [
            'kpis' => $kpis,
            'dernieresVentes' => $dernieresVentes,
            'dettesDuJour' => $dettesDuJour,
            'rupturesEtAlertes' => $rupturesEtAlertes,
            'livraisonsDuJour' => $livraisonsDuJour,
            'clientsDebiteurs' => $clientsDebiteurs,
            'soldeFournisseurs' => $soldeFournisseurs,
            'performanceVendeurs' => $performanceVendeurs,
            'errors' => $errors,
            'currentPage' => 'dashboard'
        ], $viewsPath);
    }

    public static function quickSupply(): void
    {
        $baseUrl = defined('WEB_ROUTE') ? WEB_ROUTE : '';
        if (Request::isPost()) {
            $produitId = (int)Request::post('produit_id', 0);
            $fournisseurId = (int)Request::post('fournisseur_id', 0);
            $quantite = (int)Request::post('quantite', 10);
            $coutUnitaire = (float)Request::post('cout_achat', 0);

            $errors = [];
            Validator::isGreaterThanZero($produitId, 'produit_id', $errors, "Produit invalide.");
            Validator::isGreaterThanZero($fournisseurId, 'fournisseur_id', $errors, "Fournisseur invalide.");
            Validator::isGreaterThanZero($quantite, 'quantite', $errors, "Quantité invalide.");

            if (!Validator::hasErrors($errors)) {
                try {
                    SupplyService::commandeRapide($produitId, $fournisseurId, $quantite, $coutUnitaire);
                } catch (Exception $e) {
                    SessionManager::setData('error', $e->getMessage());
                }
            } else {
                SessionManager::setData('errors', $errors);
            }
        }

        Controller::redirectToRoute("dashboard", $baseUrl);
    }
}
