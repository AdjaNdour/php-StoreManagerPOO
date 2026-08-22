<?php

namespace App\Model\Entity;

use stdClass;

class Vente
{
    private ?int $id;
    private string $numeroFacture;
    private float $montantTotal;
    private float $montantVerse;
    private string $statut;
    private ?string $dateVente;
    private ?string $dateEcheance;
    private ?int $modePaiementId;
    private ?ModePaiement $modePaiement = null;
    private Client $client;
    private ?Utilisateur $utilisateur = null;
    private array $lignes = [];

    public function __construct(
        Client $client,
        string $numeroFacture,
        float $montantTotal = 0.0,
        float $montantVerse = 0.0,
        string $statut = 'PAYEE',
        ?string $dateEcheance = null,
        ?Utilisateur $utilisateur = null,
        ?int $id = null,
        ?string $dateVente = null,
        ?int $modePaiementId = null
    ) {
        $this->id = $id;
        $this->numeroFacture = $numeroFacture;
        $this->montantTotal = $montantTotal;
        $this->montantVerse = $montantVerse;
        $this->statut = $statut;
        $this->dateEcheance = $dateEcheance;
        $this->client = $client;
        $this->utilisateur = $utilisateur;
        $this->dateVente = $dateVente;
        $this->modePaiementId = $modePaiementId;
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

    public function getMontantRestant(): float
    {
        return max(0.0, $this->montantTotal - $this->montantVerse);
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

    public function getClientId(): int
    {
        return $this->client->getId() ?? 0;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateur?->getId();
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

    public function setModePaiementId(?int $modePaiementId): void
    {
        $this->modePaiementId = $modePaiementId;
    }

    public function getModePaiementId(): ?int
    {
        return $this->modePaiementId;
    }

    public function getModePaiement(): ?ModePaiement
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?ModePaiement $modePaiement): void
    {
        $this->modePaiement = $modePaiement;
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

    public static function toEntity(stdClass $obj): self
    {
        $id = $obj->vente_id ?? $obj->id ?? null;
        $numFacture = $obj->numero_facture ?? '';
        $total = $obj->montant_total ?? 0;
        $verse = $obj->montant_verse ?? 0;
        $statut = $obj->statut ?? 'PAYEE';
        $dateVente = $obj->date_vente ?? null;
        $dateEcheance = $obj->date_echeance ?? null;
        $modeId = $obj->mode_paiement_id ?? null;

        $client = Client::toEntity($obj);

        $hasUser = isset($obj->nom_utilisateur) || isset($obj->utilisateur_id);
        $utilisateur = $hasUser ? Utilisateur::toEntity($obj) : null;

        $vente = new self(
            client: $client,
            numeroFacture: (string)$numFacture,
            montantTotal: (float)$total,
            montantVerse: (float)$verse,
            statut: (string)$statut,
            dateEcheance: $dateEcheance ? (string)$dateEcheance : null,
            utilisateur: $utilisateur,
            id: $id !== null ? (int)$id : null,
            dateVente: $dateVente ? (string)$dateVente : null,
            modePaiementId: $modeId !== null ? (int)$modeId : null
        );

        $hasMode = isset($obj->mode_paiement_nom) || isset($obj->mode_nom);
        if ($hasMode) {
            $vente->setModePaiement(ModePaiement::toEntity($obj));
        }

        return $vente;
    }
}
