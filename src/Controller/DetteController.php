<?php

require_once PATHBASE . "/src/Core/Controller.php";
require_once PATHBASE . "/src/Service/DeptService.php";
require_once PATHBASE . "/src/Service/ModePaiementService.php";

class DetteController extends Controller
{
    private DebtService $debtService;
    private ModePaiementService $modePaiementService;

    public function __construct()
    {
        $this->debtService = new DebtService();
        $this->modePaiementService = new ModePaiementService();
    }

    public function getAllDettes(): void
    {

        $allDettes = $this->debtService->getActiveDebts();
        $modes = $this->modePaiementService->getAll();

        $stats = $this->debtService->getStatistiques();
        $clientsDebiteurs = (int)$stats['nbr_clients_dettes'];
        $creancesActives = (float)$stats['somme_montant_restant_dettes'];
        $totalRecouvrements = (float)$stats['somme_montant_verser_dettes'];
        $produitsParDette = [];
        foreach ($allDettes as $dette) {
            $produitsParDette[$dette->getId()] = $this->debtService->getAllProduitsDette($dette->getId());
        }
                // Debug::dd($produitsParDette);

        $this->renderViewLayout('dettes', 'base', [
            'allDettes' => $allDettes,
            'stats' => $stats,
            'modes' => $modes,
            'clientsDebiteurs' => $clientsDebiteurs,
            'creancesActives' => $creancesActives,
            'totalRecouvrements' => $totalRecouvrements,
            'produitsParDette' => $produitsParDette
        ]);
    }

    public function enregistrerRemboursementDette(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $detteId = (int)($_REQUEST['dette_id'] ?? 0);
            $montant = (float)($_REQUEST['montant_verse'] ?? 0);
            $modeId = $_REQUEST['mode_paiement'] ?? 0;

            if ($detteId > 0 && $montant > 0) {
                $this->debtService->enregistrerPaiement($detteId, $montant, $modeId);
            }
        }

        $this->redirectToRoute('dettes');
    }

    public function listerProduitsDette(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $detteId = (int)($_REQUEST['dette_id'] ?? 0);

            if ($detteId > 0) {
                $this->debtService->getAllProduitsDette($detteId);
            }
        }

        $this->redirectToRoute('dettes');
    }
}
