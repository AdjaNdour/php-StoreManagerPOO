<?php

require_once dirname(__DIR__) . "/Model/Repository/VenteRepository::hp";
require_once dirname(__DIR__) . "/Model/Repository/ProduitRepository.php";
require_once dirname(__DIR__) . "/Model/Repository/ClientRepository.php";

class VenteService
{
  
    public static function validerVente(Vente $vente): int
    {
        if ($vente->getClientId() === null || $vente->getClientId() <= 0) {
            throw new Exception(
                "Un client est obligatoire"
            );
        }

        if (!VenteService::verifierPanier($vente)) {
            throw new Exception("Le panier est vide ou invalide.");
        }

        $total = VenteService::calculerTotal($vente);

        $vente->setMontantTotal($total);

        if ($vente->getMontantVerse() < 0) {
            throw new Exception("Le montant versé ne peut pas être négatif.");
        }

        if ($vente->getMontantVerse() > $vente->getMontantTotal()) {
            throw new Exception(
                "Le montant versé ne peut pas être supérieur au montant total."
            );
        }

        if (!VenteService::verifierStock($vente)) {
            throw new Exception("Stock insuffisant.");
        }

        if (!VenteService::verifierLimiteCredit($vente)) {
            throw new Exception(
                "La limite de crédit du client est dépassée."
            );
        }

        return VenteRepository::insert($vente);
    }
    //---------------------------------------------------------------------------------------------------------
    public static function verifierPanier(Vente $vente): bool
    {
        if (empty($vente->getLignes())) {
            return false;
        }

        foreach ($vente->getLignes() as $ligne) {

            if ($ligne->getQuantite() <= 0) {
                return false;
            }

            if ($ligne->getPrixUnitaire() < 0) {
                return false;
            }
        }

        return true;
    }
    //---------------------------------------------------------------------------------------------------------
    public static function verifierStock(Vente $vente): bool
    {
        foreach ($vente->getLignes() as $ligne) {

            $stock = ProduitRepository::getStock($ligne->getProduitId());
            if ($stock < $ligne->getQuantite()) {
                return false;
            }
        }
        return true;
    }
    //---------------------------------------------------------------------------------------------------------
    public static function verifierLimiteCredit(Vente $vente): bool
    {
        $clientId = $vente->getClientId();
        $creditActuel = ClientRepository::getColonneClient($clientId, 'credit');
        $limiteCredit = ClientRepository::getColonneClient($clientId, 'limite_credit');
        $nouveauCredit = $creditActuel + $vente->getMontantTotal() - $vente->getMontantVerse();
        return $nouveauCredit <= $limiteCredit;
    }
    //---------------------------------------------------------------------------------------------------------
    public static function calculerTotal(Vente $vente): float
    {
        $total = 0;

        foreach ($vente->getLignes() as $ligne) {
            $total += $ligne->getQuantite() * $ligne->getPrixUnitaire();
        }

        return $total;
    }

    public static function getAll(){
        return VenteRepository::selectAllVente();
    }


    public static function getStatistiques(){
        return VenteRepository::selectStatistiques();
    }

}
