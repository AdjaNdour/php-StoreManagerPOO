<?php

require_once PATHBASE . "/src/Core/Controller.php";
require_once PATHBASE . "/src/Service/DeptService.php";
require_once PATHBASE . "/src/Service/ModePaiementService.php";

class DetteController extends Controller
{

    public static function getAllDettes(): void
    {

        $allDettes = DebtService::getActiveDebts();
        $modes = ModePaiementService::getAll();

        $stats = DebtService::getStatistiques();
        $clientsDebiteurs = (int)$stats['nbr_clients_dettes'];
        $creancesActives = (float)$stats['somme_montant_restant_dettes'];
        $totalRecouvrements = (float)$stats['somme_montant_verser_dettes'];
        $produitsParDette = [];
        foreach ($allDettes as $dette) {
            $produitsParDette[$dette->getId()] = DebtService::getAllProduitsDette($dette->getId());
        }
                // Debug::dd($produitsParDette);

        self::renderViewLayout('dettes', 'base', [
            'allDettes' => $allDettes,
            'stats' => $stats,
            'modes' => $modes,
            'clientsDebiteurs' => $clientsDebiteurs,
            'creancesActives' => $creancesActives,
            'totalRecouvrements' => $totalRecouvrements,
            'produitsParDette' => $produitsParDette
        ]);
    }

    public static function enregistrerRemboursementDette(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $detteId = (int)($_REQUEST['dette_id'] ?? 0);
            $montant = (float)($_REQUEST['montant_verse'] ?? 0);
            $modeId = $_REQUEST['mode_paiement'] ?? 0;

            if ($detteId > 0 && $montant > 0) {
                DebtService::enregistrerPaiement($detteId, $montant, $modeId);
            }
        }

        self::redirectToRoute('dettes');
    }

    public static function listerProduitsDette(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $detteId = (int)($_REQUEST['dette_id'] ?? 0);

            if ($detteId > 0) {
                DebtService::getAllProduitsDette($detteId);
            }
        }

        self::redirectToRoute('dettes');
    }
}
