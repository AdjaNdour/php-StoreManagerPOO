<?php
require_once PATHBASE . "/src/Service/VenteService.php";
require_once PATHBASE . "/src/Service/ClientService.php";
require_once PATHBASE . "/src/Service/ProduitService.php";
require_once PATHBASE . "/src/Service/ModePaiementService.php";

class POSController extends Controller
{
    private VenteService $venteService;
    private ClientService $clientService;
    private ProduitService $produitService;
    private ModePaiementService $modePaiementService;

    public function __construct()
    {
        $this->venteService = new VenteService();
        $this->clientService = new ClientService();
        $this->produitService = new ProduitService();
        $this->modePaiementService = new ModePaiementService();
    }

    public function getAllVente(): void
    {
        if (!isset($_SESSION['vente']['panier'])) $_SESSION['vente']['panier'] = [];
        if (!isset($_SESSION['vente']['montantTotal'])) $_SESSION['vente']['montantTotal'] = 0;

        $allVentes = $this->venteService->getAll();
        $stats = $this->venteService->getStatistiques();
        $produits = $this->produitService->getAll();
        $clients = $this->clientService->getAll();
        $modePaiement = $this->modePaiementService->getAll();
        $panier = $_SESSION['vente']['panier'];
        $montantTotalPanier = $_SESSION['vente']['montantTotal'];

        $nbrVentes = (int)$stats['nbr_ventes'];
        $montantTotal = (float)$stats['montant_total'];
        $montantEncaisse = (float)$stats['montant_encaisse'];

        $this->renderViewLayout('pos', 'base', [
            'allVentes' => $allVentes,
            'nbrVentes' => $nbrVentes,
            'montantTotal' => $montantTotal,
            'montantEncaisse' => $montantEncaisse,
            'produits' => $produits,
            'clients' => $clients,
            'modePaiement' => $modePaiement,
            'panier' => $panier,
            'montantTotalPanier' => $montantTotalPanier
        ]);
    }

    public function ajouterAuPanier(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToRoute('pos');
            return;
        }

        $produitId = (int)($_POST['produit_id'] ?? 0);
        $quantite = (int)($_POST['quantite'] ?? 0);

        if ($produitId <= 0 || $quantite <= 0) throw new Exception("Produit ou quantité invalide.");

        $produit = $this->produitService->getById($produitId);

        if ($produit === null) throw new Exception("Produit introuvable.");
        if ($quantite > $produit->getStockInitial()) throw new Exception("Stock insuffisant.");

        $prixUnitaire = $produit->getPrixVente();

        $ligne = [
            'produit_id' => $produit->getId(),
            'libelle' => $produit->getLibelle(),
            'quantite' => $quantite,
            'prix_unitaire' => $prixUnitaire,
            'montant' => $quantite * $prixUnitaire
        ];

        $this->ajouterLignePanier($ligne);
        $this->redirectToRoute('pos');
    }

    private function ajouterLignePanier(array $ligne): void
    {
        if (!isset($_SESSION['vente']['panier'])) $_SESSION['vente']['panier'] = [];
        if (!isset($_SESSION['vente']['montantTotal'])) $_SESSION['vente']['montantTotal'] = 0;
        $_SESSION['vente']['panier'][] = $ligne;
        $_SESSION['vente']['montantTotal'] += $ligne['montant'];
    }

    public function supprimerDuPanier(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToRoute('pos');
            return;
        }

        $index = (int)($_POST['index'] ?? -1);

        if (isset($_SESSION['vente']['panier'][$index])) {
            $ligne = $_SESSION['vente']['panier'][$index];
            $_SESSION['vente']['montantTotal'] -= $ligne['montant'];
            array_splice($_SESSION['vente']['panier'], $index, 1);
        }

        $this->redirectToRoute('pos');
    }

    public function validerVente(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectToRoute('pos');
            return;
        }

        $action = $_POST['btnSaveVente'];

        if ($action === 'addPanier') {
            $this->ajouterAuPanier();
            return;
        }

        $clientId = (int)($_POST['client_id'] ?? 0);
        $montantVerse = (float)($_POST['montant_verse'] ?? 0);
        $modePaiementId = (int)($_POST['mode_reglement'] ?? 0);
        $panier = $_SESSION['vente']['panier'] ?? [];

        if ($clientId <= 0) throw new Exception("Un client est obligatoire.");
        if (empty($panier)) throw new Exception("Le panier est vide.");
        if ($modePaiementId <= 0) throw new Exception("Le mode de règlement est obligatoire.");
        if ($montantVerse < 0) throw new Exception("Le montant versé est invalide.");

        $lignes = [];

        foreach ($panier as $ligne) {
            $ligneVente = new LigneVente(
                (int)$ligne['produit_id'],
                (int)$ligne['quantite'],
                (float)$ligne['prix_unitaire']
            );
            $lignes[] = $ligneVente;
        }

        $numeroFacture = 'CMD-' . time();

        $vente = new Vente(
            $numeroFacture,
            0,
            $montantVerse,
            'PAYEE',
            null,
            $clientId,
            null,
            null,
            date('Y-m-d')
        );

        $vente->setModePaiementId($modePaiementId);

        foreach ($lignes as $ligne) {
            $vente->ajouterLigne($ligne);
        }
        $this->venteService->validerVente($vente);

        $_SESSION['vente']['panier'] = [];
        $_SESSION['vente']['montantTotal'] = 0;

        $this->redirectToRoute('pos');
    }
}
