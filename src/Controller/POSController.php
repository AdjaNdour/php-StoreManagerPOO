<?php

namespace App\Controller;

use Adja\Core\Controller;
use Adja\Core\Debug;
use Adja\Core\SessionManager;
use Adja\Core\Request;
use Adja\Core\Validator;

use App\Service\VenteService;
use App\Service\ProduitService;
use App\Service\ClientService;
use App\Service\ModePaiementService;
use App\Model\Entity\Vente;
use App\Model\Entity\LigneVente;
use App\Model\Entity\Utilisateur;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;

class POSController
{
    public static function getAllVente(): void
    {
        $viewsPath = PATHBASE . '/src/Views' ;
        $page = (int)Request::get('page', 1);

        $search = (string)Request::get('search', '');
        $statut = (string)Request::get('statut', 0);
        $clientId = (int)Request::get('client_id', 0);

        $filtered = new FilteredModel(['search' => $search,'statut' => $statut,'client_id' => $clientId]);

        $pagination = new PaginationModel(page: $page);

        $ventes = VenteService::getAllFiltered($filtered, $pagination);
        $produits = ProduitService::getAll();
        $clients = ClientService::getAll();
        $modesPaiement = ModePaiementService::getAll();
        $statistiques = VenteService::getStatistiques();

        $panier = SessionManager::getData('panier-vente') ?? [];
        $panierTotal = 0;
        foreach ($panier as $item) {
            $panierTotal += $item['sousTotal'];
        }

        $errors = SessionManager::getData('errors') ?? [];
        if (SessionManager::hasKey('errors')) {
            SessionManager::removeData('errors');
        }

        Controller::renderViewLayout("pos", "base", [
            'allVentes' => $ventes,
            'produits' => $produits,
            'clients' => $clients,
            'modesPaiement' => $modesPaiement,
            'stats' => $statistiques,
            'panier' => $panier,
            'panierTotal' => $panierTotal,
            'errors' => $errors,
            'pagination' => $pagination,
            'filteredTableau' => $filtered,
            'currentPage' => 'pos'
        ], $viewsPath);
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
                    $panier = SessionManager::getData('panier-vente') ?? [];

                    $qteProduitX = isset($panier[$produitId]) ? $panier[$produitId]['quantite'] : 0;

                    $qteTotale = $qteProduitX + $quantite;

                    if ($produit->getStockInitial() >= $qteTotale) {

                        $panier[$produitId] = [
                            'produit' => $produit,
                            'sousTotal' => $produit->getPrixVente() * $qteTotale,
                            'quantite' => $qteTotale
                        ];
                        SessionManager::setData('panier-vente', $panier);
                    } else {
                        $errors['quantite'] = "Stock insuffisant (disponible: " . $produit->getStockInitial() . ").";
                        SessionManager::setData('errors', $errors);
                    }
                }
            } else {
                SessionManager::setData('errors', $errors);
            }
        }
        Controller::redirectToRoute("pos", WEB_ROUTE);
    }

    public static function supprimerDuPanier(): void
    {
        $produitId = (int) Request::post('index') ?? 0;
        $panier = SessionManager::getData('panier-vente') ?? [];

        if (isset($panier[$produitId])) {
            unset($panier[$produitId]);
            SessionManager::setData('panier-vente', $panier);
        }
        Controller::redirectToRoute("pos", WEB_ROUTE);
    }

    public static function validerVente(): void
    {
        $keyUserConnect = defined('KEY_USERCONNECT') ? KEY_USERCONNECT : 'userConnect';

        if (Request::isPost()) {
            $btnAction = Request::post('btnSaveVente');

            if ($btnAction === 'addPanier') {
                self::ajouterAuPanier();
                return;
            }

            if ($btnAction === 'clearPanier') {
                SessionManager::removeData('panier-vente');
                Controller::redirectToRoute("pos", WEB_ROUTE);
                return;
            }

            $panier = SessionManager::getData('panier-vente') ?? [];
            if (empty($panier)) {
                $errors = ['panier' => "Le panier est vide. Veuillez ajouter au moins un produit."];
                SessionManager::setData('errors', $errors);
                Controller::redirectToRoute("pos", WEB_ROUTE);
                return;
            }

            $clientId = (int)Request::post('client_id', 0);
            $modePaiementId = (int)(Request::post('mode_reglement') ?? 1);
            $montantVerse = (float)Request::post('montant_verse', 0);

            $errors = [];
            
            Validator::isGreaterThanZero($clientId, 'client_id', $errors, "Le client est obligatoire.");
            Validator::isGreaterThanZero($modePaiementId, 'mode_reglement', $errors, "Le mode de paiement est obligatoire.");
            Validator::isPositive($montantVerse, 'montant_verse', $errors, "Le montant versé doit être positif.");

            if (!Validator::hasErrors($errors)) {
                $client = ClientService::getById($clientId);
                if ($client) {
                    $numeroFacture = VenteService::genererNumeroFacture();
                    $user = SessionManager::getData($keyUserConnect);
                    $utilisateur = ($user instanceof Utilisateur) ? $user : null;

                    $vente = new Vente(
                        client: $client,
                        numeroFacture: $numeroFacture,
                        montantVerse: $montantVerse,
                        utilisateur: $utilisateur,
                        modePaiementId: $modePaiementId
                    );

                    foreach ($panier as $item) {
                        $prd = $item['produit'];
                        $ligne = new LigneVente(
                            produit: $prd,
                            quantite: (int)$item['quantite'],
                            prixUnitaire: (float)$prd->getPrixVente()
                        );
                        $vente->ajouterLigne($ligne);
                    }

                    try {
                        VenteService::save($vente);
                        SessionManager::removeData('panier-vente');
                    } catch (Exception $e) {
                        SessionManager::setData('error', $e->getMessage());
                    }
                }
            } else {
                SessionManager::setData('errors', $errors);
            }
        }

        Controller::redirectToRoute("pos", WEB_ROUTE);
    }
}
