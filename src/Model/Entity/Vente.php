<?php

require_once __DIR__ . '/Client.php';
require_once __DIR__ . '/Utilisateur.php';
require_once __DIR__ . '/LigneVente.php';

class Vente
{
    private ?int $id;
    private string $numeroFacture;
    private float $montantTotal;
    private float $montantVerse;
    private string $statut;
    private ?string $dateVente;
    private ?string $dateEcheance;

    private ?int $clientId;
    private ?Client $client = null;
    
    private ?int $utilisateurId;
    private ?Utilisateur $utilisateur = null;

    private array $lignes = [];

    public function __construct(string $numeroFacture, float $montantTotal = 0.0, float $montantVerse = 0.0, 
                                string $statut = 'PAYEE', ?string $dateEcheance = null, ?int $clientId = null, 
                                ?int $utilisateurId = null, ?int $id = null, ?string $dateVente = null
    ) {
        $this->id = $id;
        $this->numeroFacture = $numeroFacture;
        $this->montantTotal = $montantTotal;
        $this->montantVerse = $montantVerse;
        $this->statut = $statut;
        $this->dateEcheance = $dateEcheance;
        $this->clientId = $clientId;
        $this->utilisateurId = $utilisateurId;
        $this->dateVente = $dateVente;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNumeroFacture(): string
    {
        return $this->numeroFacture;
    }

    public function setNumeroFacture(string $numeroFacture): void
    {
        $this->numeroFacture = $numeroFacture;
    }

    public function getMontantTotal(): float
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(float $montantTotal): void
    {
        $this->montantTotal = max(0.0, $montantTotal);
    }

    public function getMontantVerse(): float
    {
        return $this->montantVerse;
    }

    public function setMontantVerse(float $montantVerse): void
    {
        $this->montantVerse = max(0.0, $montantVerse);
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function getDateVente(): ?string
    {
        return $this->dateVente;
    }

    public function setDateVente(?string $dateVente): void
    {
        $this->dateVente = $dateVente;
    }

    public function getDateEcheance(): ?string
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(?string $dateEcheance): void
    {
        $this->dateEcheance = $dateEcheance;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): void
    {
        $this->client = $client;
        if ($client !== null && $client->getId() !== null) {
            $this->clientId = $client->getId();
        }
    }

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateurId;
    }

    public function setUtilisateurId(?int $utilisateurId): void
    {
        $this->utilisateurId = $utilisateurId;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
        if ($utilisateur !== null && $utilisateur->getId() !== null) {
            $this->utilisateurId = $utilisateur->getId();
        }
    }

    public function getLignes(): array
    {
        return $this->lignes;
    }

    public function setLignes(array $lignes): void
    {
        $this->lignes = $lignes;
    }

    public function ajouterLigne(LigneVente $ligne): void
    {
        $this->lignes[] = $ligne;
    }

    public function getResteDu(): float
    {
        return max(0.0, $this->montantTotal - $this->montantVerse);
    }

    public function getMonnaieRendue(): float
    {
        return max(0.0, $this->montantVerse - $this->montantTotal);
    }
}
