<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Service\ProduitService;
use App\Service\ClientService;
use App\Service\FournisseurService;
use App\Model\Entity\Produit;
use App\Model\Entity\Client;
use App\Model\Entity\Fournisseur;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;

class CatalogController
{
    public static function index(): void
    {
        $tab = Request::get('tab', 'products');
        $search = (string)Request::get('search', '');
        $page = (int)Request::get('page', 1);

        $filtered = new FilteredModel(['search' => $search]);
        $pagination = new PaginationModel(page: $page, limit: 4);

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

        Controller::renderViewLayout("produits", "base", [
            'activeTab' => $tab,
            'produits' => $produits,
            'clients' => $clients,
            'fournisseurs' => $fournisseurs,
            'allFournisseurs' => $allFournisseurs,
            'pagination' => $pagination,
            'filtered' => $filtered,
            'currentPage' => 'produits'
        ]);
    }

    public static function addProduct(): void
    {
        if (Request::isPost()) {
            $code = trim(Request::post('code', ''));
            $libelle = trim(Request::post('libelle', ''));
            $categorie = trim(Request::post('categorie', ''));
            $prixVente = (float)Request::post('prix_vente', 0);
            $coutAchat = (float)Request::post('cout_achat', 0);
            $stockInitial = (int)Request::post('stock_initial', 0);
            $seuilAlerte = (int)Request::post('seuil_alerte', 5);
            $fournisseurId = (int)Request::post('fournisseur_id', 0);

            $errors = [];
            Validator::required($libelle, 'libelle', $errors, "Le libellé est obligatoire.");
            Validator::isPositive($prixVente, 'prix_vente', $errors, "Le prix de vente doit être positif.");
            Validator::isPositive($coutAchat, 'cout_achat', $errors, "Le coût d'achat doit être positif.");
            Validator::isPositive($stockInitial, 'stock_initial', $errors, "Le stock initial doit être positif.");

            if (!Validator::hasErrors($errors)) {
                $fournisseur = ($fournisseurId > 0) ? FournisseurService::getById($fournisseurId) : null;
                $produit = new Produit(
                    code: $code,
                    libelle: $libelle,
                    categorie: $categorie,
                    prixVente: $prixVente,
                    coutAchat: $coutAchat,
                    stockInitial: $stockInitial,
                    seuilAlerte: $seuilAlerte,
                    fournisseur: $fournisseur
                );

                try {
                    ProduitService::enregistrer($produit);
                } catch (Exception $e) {
                    // ignore or flash
                }
            }
        }

        Controller::redirectToRoute("produits?tab=products");
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
                    ClientService::enregistrer($client);
                } catch (Exception $e) {
                    // ignore or flash
                }
            }
        }

        Controller::redirectToRoute("produits?tab=clients");
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
                    FournisseurService::enregistrer($fournisseur);
                } catch (Exception $e) {
                    // ignore or flash
                }
            }
        }

        Controller::redirectToRoute("produits?tab=suppliers");
    }
}
