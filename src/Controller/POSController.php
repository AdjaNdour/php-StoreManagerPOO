<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\SessionManager;
use App\Core\Request;
use App\Core\Validator;
use App\Service\VenteService;
use App\Service\ProduitService;
use App\Service\ClientService;
use App\Service\ModePaiementService;
use App\Model\Entity\Vente;
use App\Model\Entity\LigneVente;
use App\Model\Entity\Client;
use App\Model\Entity\Utilisateur;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;

class POSController
{
    public static function getAllVente(): void
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

        $ventes = VenteService::getAllVentesFiltered($filtered, $pagination);
        $produits = ProduitService::getAll();
        $clients = ClientService::getAll();
        $modesPaiement = ModePaiementService::getAll();
        $statistiques = VenteService::getStatistiques();

        $panier = SessionManager::getData('pos_cart') ?? [];
        $panierTotal = 0;
        foreach ($panier as $item) {
            $panierTotal += ($item['quantite'] * $item['prix_vente']);
        }

        Controller::renderViewLayout("pos", "base", [
            'ventes' => $ventes,
            'allVentes' => $ventes,
            'produits' => $produits,
            'clients' => $clients,
            'modesPaiement' => $modesPaiement,
            'modePaiement' => $modesPaiement,
            'statistiques' => $statistiques,
            'stats' => $statistiques,
            'panier' => $panier,
            'panierTotal' => $panierTotal,
            'montantTotalPanier' => $panierTotal,
            'pagination' => $pagination,
            'filtered' => $filtered,
            'filteredTableau' => $filtered,
            'currentPage' => 'pos'
        ]);
    }

    public static function ajouterAuPanier(): void
    {
        if (Request::isPost()) {
            $produitId = (int)Request::post('produit_id', 0);
            $quantite = (int)Request::post('quantite', 1);

            $errors = [];
            Validator::isGreaterThanZero($produitId, 'produit_id', $errors, "Veuillez choisir un article.");
            Validator::isGreaterThanZero($quantite, 'quantite', $errors, "La quantité doit être supérieure à zéro.");

            if (!Validator::hasErrors($errors)) {
                $produit = ProduitService::getById($produitId);
                if ($produit) {
                    $panier = SessionManager::getData('pos_cart') ?? [];

                    $qteExistante = isset($panier[$produitId]) ? $panier[$produitId]['quantite'] : 0;
                    $qteTotale = $qteExistante + $quantite;

                    if ($produit->getStockInitial() >= $qteTotale) {
                        $panier[$produitId] = [
                            'id' => $produit->getId(),
                            'code' => $produit->getCode(),
                            'libelle' => $produit->getLibelle(),
                            'prix_vente' => $produit->getPrixVente(),
                            'prix_unitaire' => $produit->getPrixVente(),
                            'quantite' => $qteTotale,
                            'montant' => $qteTotale * $produit->getPrixVente(),
                            'sous_total' => $qteTotale * $produit->getPrixVente(),
                        ];
                        SessionManager::saveData('pos_cart', $panier);
                    }
                }
            }
        }

        Controller::redirectToRoute("pos");
    }

    public static function supprimerDuPanier(): void
    {
        $produitId = (int)(Request::input('produit_id') ?? Request::input('index') ?? 0);
        $panier = SessionManager::getData('pos_cart') ?? [];

        if (isset($panier[$produitId])) {
            unset($panier[$produitId]);
            SessionManager::saveData('pos_cart', $panier);
        }

        Controller::redirectToRoute("pos");
    }

    public static function validerVente(): void
    {
        if (Request::isPost()) {
            $btnAction = Request::post('btnSaveVente');
            if ($btnAction === 'addPanier') {
                self::ajouterAuPanier();
                return;
            }
            if ($btnAction === 'clearPanier') {
                SessionManager::removeData('pos_cart');
                Controller::redirectToRoute("pos");
                return;
            }

            $panier = SessionManager::getData('pos_cart') ?? [];
            if (empty($panier)) {
                Controller::redirectToRoute("pos");
                return;
            }

            $clientId = (int)Request::post('client_id', 0);
            $modePaiementId = (int)(Request::post('mode_paiement_id') ?? Request::post('mode_reglement') ?? 1);
            if ($modePaiementId <= 0) {
                $modePaiementId = 1;
            }

            $montantVerse = (float)Request::post('montant_verse', 0);
            $dateEcheance = Request::post('date_echeance', null);

            $errors = [];
            Validator::isGreaterThanZero($clientId, 'client_id', $errors, "Le client est obligatoire.");
            Validator::isPositive($montantVerse, 'montant_verse', $errors, "Le montant versé doit être positif.");

            if (!Validator::hasErrors($errors)) {
                $client = ClientService::getById($clientId);
                if ($client) {
                    $numeroFacture = VenteService::genererNumeroFacture();

                    $user = SessionManager::getData(KEY_USERCONNECT);
                    $utilisateur = ($user instanceof Utilisateur) ? $user : null;

                    $vente = new Vente(
                        client: $client,
                        numeroFacture: $numeroFacture,
                        montantVerse: $montantVerse,
                        dateEcheance: !empty($dateEcheance) ? $dateEcheance : null,
                        utilisateur: $utilisateur,
                        modePaiementId: $modePaiementId
                    );

                    foreach ($panier as $item) {
                        $prd = ProduitService::getById((int)$item['id']);
                        if ($prd) {
                            $ligne = new LigneVente(
                                produit: $prd,
                                quantite: (int)$item['quantite'],
                                prixUnitaire: (float)$item['prix_vente']
                            );
                            $vente->ajouterLigne($ligne);
                        }
                    }

                    try {
                        VenteService::validerVente($vente);
                        SessionManager::removeData('pos_cart');
                    } catch (Exception $e) {
                        // Error handled
                    }
                }
            }
        }

        Controller::redirectToRoute("pos");
    }
}
