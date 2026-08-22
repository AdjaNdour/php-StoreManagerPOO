<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Service\DashboardService;
use App\Service\SupplyService;
use Exception;

class DashboardController
{
    public static function index(): void
    {
        $kpis = DashboardService::getKpis();
        $dernieresVentes = DashboardService::getDernieresVentes(5);
        $dettesDuJour = DashboardService::getDettesDuJour();
        $rupturesEtAlertes = DashboardService::getRupturesEtAlertes();
        $livraisonsDuJour = DashboardService::getLivraisonsDuJour();
        $clientsDebiteurs = DashboardService::getClientsDebiteurs();
        $soldeFournisseurs = DashboardService::getSoldeFournisseurs();
        $performanceVendeurs = DashboardService::getPerformanceVendeurs();

        Controller::renderViewLayout("dashboard", "base", [
            'kpis' => $kpis,
            'dernieresVentes' => $dernieresVentes,
            'dettesDuJour' => $dettesDuJour,
            'rupturesEtAlertes' => $rupturesEtAlertes,
            'livraisonsDuJour' => $livraisonsDuJour,
            'clientsDebiteurs' => $clientsDebiteurs,
            'soldeFournisseurs' => $soldeFournisseurs,
            'performanceVendeurs' => $performanceVendeurs,
            'currentPage' => 'dashboard'
        ]);
    }

    public static function quickSupply(): void
    {
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
                    // ignore or log
                }
            }
        }

        Controller::redirectToRoute("dashboard");
    }
}
