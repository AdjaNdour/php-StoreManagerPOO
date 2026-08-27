<?php

namespace App\Controller;

use Adja\Core\Controller;
use Adja\Core\Debug;
use Adja\Core\SessionManager;
use Adja\Core\Request;
use Adja\Core\Validator;
use App\Service\ProduitService;
use App\Service\ClientService;
use App\Service\FournisseurService;
use App\Model\Entity\Produit;
use App\Model\Entity\Client;
use App\Model\Entity\Fournisseur;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;

class ProduitController
{
    public static function index(): void
    {
        $viewsPath = PATHBASE . '/src/Views';
        
        $tab = Request::get('tab', 'products');
        // Debug::dd($_GET);
        $search = (string)Request::get('search', '');
        $page = (int)Request::get('page', 1);

        $filtered = new FilteredModel(['search' => $search]);
        $pagination = new PaginationModel(page: $page);

        $produits = [];
        $clients = [];
        $fournisseurs = [];

        if ($tab === 'clients') {
            $clients = ClientService::getAllFiltered($filtered, $pagination);
        } elseif ($tab === 'suppliers') {
            $fournisseurs = FournisseurService::getAllFiltered($filtered, $pagination);
        } else {
            $tab = 'products';
            $produits = ProduitService::getAllFiltered($filtered, $pagination);
        }

        $allFournisseurs = FournisseurService::getAll();
        $statistiques = ProduitService::getStatistiques();

        $errors = SessionManager::getData('errors') ?? [];
        if (SessionManager::hasKey('errors')) {
            SessionManager::removeData('errors');
        }

        Controller::renderViewLayout("produits", "base", [
            'tab' => $tab,
            'produits' => $produits,
            'clients' => $clients,
            'fournisseurs' => $fournisseurs,
            'allFournisseurs' => $allFournisseurs,
            'statistiques' => $statistiques,
            'stats' => $statistiques,
            'errors' => $errors,
            'pagination' => $pagination,
            'filteredTableau' => $filtered,
            'currentPage' => 'produits'
        ], $viewsPath);
    }

    public static function addProduct(): void
    {
        if (Request::isPost()) {
            $libelle = trim((string)(Request::post('libelle') ?? Request::post('nom') ?? ''));
            $categorie = trim((string)Request::post('categorie', 'Général'));
            $prixVente = (float)(Request::post('prix_vente') ?? Request::post('prix_unitaire') ?? 0);
            $coutAchat = (float)Request::post('cout_achat', 0);
            $stockInitial = (int)(Request::post('stock_initial') ?? Request::post('quantite_stock') ?? 0);
            $fournisseurId = (int)Request::post('fournisseur_id', 0);

            if ($coutAchat <= 0 && $prixVente > 0) {
                $coutAchat = round($prixVente * 0.7);
            }

            $errors = [];
            Validator::required($libelle, 'nom', $errors, "Le nom de l'article est obligatoire.");
            Validator::isPositive($prixVente, 'prix_unitaire', $errors, "Le prix de vente doit être positif.");
            Validator::isPositive($stockInitial, 'quantite_stock', $errors, "Le stock initial doit être positif.");

            if (!Validator::hasErrors($errors)) {
                $fournisseur = ($fournisseurId > 0) ? FournisseurService::getById($fournisseurId) : null;
                $produit = new Produit(
                    code: ProduitService::genererCodeProduit($libelle),
                    libelle: $libelle,
                    categorie: !empty($categorie) ? $categorie : 'Général',
                    prixVente: $prixVente,
                    coutAchat: $coutAchat,
                    stockInitial: $stockInitial,
                    fournisseur: $fournisseur
                );

                try {
                    ProduitService::save($produit);
                } catch (Exception $e) {
                    SessionManager::setData('error', $e->getMessage());
                }
            } else {
                SessionManager::setData('errors', $errors);
            }
        }

        Controller::redirectToRoute("produits?tab=products", WEB_ROUTE);
    }

    public static function addClient(): void
    {
        if (Request::isPost()) {
            $nom = trim(Request::post('nom', ''));
            $prenom = trim(Request::post('prenom', ''));
            $telephone = trim(Request::post('telephone', ''));
            $email = trim(Request::post('email', ''));
            $limiteCredit = (float)Request::post('limite_credit', 0);

            $errors = [];
            Validator::required($nom, 'nom', $errors, "Le nom est obligatoire.");
            Validator::required($prenom, 'prenom', $errors, "Le prénom est obligatoire.");
            Validator::required($telephone, 'telephone', $errors, "Le téléphone est obligatoire.");
            Validator::isEmail($email, 'email', $errors, "Email invalide.");
            Validator::isPositive($limiteCredit, 'limite_credit', $errors, "La limite de crédit doit être positive.");

            if (!Validator::hasErrors($errors)) {
                $client = new Client(
                    nom: $nom,
                    prenom: $prenom,
                    telephone: $telephone,
                    email: !empty($email) ? $email : null,
                    limiteCredit: $limiteCredit
                );

                try {
                    ClientService::save($client);
                } catch (Exception $e) {
                    SessionManager::setData('error', $e->getMessage());
                }
            } else {
                SessionManager::setData('errors', $errors);
            }
        }

        Controller::redirectToRoute("produits?tab=clients", WEB_ROUTE);
    }

    public static function addSupplier(): void
    {
        if (Request::isPost()) {
            $nom = trim(Request::post('nom', ''));
            $email = trim(Request::post('email', ''));
            $telephone = trim(Request::post('telephone', ''));
            $adresse = trim(Request::post('adresse', ''));

            $errors = [];
            Validator::required($nom, 'nom', $errors, "Le nom de l'entreprise est obligatoire.");
            Validator::required($telephone, 'telephone', $errors, "Le téléphone est obligatoire.");
            Validator::isEmail($email, 'email', $errors, "Email invalide.");

            if (!Validator::hasErrors($errors)) {
                $fournisseur = new Fournisseur(
                    nom: $nom,
                    telephone: $telephone,
                    email: !empty($email) ? $email : null,
                    adresse: !empty($adresse) ? $adresse : null
                );

                try {
                    FournisseurService::save($fournisseur);
                } catch (Exception $e) {
                    SessionManager::setData('error', $e->getMessage());
                }
            } else {
                SessionManager::setData('errors', $errors);
            }
        }

        Controller::redirectToRoute("produits?tab=suppliers", WEB_ROUTE);
    }
}
