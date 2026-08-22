# 📓 Journal de Développement (DEVLOG)
**Nom & Prénom** : Adja Coura Ndour 
**Projet** : StoreManager Pro (ERP PHP/POO & Trésorerie)  

---

## 1. Suivi Chronologique des Phases

### 🌃 [Vendredi - Phase 1] : Conception & BDD Fallback
- **Heure de réalisation** : 19h00 - 23h00
- **Ce qui a été fait** : 
  - Réalisation des diagrammes de use case et de classe (`docs/use_case_admin.puml`,
                                                        `docs/use_case_inventaire.puml`,
                                                        `docs/use_case_stock.puml`,
                                                        `docs/use_case_vendeur.puml`,
                                                        `docs/class_diagram.puml`).
  - Définition des 4 acteurs (*Admin*, *Vente*, *Stock*, *Inventaire*) et de leurs cas d'utilisation respectifs.
  - representation des UC principaux et des UC interne les relations entre eux avec extend et include.
  - representation du diagramme de classe, des diferentes classe qu'on peut avoir leur attributs leur relations 
    et multiplicite.

  - Création des scripts d'initialisation de base de données relationnelle avec contraintes d'intégrité, clés étrangères
    et vérifications (`sql/schema.sql` pour PostgreSQL et `sql/schema_sqlite.sql` pour SQLite).

  - Implémentation de la classe Singleton `Database.php` se trouvant dans src/Core intégrant un mécanisme de bascule
    automatique (*fallback try catch*) de PostgreSQL vers SQLite (`erp.db`) en cas d'indisponibilité du serveur SQL principal.

- **Difficultés / Obstacles**
  - il y'a beaucoup trop d'informations à prendre en compte.

  - il n'ya pas trop de difficulte pour postgres et pour sqlite c'est presque la meme chose juste quelques changement
    comme VARCHAR qui devient TEXT et SERIAL qui devient AUTOINCREMENT.


---

### ☀️ [Samedi - Phase 2] : POO, Services & Ventes POS
- **Heure de réalisation** : 09h00 - 20h00
- **Ce qui a été fait** :
  - Création des entités avec encapsulation (attributs privés, les methodes accesseurs et le constructeurs) : `Produit.php`, `Client.php`, `Fournisseur.php`, `Vente.php`, `LigneVente.php`, `Dette.php`, `Paiement.php`, `Approvisionnement.php`, `LigneApprovisionnement.php`, `Utilisateur.php`, `Role.php`, `ModePaiement.php`, `StatutDette.php`.

  - implementation de 3 repository : `ProduitRepository.php`, `ClientRepository.php`, `FournisseurRepository.php` au niveau du dossier Model/Repository avec leur fonction de base: CRUD: insert(), selctAll(), selectById(), delete() et update() + une fonction reutilisable appelée toObjet() qui transforme les donnees retournees par la requete en objet correspondant a mes entites.

  - Realisation de VenteService validerVente() avec gestion et validation du panier,
  contrôle de l’existence du client, calcul du montant total,
  vérification de la disponibilite du stock et contrôle de la limite de crédit du client. 
  Lors de l'enregistrement de la vente si le montant verse est inferieur au montant total de la vente, une dette est automatiquement créée.
  La sauvegarde de la vente, de sa dette, de ses lignes et la décrémentation du stock sont réalisées dans une transaction avec beginTransaction, commit et rollback en cas d'erreur (annulation de tous).
  Ajout de quelques fonctions dans les repos (produit et client).

  - Construction du contrôleur `POSController.php` et de la vue `views/pos/index.php` .

- **Difficultés / Obstacles**
  - beaucoup de doutes concernant les attributs nullable obligatoire.

  - comprehension de la separation Service/Repository, gestion des transactions pdo et du rollback et comprehension du mécanisme des exceptions (throw, catch, $e->getMessage()).
  la coherence entres les fonctions et leur appel et les test de verification qui doivent etre faites.
  beaucoup de temps dans strucuration de ma fonctionalites valider vente (concernant le service et le repository)

---

### 🚀 [Dimanche - Phase 3] : Dettes, Approvisionnements, Auth, Dashboard & Finalisation
- **Heure de réalisation** : 08h30 - 18h30
- **Ce qui a été fait** :
  - **Gestion des Dettes & Recouvrements** :
    - Création de `DetteRepository.php` et `PaiementRepository.php` avec transactions atomiques pour les versements.
    - Création de `DebtService.php` pour le chargement des statistiques de créances des clients et recouvrement, recuperations de dettesActives
    - Création du contrôleur `DetteController.php` et de la vue `src/Views/dettes/index.php` avec 2 drawers interactifs ( Historique des paiements, Formulaire de versement rapide).

- **Difficultés / Obstacles** :
  - tourjours la meme chose : trop d'informations en prendre en compte sans lesquelles y'aurais pas de coherence.




## 2. Autopsie de 3 Méthodes Clés

### 🔬 Méthode 1 : `Database::connexionDB()` / Singleton avec Mécanisme de Fallback
- **Fichier** : `src/Core/Database.php`
- **Rôle** : Fournir une instance unique de connexion PDO à l'ensemble de l'ERP via le patron de conception Singleton
    avec bascule automatique de PostgreSQL vers SQLite local (`erp.db`) en cas de panne de serveur.
- **Fonctionnement interne** :
  -La méthode vérifie si `self::$pdo` est déjà instancié pour éviter toute reconnexion inutile.
   Dans un premier bloc `try`, elle tente la connexion à PostgreSQL avec les identifiants configurés.
   Si PostgreSQL échoue (panne réseau, service arrêté), le bloc `catch` prend le relais instantanément et instancie un connecteur PDO SQLite vers `erp.db`.
   L'attribut statique `$driver` mémorise le driver actif (`pgsql` ou `sqlite`) pour ajuster dynamiquement certaines requêtes si nécessaire.

### 🔬 Méthode 2 : `VenteService::validerVente()` 
- **Fichier** : `src/Service/VenteService.php`
- **Rôle** : enregistrer une vente 
- **Fonctionnement interne** :
  - Validation du panier : Vérifie que le panier contient au moins un produit valide et que le client sélectionné existe bien dans la base.
  - Contrôle des stocks : Vérifie pour chaque ligne que la quantité demandée est inférieure ou égale au stock physique disponible.
  - Contrôle de solvabilité : Calcule le reste dû éventuel (`montant_total - montant_verse`). Si une dette est générée, vérifie qu'elle ne dépasse pas la limite de crédit accordée au client.
  - Transaction :
     - Enregistrement de la vente dans la table `ventes`.
     - parcourrir les lignes de l'approvisionnenement et inserer de toutes les lignes dans `lignes_vente`.
     - decrementer immédiatemenent le stock du `produits` produit concerne .
     - Si `montant_verse < montant_total`, creer automatiquement une dette dans la table `dettes`.
     - `commit()` si tout réussit ; en cas d'erreur, `rollBack()` intégral pour éviter l'incoherence.

### 🔬 Méthode 3 : `PaiementRepository::enregistrerPaiement()` 
- **Fichier** : `src/Model/Repository/DetteRepository.php`
- **Rôle** : Enregistrer un remboursement partiel ou total d'une dette client tout en maintenant la cohérence 
- **Fonctionnement interne** :
  - Démarre une transaction PDO (`beginTransaction()`) et verrouille la ligne de dette concernée.
  - Vérifie que le montant du versement ne dépasse pas le solde débiteur restant.
  - Insère un enregistrement dans la table `paiements` avec le canal sélectionné (Wave, Orange Money, Espèces, Virement) et l'utilisateur connecté.
  - Recalcule `montant_verse` et `montant_restant`.
  - Met à jour le statut de la dette : `SOLDEE` si `montant_restant <= 0`, sinon `NON SOLDEE`.
  - Si la dette est totalement soldée, met automatiquement à jour le statut de la commande liée dans `ventes` à `PAYEE`.
  - Valide la transaction avec `commit()`.
