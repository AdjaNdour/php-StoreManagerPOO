CREATE TABLE roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);

CREATE TABLE utilisateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    adresse TEXT,
    telephone TEXT,
    role_id INTEGER NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
);

CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    telephone TEXT NOT NULL,
    email TEXT,
    limite_credit REAL NOT NULL DEFAULT 0.00 CHECK (limite_credit >= 0)
);

CREATE TABLE fournisseurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    email TEXT,
    telephone TEXT NOT NULL,
    adresse TEXT
);

CREATE TABLE produits (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL UNIQUE,
    libelle TEXT NOT NULL,
    categorie TEXT NOT NULL,
    prix_vente REAL NOT NULL CHECK (prix_vente >= 0),
    cout_achat REAL NOT NULL DEFAULT 0.00 CHECK (cout_achat >= 0),
    stock_initial INTEGER NOT NULL DEFAULT 0 CHECK (stock_initial >= 0),
    seuil_alerte INTEGER NOT NULL DEFAULT 5 CHECK (seuil_alerte >= 0),
    fournisseur_id INTEGER,
    FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(id) ON DELETE SET NULL
);

CREATE TABLE ventes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_facture TEXT NOT NULL UNIQUE,
    montant_total REAL NOT NULL DEFAULT 0.00 CHECK (montant_total >= 0),
    montant_verse REAL NOT NULL DEFAULT 0.00 CHECK (montant_verse >= 0),
    statut TEXT NOT NULL DEFAULT 'PAYEE',
    date_vente DATE NOT NULL DEFAULT CURRENT_DATE,
    date_echeance DATE,
    client_id INTEGER,
    utilisateur_id INTEGER,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
);

CREATE TABLE lignes_vente (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    vente_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    quantite INTEGER NOT NULL CHECK (quantite > 0),
    prix_unitaire REAL NOT NULL CHECK (prix_unitaire >= 0),
    FOREIGN KEY (vente_id) REFERENCES ventes(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE RESTRICT
);

CREATE TABLE statuts_dette (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);

CREATE TABLE dettes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ref TEXT NOT NULL UNIQUE,
    montant_initial REAL NOT NULL CHECK (montant_initial > 0),
    montant_verse REAL NOT NULL DEFAULT 0.00 CHECK (montant_verse >= 0),
    montant_restant REAL NOT NULL CHECK (montant_restant >= 0),
    date_dette DATE NOT NULL DEFAULT CURRENT_DATE,
    date_echeance DATE,
    vente_id INTEGER NOT NULL UNIQUE,
    client_id INTEGER NOT NULL,
    statut_dette_id INTEGER NOT NULL,
    FOREIGN KEY (vente_id) REFERENCES ventes(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE RESTRICT,
    FOREIGN KEY (statut_dette_id) REFERENCES statuts_dette(id) ON DELETE RESTRICT
);

CREATE TABLE modes_paiement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);

CREATE TABLE paiements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant REAL NOT NULL CHECK (montant > 0),
    notes TEXT,
    date_paiement DATE NOT NULL DEFAULT CURRENT_DATE,
    dette_id INTEGER NOT NULL,
    mode_paiement_id INTEGER NOT NULL,
    utilisateur_id INTEGER,
    FOREIGN KEY (dette_id) REFERENCES dettes(id) ON DELETE CASCADE,
    FOREIGN KEY (mode_paiement_id) REFERENCES modes_paiement(id) ON DELETE RESTRICT,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
);

CREATE TABLE approvisionnements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reference_bl TEXT NOT NULL UNIQUE,
    cout_achat REAL NOT NULL DEFAULT 0.00 CHECK (cout_achat >= 0),
    date_appro DATE NOT NULL DEFAULT CURRENT_DATE,
    date_reception DATE,
    fournisseur_id INTEGER NOT NULL,
    utilisateur_id INTEGER,
    FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(id) ON DELETE RESTRICT,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
);

CREATE TABLE lignes_approvisionnement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    approvisionnement_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    quantite_appro INTEGER NOT NULL CHECK (quantite_appro > 0),
    quantite_recue INTEGER NOT NULL DEFAULT 0 CHECK (quantite_recue >= 0),
    prix_achat REAL NOT NULL CHECK (prix_achat >= 0),
    sous_total REAL NOT NULL CHECK (sous_total >= 0),
    FOREIGN KEY (approvisionnement_id) REFERENCES approvisionnements(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE RESTRICT
);
