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


- **Difficultés / Obstacles**
  - beaucoup de doutes concernant les attributs nullable obligatoire.
