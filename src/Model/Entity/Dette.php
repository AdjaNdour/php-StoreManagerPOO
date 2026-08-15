<?php

require_once __DIR__ . '/Client.php';
require_once __DIR__ . '/Vente.php';
require_once __DIR__ . '/StatutDette.php';
require_once __DIR__ . '/Paiement.php';

class Dette
{
    private ?int $id;
    private string $ref;
    private float $montantInitial;
    private float $montantVerse;
    private float $montantRestant;
    private ?string $dateDette;
    private ?string $dateEcheance;

    private int $venteId;
    private ?Vente $vente = null;

    private int $clientId;
    private ?Client $client = null;
    
    private int $statutDetteId;
    private ?StatutDette $statutDette = null;
    
    private array $paiements = [];

    public function __construct( string $ref, int $venteId, int $clientId, int $statutDetteId, 
        float $montantInitial, float $montantVerse = 0.0, ?float $montantRestant = null, 
        ?string $dateEcheance = null, ?int $id = null, ?string $dateDette = null
    ) {
        $this->id = $id;
        $this->ref = $ref;
        $this->venteId = $venteId;
        $this->clientId = $clientId;
        $this->statutDetteId = $statutDetteId;
        $this->montantInitial = $montantInitial;
        $this->montantVerse = $montantVerse;
        $this->montantRestant = $montantRestant ?? max(0.0, $montantInitial - $montantVerse);
        $this->dateEcheance = $dateEcheance;
        $this->dateDette = $dateDette;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getRef(): string
    {
        return $this->ref;
    }

    public function setRef(string $ref): void
    {
        $this->ref = $ref;
    }

    public function getMontantInitial(): float
    {
        return $this->montantInitial;
    }

    public function setMontantInitial(float $montantInitial): void
    {
        $this->montantInitial = max(0.0, $montantInitial);
    }

    public function getMontantVerse(): float
    {
        return $this->montantVerse;
    }

    public function setMontantVerse(float $montantVerse): void
    {
        $this->montantVerse = max(0.0, $montantVerse);
        $this->montantRestant = max(0.0, $this->montantInitial - $this->montantVerse);
    }

    public function getMontantRestant(): float
    {
        return $this->montantRestant;
    }

    public function setMontantRestant(float $montantRestant): void
    {
        $this->montantRestant = max(0.0, $montantRestant);
    }

    public function getDateDette(): ?string
    {
        return $this->dateDette;
    }

    public function setDateDette(?string $dateDette): void
    {
        $this->dateDette = $dateDette;
    }

    public function getDateEcheance(): ?string
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(?string $dateEcheance): void
    {
        $this->dateEcheance = $dateEcheance;
    }

    public function getVenteId(): int
    {
        return $this->venteId;
    }

    public function setVenteId(int $venteId): void
    {
        $this->venteId = $venteId;
    }

    public function getVente(): ?Vente
    {
        return $this->vente;
    }

    public function setVente(?Vente $vente): void
    {
        $this->vente = $vente;
        if ($vente !== null && $vente->getId() !== null) {
            $this->venteId = $vente->getId();
        }
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId): void
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

    public function getStatutDetteId(): int
    {
        return $this->statutDetteId;
    }

    public function setStatutDetteId(int $statutDetteId): void
    {
        $this->statutDetteId = $statutDetteId;
    }

    public function getStatutDette(): ?StatutDette
    {
        return $this->statutDette;
    }

    public function setStatutDette(?StatutDette $statutDette): void
    {
        $this->statutDette = $statutDette;
        if ($statutDette !== null && $statutDette->getId() !== null) {
            $this->statutDetteId = $statutDette->getId();
        }
    }

    public function getPaiements(): array
    {
        return $this->paiements;
    }

    public function setPaiements(array $paiements): void
    {
        $this->paiements = $paiements;
    }

    public function ajouterPaiement(Paiement $paiement): void
    {
        $this->paiements[] = $paiement;
    }

    public function enregistrerReglement(float $montant): void
    {
        if ($montant <= 0) {
            throw new Exception("Le montant du règlement doit être supérieur à zéro");
        }
        if ($montant > $this->montantRestant) {
            throw new Exception("Le montant réglé dépasse le reste dû");
        }

        $this->montantVerse += $montant;
        $this->montantRestant = max(0.0, $this->montantInitial - $this->montantVerse);
    }

    public function estSoldee(): bool
    {
        return $this->montantRestant <= 0.0;
    }

    public function estEnRetard(): bool
    {
        if ($this->estSoldee() || empty($this->dateEcheance)) {
            return false;
        }
        return strtotime($this->dateEcheance) < strtotime(date('Y-m-d'));
    }
}
