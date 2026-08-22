<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Service\SupplyService;
use App\Service\FournisseurService;
use App\Service\ProduitService;
use App\Model\Entity\Approvisionnement;
use App\Model\Entity\LigneApprovisionnement;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Exception;

class SupplyController
{
    public static function index(): void
    {
        $page = (int)Request::get('page', 1);
        $search = (string)Request::get('search', '');
        $statut = (string)Request::get('statut', 'ALL');

        $filtered = new FilteredModel([
            'search' => $search,
            'statut' => $statut
        ]);

        $pagination = new PaginationModel(page: $page, limit: 4);

        $appros = SupplyService::getAllFiltered($filtered, $pagination);
        $fournisseurs = FournisseurService::getAll();
        $produits = ProduitService::getAll();
        $statistiques = SupplyService::getStatistiques();

        Controller::renderViewLayout("appros", "base", [
            'appros' => $appros,
            'allAppros' => $appros,
            'fournisseurs' => $fournisseurs,
            'produits' => $produits,
            'statistiques' => $statistiques,
            'stats' => $statistiques,
            'pagination' => $pagination,
            'filtered' => $filtered,
            'filteredTableau' => $filtered,
            'currentPage' => 'appros'
        ]);
    }

    public static function saveAppro(): void
    {
        if (Request::isPost()) {
            $fournisseurId = (int)Request::post('fournisseur_id', 0);
            $referenceBl = trim((string)Request::post('reference_bl', ''));
            $dateAppro = (string)Request::post('date_appro', date('Y-m-d'));
            $receptionDirecte = (bool)Request::post('reception_directe', false);

            $errors = [];
            Validator::isGreaterThanZero($fournisseurId, 'fournisseur_id', $errors, "Le fournisseur est obligatoire.");

            $rawProduitId = Request::post('produit_id');
            $rawQuantite = Request::post('quantite') ?? Request::post('quantite_appro');
            $rawPrixAchat = Request::post('prix_achat');

            $lignes = [];
            $totalAchat = 0;

            if (is_array($rawProduitId)) {
                $quantites = is_array($rawQuantite) ? $rawQuantite : [];
                $prixAchats = is_array($rawPrixAchat) ? $rawPrixAchat : [];

                for ($i = 0; $i < count($rawProduitId); $i++) {
                    $pId = (int)($rawProduitId[$i] ?? 0);
                    $qte = (int)($quantites[$i] ?? 0);
                    $prix = (float)($prixAchats[$i] ?? 0);

                    if ($pId > 0 && $qte > 0) {
                        $produit = ProduitService::getById($pId);
                        if ($produit) {
                            if ($prix <= 0) {
                                $prix = $produit->getCoutAchat() > 0 ? $produit->getCoutAchat() : $produit->getPrixVente();
                            }
                            $ligne = new LigneApprovisionnement(
                                approvisionnementId: 0,
                                quantiteAppro: $qte,
                                prixAchat: $prix,
                                produit: $produit,
                                quantiteRecue: $receptionDirecte ? $qte : 0
                            );
                            $lignes[] = $ligne;
                            $totalAchat += ($qte * $prix);
                        }
                    }
                }
            } else {
                $pId = (int)($rawProduitId ?? 0);
                $qte = (int)($rawQuantite ?? 0);
                $prix = (float)($rawPrixAchat ?? 0);

                Validator::isGreaterThanZero($pId, 'produit_id', $errors, "Veuillez choisir un article.");
                Validator::isGreaterThanZero($qte, 'quantite', $errors, "La quantité doit être supérieure à zéro.");

                if ($pId > 0 && $qte > 0) {
                    $produit = ProduitService::getById($pId);
                    if ($produit) {
                        if ($prix <= 0) {
                            $prix = $produit->getCoutAchat() > 0 ? $produit->getCoutAchat() : $produit->getPrixVente();
                        }
                        $ligne = new LigneApprovisionnement(
                            approvisionnementId: 0,
                            quantiteAppro: $qte,
                            prixAchat: $prix,
                            produit: $produit,
                            quantiteRecue: $receptionDirecte ? $qte : 0
                        );
                        $lignes[] = $ligne;
                        $totalAchat = ($qte * $prix);
                    }
                }
            }

            if (empty($lignes)) {
                $errors['lignes'] = "Veuillez ajouter au moins un produit.";
            }

            if (!Validator::hasErrors($errors)) {
                $fournisseur = FournisseurService::getById($fournisseurId);
                if ($fournisseur) {
                    if (empty($referenceBl)) {
                        $referenceBl = SupplyService::genererReferenceBL($fournisseur->getNom());
                    }

                    $appro = new Approvisionnement(
                        referenceBl: $referenceBl,
                        fournisseur: $fournisseur,
                        dateReception: $receptionDirecte ? date('Y-m-d') : null,
                        dateAppro: !empty($dateAppro) ? $dateAppro : date('Y-m-d'),
                        coutAchat: $totalAchat
                    );
                    $appro->setLignes($lignes);

                    try {
                        SupplyService::enregistrerApprovisionnement($appro);
                    } catch (Exception $e) {
                        // Handled
                    }
                }
            }
        }

        Controller::redirectToRoute("appros");
    }

    public static function receiveAppro(): void
    {
        if (Request::isPost()) {
            $approId = (int)(Request::post('approvisionnement_id') ?? Request::post('appro_id') ?? Request::post('id') ?? 0);
            $quantitesRecues = Request::post('quantites_livrees') ?? Request::post('quantites_recues') ?? Request::post('quantite_recue') ?? [];

            $errors = [];
            Validator::isGreaterThanZero($approId, 'appro_id', $errors, "Identifiant BL invalide.");

            if (!Validator::hasErrors($errors)) {
                try {
                    SupplyService::receptionnerBL($approId, is_array($quantitesRecues) ? $quantitesRecues : []);
                } catch (Exception $e) {
                    // Handled
                }
            }
        }

        Controller::redirectToRoute("appros");
    }

    public static function receptionnerBL(): void
    {
        self::receiveAppro();
    }
}
