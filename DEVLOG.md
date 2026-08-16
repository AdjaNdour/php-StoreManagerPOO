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
