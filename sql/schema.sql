CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE utilisateurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    adresse VARCHAR(255),
    telephone VARCHAR(50),
    role_id INT NOT NULL REFERENCES roles(id) ON DELETE RESTRICT
);

CREATE TABLE clients (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(50) NOT NULL,
    email VARCHAR(150),
    limite_credit NUMERIC(15, 2) NOT NULL DEFAULT 0.00 CHECK (limite_credit >= 0)
);

CREATE TABLE fournisseurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    email VARCHAR(150),
    telephone VARCHAR(50) NOT NULL,
    adresse VARCHAR(255)
);

CREATE TABLE produits (
    id SERIAL PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    libelle VARCHAR(150) NOT NULL,
    categorie VARCHAR(100) NOT NULL,
    prix_vente NUMERIC(15, 2) NOT NULL CHECK (prix_vente >= 0),
    cout_achat NUMERIC(15, 2) NOT NULL DEFAULT 0.00 CHECK (cout_achat >= 0),
    stock_initial INT NOT NULL DEFAULT 0 CHECK (stock_initial >= 0),
    seuil_alerte INT NOT NULL DEFAULT 5 CHECK (seuil_alerte >= 0),
    fournisseur_id INT REFERENCES fournisseurs(id) ON DELETE SET NULL
);

CREATE TABLE ventes (
    id SERIAL PRIMARY KEY,
    numero_facture VARCHAR(50) NOT NULL UNIQUE,
    montant_total NUMERIC(15, 2) NOT NULL DEFAULT 0.00 CHECK (montant_total >= 0),
    montant_verse NUMERIC(15, 2) NOT NULL DEFAULT 0.00 CHECK (montant_verse >= 0),
    statut VARCHAR(50) NOT NULL DEFAULT 'PAYEE',
    date_vente DATE NOT NULL DEFAULT CURRENT_DATE,
    date_echeance DATE,
    client_id INT REFERENCES clients(id) ON DELETE SET NULL,
    utilisateur_id INT REFERENCES utilisateurs(id) ON DELETE SET NULL
);

CREATE TABLE lignes_vente (
    id SERIAL PRIMARY KEY,
    vente_id INT NOT NULL REFERENCES ventes(id) ON DELETE CASCADE,
    produit_id INT NOT NULL REFERENCES produits(id) ON DELETE RESTRICT,
    quantite INT NOT NULL CHECK (quantite > 0),
    prix_unitaire NUMERIC(15, 2) NOT NULL CHECK (prix_unitaire >= 0)
);

CREATE TABLE statuts_dette (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE dettes (
    id SERIAL PRIMARY KEY,
    ref VARCHAR(50) NOT NULL UNIQUE,
    montant_initial NUMERIC(15, 2) NOT NULL CHECK (montant_initial > 0),
    montant_verse NUMERIC(15, 2) NOT NULL DEFAULT 0.00 CHECK (montant_verse >= 0),
    montant_restant NUMERIC(15, 2) NOT NULL CHECK (montant_restant >= 0),
    date_dette DATE NOT NULL DEFAULT CURRENT_DATE,
    date_echeance DATE,
    vente_id INT NOT NULL UNIQUE REFERENCES ventes(id) ON DELETE CASCADE,
    client_id INT NOT NULL REFERENCES clients(id) ON DELETE RESTRICT,
    statut_dette_id INT NOT NULL REFERENCES statuts_dette(id) ON DELETE RESTRICT
);

CREATE TABLE modes_paiement (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE paiements (
    id SERIAL PRIMARY KEY,
    montant NUMERIC(15, 2) NOT NULL CHECK (montant > 0),
    notes TEXT,
    date_paiement DATE NOT NULL DEFAULT CURRENT_DATE,
    dette_id INT NOT NULL REFERENCES dettes(id) ON DELETE CASCADE,
    mode_paiement_id INT NOT NULL REFERENCES modes_paiement(id) ON DELETE RESTRICT,
    utilisateur_id INT REFERENCES utilisateurs(id) ON DELETE SET NULL
);

CREATE TABLE approvisionnements (
    id SERIAL PRIMARY KEY,
    reference_bl VARCHAR(50) NOT NULL UNIQUE,
    cout_achat NUMERIC(15, 2) NOT NULL DEFAULT 0.00 CHECK (cout_achat >= 0),
    date_appro DATE NOT NULL DEFAULT CURRENT_DATE,
    date_reception DATE,
    fournisseur_id INT NOT NULL REFERENCES fournisseurs(id) ON DELETE RESTRICT,
    utilisateur_id INT REFERENCES utilisateurs(id) ON DELETE SET NULL
);

CREATE TABLE lignes_approvisionnement (
    id SERIAL PRIMARY KEY,
    approvisionnement_id INT NOT NULL REFERENCES approvisionnements(id) ON DELETE CASCADE,
    produit_id INT NOT NULL REFERENCES produits(id) ON DELETE RESTRICT,
    quantite_appro INT NOT NULL CHECK (quantite_appro > 0),
    quantite_recue INT NOT NULL DEFAULT 0 CHECK (quantite_recue >= 0),
    prix_achat NUMERIC(15, 2) NOT NULL CHECK (prix_achat >= 0),
    sous_total NUMERIC(15, 2) NOT NULL CHECK (sous_total >= 0)
);
