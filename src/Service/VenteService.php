<?php

require_once dirname(__DIR__) . "/Model/Repository/VenteRepository.php";
require_once dirname(__DIR__) . "/Model/Repository/ProduitRepository.php";
require_once dirname(__DIR__) . "/Model/Repository/ClientRepository.php";

class VenteService
{
    private VenteRepository $repoVente;
    private ProduitRepository $repoProduit;
    private ClientRepository $repoClient;

    public function __construct()
    {
        $this->repoVente = new VenteRepository();
        $this->repoProduit = new ProduitRepository();
        $this->repoClient = new ClientRepository();
    }

    public function validerVente(Vente $vente): int
    {
        if ($vente->getClientId() === null || $vente->getClientId() <= 0) {
            throw new Exception(
                "Un client est obligatoire"
            );
        }

        if (!$this->verifierPanier($vente)) {
            throw new Exception("Le panier est vide ou invalide.");
        }

        $total = $this->calculerTotal($vente);

        $vente->setMontantTotal($total);

        if ($vente->getMontantVerse() < 0) {
            throw new Exception("Le montant versé ne peut pas être négatif.");
        }

        if ($vente->getMontantVerse() > $vente->getMontantTotal()) {
            throw new Exception(
                "Le montant versé ne peut pas être supérieur au montant total."
            );
        }

        if (!$this->verifierStock($vente)) {
            throw new Exception("Stock insuffisant.");
        }

        if (!$this->verifierLimiteCredit($vente)) {
            throw new Exception(
                "La limite de crédit du client est dépassée."
            );
        }

        return $this->repoVente->insert($vente);
    }
    //---------------------------------------------------------------------------------------------------------
    public function verifierPanier(Vente $vente): bool
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
    public function verifierStock(Vente $vente): bool
    {
        foreach ($vente->getLignes() as $ligne) {

            $stock = $this->repoProduit->getStock($ligne->getProduitId());
            if ($stock < $ligne->getQuantite()) {
                return false;
            }
        }
        return true;
    }
    //---------------------------------------------------------------------------------------------------------
    public function verifierLimiteCredit(Vente $vente): bool
    {
        $clientId = $vente->getClientId();
        $creditActuel = $this->repoClient->getColonneClient($clientId, 'credit');
        $limiteCredit = $this->repoClient->getColonneClient($clientId, 'limite_credit');
        $nouveauCredit = $creditActuel + $vente->getMontantTotal() - $vente->getMontantVerse();
        return $nouveauCredit <= $limiteCredit;
    }
    //---------------------------------------------------------------------------------------------------------
    public function calculerTotal(Vente $vente): float
    {
        $total = 0;

        foreach ($vente->getLignes() as $ligne) {
            $total += $ligne->getQuantite() * $ligne->getPrixUnitaire();
        }

        return $total;
    }

    public function getAll(){
        return $this->repoVente->selectAllVente();
    }


    public function getStatistiques(){
        return $this->repoVente->selectStatistiques();
    }

}
