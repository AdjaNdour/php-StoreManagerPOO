<?php

namespace App\Service;

use App\Model\Repository\VenteRepository;
use App\Model\Repository\ProduitRepository;
use App\Model\Repository\ClientRepository;
use App\Model\Entity\Vente;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;
use App\Core\Database;
use Exception;

class VenteService
{
    public static function getAllVentesFiltered(FilteredModel $filtered, PaginationModel $pagination): array
    {
        return VenteRepository::selectAllVentesFiltered($filtered, $pagination);
    }

    public static function getAll(): array
    {
        return VenteRepository::selectAllVente();
    }

    public static function getAllVente(): array
    {
        return VenteRepository::selectAllVente();
    }

    public static function getById(int $id): ?Vente
    {
        return VenteRepository::selectById($id);
    }

    public static function getByNumeroFacture(string $numeroFacture): ?Vente
    {
        return VenteRepository::selectByNumeroFacture($numeroFacture);
    }

    public static function getLignesByVenteId(int $venteId): array
    {
        return VenteRepository::selectLignesByVenteId($venteId);
    }

    public static function getStatistiques(): object
    {
        return VenteRepository::selectStatistiques();
    }

    public static function genererNumeroFacture(): string
    {
        $id = Database::getLastId("ventes") + 1;
        return "FAC-" . date('Ymd') . "-" . str_pad((string)$id, 4, '0', STR_PAD_LEFT);
    }

    public static function validerVente(Vente $vente): int
    {
        if ($vente->getClientId() <= 0) {
            throw new Exception("Un client est obligatoire.");
        }

        if (!self::verifierPanier($vente)) {
            throw new Exception("Le panier est vide ou invalide.");
        }

        $total = self::calculerTotal($vente);
        $vente->setMontantTotal($total);

        if ($vente->getMontantVerse() < 0) {
            throw new Exception("Le montant versé ne peut pas être négatif.");
        }

        if ($vente->getMontantVerse() > $vente->getMontantTotal()) {
            throw new Exception("Le montant versé ne peut pas être supérieur au montant total.");
        }

        if (!self::verifierStock($vente)) {
            throw new Exception("Stock insuffisant pour un ou plusieurs articles.");
        }

        if (!self::verifierLimiteCredit($vente)) {
            throw new Exception("La limite de crédit du client est dépassée.");
        }

        return VenteRepository::insert($vente);
    }

    public static function enregistrerVente(Vente $vente): int
    {
        return self::validerVente($vente);
    }

    public static function save(Vente $vente): int
    {
        return self::validerVente($vente);
    }

    public static function verifierPanier(Vente $vente): bool
    {
        $lignes = $vente->getLignes();
        if (empty($lignes)) {
            return false;
        }

        foreach ($lignes as $ligne) {
            if ($ligne->getQuantite() <= 0 || $ligne->getPrixUnitaire() < 0) {
                return false;
            }
        }

        return true;
    }

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

    public static function verifierLimiteCredit(Vente $vente): bool
    {
        $clientId = $vente->getClientId();
        $creditActuel = (float)ClientRepository::getColonneClient($clientId, 'credit');
        $limiteCredit = (float)ClientRepository::getColonneClient($clientId, 'limite_credit');

        $resteAPayer = $vente->getMontantTotal() - $vente->getMontantVerse();
        if ($resteAPayer <= 0) {
            return true;
        }

        $nouveauCredit = $creditActuel + $resteAPayer;
        return $nouveauCredit <= $limiteCredit;
    }

    public static function calculerTotal(Vente $vente): float
    {
        $total = 0.0;
        foreach ($vente->getLignes() as $ligne) {
            $total += $ligne->getSousTotal();
        }
        return $total;
    }
}
