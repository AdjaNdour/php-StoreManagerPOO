<?php

namespace App\Service;

use App\Model\Repository\ApprovisionnementRepository;
use App\Model\Repository\FournisseurRepository;
use App\Model\Repository\ProduitRepository;
use App\Model\Entity\Approvisionnement;
use App\Model\Entity\LigneApprovisionnement;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use Adja\Core\Database;
use Exception;

class SupplyService
{
    public static function getAllFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        return ApprovisionnementRepository::selectAllFiltered($filtered, $pagination);
    }

    public static function getStatistiques(): object
    {
        return ApprovisionnementRepository::selectStatistiques();
    }

    public static function genererReferenceBL(string $nomFournisseur = ''): string
    {
        $id = Database::getLastId("approvisionnements") + 1;
        $prefix = !empty($nomFournisseur) ? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $nomFournisseur), 0, 3)) : 'GEN';
        return "BL-" . $prefix . "-" . $id;
    }

    public static function save(Approvisionnement $appro): int
    {
        if ($appro->getFournisseurId() <= 0) {
            throw new Exception("Le fournisseur est obligatoire.");
        }
        if (trim($appro->getReferenceBl()) === '') {
            throw new Exception("La référence du bon de livraison (BL) est obligatoire.");
        }
        if (empty($appro->getLignes())) {
            throw new Exception("Veuillez ajouter au moins un produit à l'approvisionnement.");
        }

        return ApprovisionnementRepository::insert($appro);
    }

    public static function receptionnerBL(int $approId, array $quantitesRecues = []): bool
    {
        if ($approId <= 0) {
            throw new Exception("Identifiant d'approvisionnement invalide.");
        }
        return ApprovisionnementRepository::receptionnerBL($approId, $quantitesRecues);
    }

    public static function commandeRapide(int $produitId, int $fournisseurId, int $quantite, float $coutUnitaire): int
    {
        if ($produitId <= 0 || $fournisseurId <= 0 || $quantite <= 0) {
            throw new Exception("Paramètres de commande rapide invalides.");
        }

        $fournisseur = FournisseurRepository::selectById($fournisseurId);
        $produit = ProduitRepository::selectById($produitId);

        if (!$fournisseur || !$produit) {
            throw new Exception("Fournisseur ou Produit introuvable.");
        }

        $refBL = self::genererReferenceBL($fournisseur->getNom());

        $appro = new Approvisionnement(
            referenceBl: $refBL,
            fournisseur: $fournisseur,
            dateReception: null,
            dateAppro: date('Y-m-d'),
            coutAchat: $quantite * $coutUnitaire
        );

        $ligne = new LigneApprovisionnement(
            approvisionnementId: 0,
            quantiteAppro: $quantite,
            prixAchat: $coutUnitaire,
            produit: $produit,
            quantiteRecue: 0
        );

        $appro->ajouterLigne($ligne);
        return self::save($appro);
    }
}
