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

// insertions 

INSERT INTO roles (nom) VALUES
('admin'),
('vente'),
('stock'),
('inventaire');

INSERT INTO utilisateurs (nom, prenom, email, password, adresse, telephone, role_id) VALUES
('Admin', 'Boutique', 'admin@storemanager.sn', '$2y$10$demoHashAdminBoutiqueXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'Dakar, Sénégal', '338000001', 1),
('Chargé', 'Vente',    'vente@storemanager.sn', '$2y$10$demoHashChargeVenteXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'Dakar, Sénégal', '338000002', 2),
('Chargé', 'Stock',    'stock@storemanager.sn', '$2y$10$demoHashChargeStockXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'Dakar, Sénégal', '338000003', 3),
('Agent',  'Inventaire','inventaire@storemanager.sn', '$2y$10$demoHashInventaireXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'Dakar, Sénégal', '338000004', 4);

INSERT INTO clients (nom, prenom, telephone, email, limite_credit) VALUES
('Ndiaye',  'Abdou',    '776543210', 'abdou.ndiaye@mail.sn',   150000.00),
('Diouf',   'Fama',     '781234567', 'fama.diouf@mail.sn',     200000.00),
('Sarr',    'Moussa',   '769876543', 'moussa.sarr@mail.sn',    250000.00),
('Diallo',  'Maimouna', '701122334', 'maimouna.diallo@mail.sn',120000.00),
('Sow',     'Ousmane',  '775554433', 'ousmane.sow@mail.sn',    180000.00),
('Cisse',   'Awa',      '783332211', 'awa.cisse@mail.sn',      300000.00),
('Faye',    'Babacar',  '762221100', 'babacar.faye@mail.sn',   150000.00),
('Mbacke',  'Khady',    '704443322', 'khady.mbacke@mail.sn',   400000.00),
('Gueye',   'Ibrahima', '778887766', 'ibrahima.gueye@mail.sn', 100000.00),
('Fall',    'Fatou',    '789998877', 'fatou.fall@mail.sn',     250000.00);

INSERT INTO fournisseurs (nom, email, telephone, adresse) VALUES
('Comptoir Céréalier Sénégalais', 'contact@comptoircerealier.sn', '338245678', 'Port de Dakar, Hangar 4'),
('Grossiste Diop & Frères',       'contact@diopfreres.sn',        '773456789', 'Marché Grand Yoff, Lot B'),
('Sénégal Import-Export',         'contact@senimportexport.sn',   '338211010', 'Zone Industrielle de Hann');


INSERT INTO produits (code, libelle, categorie, prix_vente, cout_achat, stock_initial, seuil_alerte, fournisseur_id) VALUES
('PRD-RIZ50',   'Sac de riz 50kg',      'Céréales',          25000.00, 21000.00, 100, 20, 1),
('PRD-HUI5L',   'Bidon d''huile 5L',    'Huiles',             8000.00,  7000.00,   5, 10, 2),
('PRD-SAV01',   'Carton de savon',      'Hygiène',           12000.00, 12000.00,   3,  5, 2),
('PRD-SUC1KG',  'Paquet de sucre 1kg',  'Céréales',           1500.00,  1000.00, 200, 50, 3),
('PRD-LAIT01',  'Carton de lait',       'Produits Laitiers', 15000.00, 14000.00,  40, 20, 3),
('PRD-HUIP1L',  'Huile de palme 1L',    'Huiles',             2000.00,  1400.00,   0,  5, 2);

INSERT INTO ventes (numero_facture, montant_total, montant_verse, statut, date_vente, date_echeance, client_id, utilisateur_id) VALUES
('CMD-1', 58000.00, 58000.00, 'PAYEE',  '2026-08-01', NULL,         1, 2),
('CMD-2', 44000.00, 10000.00, 'AVANCE', '2026-08-07', '2026-09-06', 2, 2),
('CMD-3', 74000.00, 24000.00, 'AVANCE', '2026-08-07', '2026-09-06', 3, 2),
('CMD-4', 15000.00,     0.00, 'CREDIT', '2026-08-07', '2026-09-06', 4, 2);
INSERT INTO lignes_vente (vente_id, produit_id, quantite, prix_unitaire) VALUES
(1, 1, 2,  25000.00),  
(1, 2, 1,   8000.00),  
(2, 2, 3,   8000.00),  
(2, 4, 13,  1500.00),  
(3, 1, 2,  25000.00),  
(3, 3, 2,  12000.00),  
(4, 4, 10,  1500.00);  

INSERT INTO statuts_dette (nom) VALUES
('NON SOLDEE'),
('SOLDEE');

INSERT INTO dettes (ref, montant_initial, montant_verse, montant_restant, date_dette, date_echeance, vente_id, client_id, statut_dette_id) VALUES
('DT-1', 44000.00, 10000.00, 34000.00, '2026-08-07', '2026-09-06', 2, 2, 1),
('DT-2', 74000.00, 24000.00, 50000.00, '2026-08-07', '2026-09-06', 3, 3, 1),
('DT-3', 15000.00,     0.00, 15000.00, '2026-08-07', '2026-09-06', 4, 4, 1);

INSERT INTO modes_paiement (nom) VALUES
('Wave'),
('Orange Money'),
('Especes'),
('Virement');
INSERT INTO paiements (montant, notes, date_paiement, dette_id, mode_paiement_id, utilisateur_id) VALUES
(10000.00, 'Acompte versé à la création de la vente CMD-2', '2026-08-07', 1, 2, 2),
(24000.00, 'Acompte versé à la création de la vente CMD-3', '2026-08-07', 2, 1, 2);

INSERT INTO approvisionnements (reference_bl, cout_achat, date_appro, date_reception, fournisseur_id, utilisateur_id) VALUES
('BL-CCS-098', 4200000.00, '2026-07-20', '2026-07-25', 1, 3),  
('BL-DIP-099',  320000.00, '2026-07-28', '2026-08-02', 2, 3),  
('BL-CCS-101',  525000.00, '2026-08-06', NULL,         1, 3),  
('BL-SEN-102',  190000.00, '2026-08-07', NULL,         3, 3);  
INSERT INTO lignes_approvisionnement (approvisionnement_id, produit_id, quantite_appro, quantite_recue, prix_achat, sous_total) VALUES
(1, 1, 200, 200, 21000.00, 4200000.00),  
(2, 2,  20,  20,  7000.00,  140000.00),  
(2, 3,  15,  15, 12000.00,  180000.00),  
(3, 1,  25,   0, 21000.00,  525000.00),  
(4, 4,  50,   0,  1000.00,   50000.00),  
(4, 5,  10,   0, 14000.00,  140000.00);  


--requatte de recuperation des ventes plus leur produit et le client

SELECT  v.id AS vente_id, v.numero_facture, v.date_vente, v.statut, v.montant_total,
                        v.montant_verse, v.date_echeance,v.utilisateur_id,
                        c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone,
                        p.id AS produit_id,p.libelle AS produit_libelle,
                        lv.quantite,lv.prix_unitaire,(lv.quantite * lv.prix_unitaire) AS sous_total
                FROM ventes v
                JOIN clients c ON c.id = v.client_id
                JOIN lignes_vente lv ON lv.vente_id = v.id
                JOIN produits p ON p.id = lv.produit_id
                ORDER BY v.id