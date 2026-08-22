<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StoreManager | ERP Tactical Workspace</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b0f19;
            --panel-bg: rgba(22, 30, 49, 0.65);
            --border-color: rgba(45, 212, 191, 0.12);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #2dd4bf;
            --accent-glow: rgba(45, 212, 191, 0.1);
            --success: #34d399;
            --danger: #f87171;
            --warning: #fbbf24;
            --font-family: 'Plus Jakarta Sans', sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: var(--font-family);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 0;
            margin: 0;
            overflow-x: hidden;
        }

        .app-container {
            width: 100%;
            max-width: 100%;
            padding: 24px;
        }

        /* Top Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(8, 12, 24, 0.7);
            border: 1px solid var(--border-color);
            padding: 16px 24px;
            border-radius: 20px;
            margin-bottom: 24px;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }

        .nav-logo { font-size: 20px; font-weight: 800; display: flex; align-items: center; gap: 10px; }
        .nav-logo span { color: var(--accent); }

        .nav-menu { display: flex; gap: 8px; }
        .nav-item {
            background: transparent;
            border: 1px solid transparent;
            color: var(--text-muted);
            padding: 10px 18px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .nav-item:hover, .nav-item.active {
            background: var(--accent-glow);
            color: var(--accent);
            border-color: var(--accent);
        }

        /* Premium Toast notification structure */
        .toast-box {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast {
            background: rgba(13, 20, 38, 0.9);
            border: 1px solid var(--border-color);
            padding: 16px 24px;
            border-radius: 16px;
            color: white;
            font-size: 13px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease forwards;
        }
        .toast.success { border-left: 4px solid var(--success); }
        .toast.danger { border-left: 4px solid var(--danger); }

        /* Badges styles */
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge.payee { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .badge.non-payee { background: rgba(244, 63, 94, 0.1); color: var(--danger); }
        .badge.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }

        @keyframes slideIn {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* KPI Dashboard Radial charts */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        .kpi-card {
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }

        .kpi-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.03) 0%, transparent 80%);
            pointer-events: none;
        }

        .kpi-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 6px; letter-spacing: 0.5px; }
        .kpi-val { font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }

        /* Radial Progress Ring */
        .progress-ring-container { position: relative; width: 60px; height: 60px; }
        .progress-ring { transform: rotate(-90deg); }
        .progress-ring-circle-bg { fill: transparent; stroke: rgba(255,255,255,0.03); stroke-width: 6; }
        .progress-ring-circle { fill: transparent; stroke: var(--accent); stroke-width: 6; stroke-dasharray: 157; stroke-dashoffset: 60; stroke-linecap: round; transition: stroke-dashoffset 0.35s; }

        /* Layout panels */
        .panel-card {
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
            margin-bottom: 24px;
        }

        .panel-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
            border-left: 4px solid var(--accent);
            padding-left: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Search inputs inside tables headers */
        .search-control {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 6px 12px;
            color: white;
            font-size: 12px;
            outline: none;
            font-family: var(--font-family);
            width: 220px;
        }
        .search-control:focus { border-color: var(--accent); }

        /* Tactile Numerical Keypad panel */
        .keypad-container {
            background: #090e1a;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 12px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 12px;
            max-width: 280px;
            display: none; /* Dynamic slide down */
            animation: fadeIn 0.2s ease;
        }

        .keypad-btn {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: 700;
            padding: 12px 0;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }
        .keypad-btn:hover { background: var(--accent-glow); color: var(--accent); }
        .keypad-btn:active { transform: scale(0.95); }

        /* Forms controls */
        .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; position: relative; }
        .form-group label { font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        
        .form-control {
            background: rgba(8, 12, 24, 0.7);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 14px 18px;
            color: white;
            font-family: var(--font-family);
            outline: none;
            font-size: 13px;
            transition: all 0.3s;
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 12px rgba(59, 130, 246, 0.1); }

        /* Submit elements */
        .btn-submit {
            background: linear-gradient(135deg, var(--accent) 0%, #0d9488 100%);
            color: #0b0f19;
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-weight: 800;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(45, 212, 191, 0.3); }
        .btn-submit.btn-success { background: linear-gradient(135deg, var(--success) 0%, #059669 100%); color: white; }
        .btn-submit.btn-success:hover { box-shadow: 0 8px 20px rgba(52, 211, 153, 0.3); }

        /* Tables & Lists */
        .debt-table { width: 100%; border-collapse: collapse; text-align: left; }
        .debt-table th {
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
        }
        .debt-table td { padding: 14px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.03); font-size: 13px; }

        .btn-quick-action {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-quick-action:hover { background: var(--accent-glow); border-color: var(--accent); color: var(--accent); }

        .details-drawer {
            display: none;
            background: rgba(255,255,255,0.012);
            border: 1px solid rgba(255,255,255,0.03);
            border-radius: 16px;
            padding: 20px;
            margin-top: 10px;
            animation: fadeIn 0.3s ease;
        }

        .view-section { display: none; }
        .active-view { display: block; }

        /* Hide HTML5 Up/Down Spinners */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Écran de Connexion Moderne Split-Screen (Harmonisé aux couleurs Dark/Teal de StoreManager Pro) -->
    <div id="login-screen" style="position: fixed; inset: 0; background: var(--bg-color); z-index: 9999; display: grid; grid-template-columns: 1.1fr 1fr; font-family: var(--font-family); color: var(--text-main);">
        
        <!-- Colonne Gauche : Branding & Visual Hero (Fond Sombre Deep Navy / Teal Accent) -->
        <div style="background: linear-gradient(135deg, #0b0f19 0%, #111827 50%, #0d1b2a 100%); border-right: 1px solid var(--border-color); padding: 48px; display: flex; flex-direction: column; justify-content: space-between; color: var(--text-main); position: relative; overflow: hidden;">
            <!-- Cercles décoratifs en arrière-plan avec l'accent Cyan/Teal -->
            <div style="position: absolute; width: 650px; height: 650px; border-radius: 50%; border: 1px solid rgba(45, 212, 191, 0.08); bottom: -200px; left: -100px; pointer-events: none;"></div>
            <div style="position: absolute; width: 450px; height: 450px; border-radius: 50%; border: 1px solid rgba(45, 212, 191, 0.12); bottom: -100px; left: 0px; pointer-events: none;"></div>
            <div style="position: absolute; width: 250px; height: 250px; border-radius: 50%; background: radial-gradient(circle, rgba(45, 212, 191, 0.15) 0%, transparent 70%); top: 20%; right: 10%; pointer-events: none;"></div>

            <!-- Header Brand Logo -->
            <div style="display: flex; align-items: center; gap: 12px; z-index: 2;">
                <div style="background: rgba(22, 30, 49, 0.8); border: 1px solid var(--border-color); backdrop-filter: blur(12px); padding: 10px 20px; border-radius: 14px; display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    <span style="font-size: 26px;">📦</span>
                    <div>
                        <div style="font-weight: 800; color: var(--accent); font-size: 17px; line-height: 1.1; letter-spacing: -0.5px;">StoreManager Pro</div>
                        <div style="font-size: 9px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Gérez aujourd'hui, réussissez demain.</div>
                    </div>
                </div>
            </div>

            <!-- Content Hero Text -->
            <div style="max-width: 520px; z-index: 2; margin: 60px 0;">
                <div style="display: inline-block; background: var(--accent-glow); border: 1px solid var(--accent); border-radius: 20px; padding: 6px 14px; font-size: 11px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 24px; color: var(--accent);">
                    COMMERCE • SÉNÉGAL
                </div>
                <h1 style="font-size: 42px; font-weight: 800; line-height: 1.15; margin-bottom: 20px; color: #ffffff; letter-spacing: -1px;">
                    Une boutique mieux pilotée,<br>
                    <span style="color: var(--accent); text-shadow: 0 0 20px rgba(45, 212, 191, 0.3);">une rentabilité optimisée.</span>
                </h1>
                <p style="font-size: 15px; color: var(--text-muted); line-height: 1.6; margin-bottom: 32px; font-weight: 400;">
                    Ventes, stock, dettes clients et suivi fournisseurs réunis dans un espace clair, rapide et taillé pour le commerce moderne.
                </p>

                <!-- Badge démo -->
                <div style="background: rgba(22, 30, 49, 0.6); border: 1px solid var(--border-color); border-radius: 14px; padding: 14px 18px; display: flex; align-items: center; gap: 14px; width: fit-content;">
                    <div style="width: 36px; height: 36px; background: var(--accent-glow); border: 1px solid var(--accent); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 18px;">🛡️</div>
                    <div>
                        <div style="font-size: 12px; font-weight: 700; color: #ffffff;">Espace de démonstration sécurisé</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Sélectionnez un profil pour tester instantanément.</div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div style="font-size: 11px; color: var(--text-muted); z-index: 2;">
                Conçu pour les commerces et boutiques au Sénégal.
            </div>
        </div>

        <!-- Colonne Droite : Formulaire & Sélection de Profil (Thème Sombre Ergonomique) -->
        <div style="background: #0f1523; padding: 48px 64px; display: flex; flex-direction: column; justify-content: center; overflow-y: auto; color: var(--text-main);">
            <div style="max-width: 520px; width: 100%; margin: 0 auto;">
                
                <div style="font-size: 11px; font-weight: 800; color: var(--accent); letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 6px;">
                    RAVI DE VOUS REVOIR
                </div>
                <h2 style="font-size: 30px; font-weight: 800; color: #ffffff; margin-bottom: 8px;">
                    Connexion à StoreManager
                </h2>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 28px;">
                    Choisissez un profil de démonstration ou saisissez vos identifiants.
                </p>

                <!-- Grille des cartes de profils rapides -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
                    <!-- Profil 1: Admin -->
                    <div onclick="selectQuickProfile('admin', 'admin@storemanager.sn', '👑 Admin Boutique')" class="quick-profile-card active" id="profile-card-admin" style="background: rgba(22, 30, 49, 0.7); border: 2px solid var(--accent); border-radius: 14px; padding: 14px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                        <div style="width: 40px; height: 40px; background: rgba(45, 212, 191, 0.15); border: 1px solid var(--accent); border-radius: 10px; font-size: 12px; font-weight: 800; color: var(--accent); display: flex; align-items: center; justify-content: center;">AB</div>
                        <div>
                            <div style="font-weight: 700; font-size: 13px; color: #ffffff;">Admin Boutique</div>
                            <div style="font-size: 10px; color: var(--text-muted);">Pilotage complet</div>
                        </div>
                    </div>

                    <!-- Profil 2: Chargé de Vente -->
                    <div onclick="selectQuickProfile('vente', 'vente@storemanager.sn', '🛒 Chargé de Vente')" class="quick-profile-card" id="profile-card-vente" style="background: rgba(22, 30, 49, 0.4); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 14px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: all 0.2s;">
                        <div style="width: 40px; height: 40px; background: rgba(56, 189, 248, 0.15); border: 1px solid #38bdf8; border-radius: 10px; font-size: 12px; font-weight: 800; color: #38bdf8; display: flex; align-items: center; justify-content: center;">CV</div>
                        <div>
                            <div style="font-weight: 700; font-size: 13px; color: #ffffff;">Chargé de Vente</div>
                            <div style="font-size: 10px; color: var(--text-muted);">Caisse & Dettes</div>
                        </div>
                    </div>

                    <!-- Profil 3: Chargé de Stock -->
                    <div onclick="selectQuickProfile('stock', 'stock@storemanager.sn', '📦 Chargé de Stock')" class="quick-profile-card" id="profile-card-stock" style="background: rgba(22, 30, 49, 0.4); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 14px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: all 0.2s;">
                        <div style="width: 40px; height: 40px; background: rgba(251, 191, 36, 0.15); border: 1px solid var(--warning); border-radius: 10px; font-size: 12px; font-weight: 800; color: var(--warning); display: flex; align-items: center; justify-content: center;">CS</div>
                        <div>
                            <div style="font-weight: 700; font-size: 13px; color: #ffffff;">Chargé de Stock</div>
                            <div style="font-size: 10px; color: var(--text-muted);">Appro & Réception</div>
                        </div>
                    </div>

                    <!-- Profil 4: Inventaire -->
                    <div onclick="selectQuickProfile('inventaire', 'inventaire@storemanager.sn', '📋 Inventaire')" class="quick-profile-card" id="profile-card-inventaire" style="background: rgba(22, 30, 49, 0.4); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 14px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: all 0.2s;">
                        <div style="width: 40px; height: 40px; background: rgba(192, 132, 252, 0.15); border: 1px solid #c084fc; border-radius: 10px; font-size: 12px; font-weight: 800; color: #c084fc; display: flex; align-items: center; justify-content: center;">IV</div>
                        <div>
                            <div style="font-weight: 700; font-size: 13px; color: #ffffff;">Inventaire</div>
                            <div style="font-size: 10px; color: var(--text-muted);">Consultation produits</div>
                        </div>
                    </div>
                </div>

                <!-- Séparateur -->
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                    <div style="flex: 1; height: 1px; background: rgba(255,255,255,0.08);"></div>
                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 500;">ou avec vos identifiants</div>
                    <div style="flex: 1; height: 1px; background: rgba(255,255,255,0.08);"></div>
                </div>

                <!-- Formulaire classique -->
                <form onsubmit="handleLogin(event)" style="display: flex; flex-direction: column; gap: 16px;">
                    <input type="hidden" id="login-role-select" value="admin">

                    <div>
                        <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase;">Adresse email</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 14px;">👤</span>
                            <input type="email" id="login-email" class="form-control" value="admin@storemanager.sn" placeholder="vous@boutique.sn" style="width: 100%; padding: 12px 14px 12px 40px; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 10px; color: #ffffff; font-size: 13px;" required>
                        </div>
                    </div>

                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <label style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Mot de passe</label>
                            <a href="#" onclick="alert('Mot de passe par défaut : demo1234'); return false;" style="font-size: 11px; font-weight: 600; color: var(--accent); text-decoration: none;">Mot de passe oublié ?</a>
                        </div>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 14px;">🔒</span>
                            <input type="password" id="login-password" class="form-control" value="demo1234" placeholder="Votre mot de passe" style="width: 100%; padding: 12px 40px 12px 40px; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 10px; color: #ffffff; font-size: 13px;" required>
                            <span style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 14px; cursor: pointer;">👁️</span>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" id="remember-me" checked style="accent-color: var(--accent); width: 16px; height: 16px; cursor: pointer;">
                        <label for="remember-me" style="font-size: 12px; color: var(--text-muted); cursor: pointer;">Rester connecté sur cet appareil</label>
                    </div>

                    <button type="submit" class="btn-submit" style="padding: 14px; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 8px; box-shadow: 0 10px 25px rgba(45, 212, 191, 0.25);">
                        Se connecter ➔
                    </button>
                </form>

                <div style="text-align: center; margin-top: 18px; font-size: 11px; color: var(--text-muted);">
                    ✓ Tous les comptes utilisent le mot de passe : <strong style="color: var(--accent);">demo1234</strong>
                </div>

            </div>
        </div>
    </div>

    <!-- Toast box for user notices -->
    <div class="toast-box" id="toast-box">
            </div>

    <div class="app-container">
        
        <!-- Top Navbar -->
        <div class="navbar">
            <div class="nav-logo">
                <span>📦</span> StoreManager Pro
            </div>
            <div class="nav-menu">
                <button class="nav-item" id="nav-dashboard" onclick="switchView('dashboard')">Tableau de Bord</button>
                <button class="nav-item" id="nav-pos" onclick="switchView('pos')">Ventes / POS</button>
                <button class="nav-item" id="nav-dettes" onclick="switchView('dettes')">Gestion Dettes</button>
                <button class="nav-item" id="nav-supplies" onclick="switchView('supplies')">Approvisionnements</button>
                <button class="nav-item" id="nav-catalog" onclick="switchView('catalog')">Produits & Tiers</button>
            </div>
            
            <div style="margin-left: auto; display: flex; align-items: center; gap: 14px;">
                <div style="text-align: right;">
                    <div id="current-user-role" style="font-size: 12px; font-weight: 800; color: var(--accent);">Admin Boutique</div>
                    <div style="font-size: 10px; color: var(--text-muted);">Session active</div>
                </div>
                <button type="button" class="btn-quick-action" onclick="logout()" style="border-color: var(--danger); color: var(--danger); background: rgba(248, 113, 113, 0.08); padding: 8px 12px;">Déconnexion 🚪</button>
            </div>
        </div>

        <!-- ================= VIEW: DASHBOARD ================= -->
        <div id="view-dashboard" class="view-section">
            <div class="kpi-grid">
                <!-- Radial Chart 1 -->
                <div class="kpi-card" style="border-left: 4px solid var(--success);">
                    <div>
                        <div class="kpi-label">Ventes Comptant</div>
                        <div class="kpi-val" style="color: var(--success);">92 000 F</div>
                    </div>
                    <div class="progress-ring-container">
                        <svg class="progress-ring" width="60" height="60">
                            <circle class="progress-ring-circle-bg" cx="30" cy="30" r="25"/>
                            <circle class="progress-ring-circle" style="stroke: var(--success); stroke-dashoffset: 20;" cx="30" cy="30" r="25"/>
                        </svg>
                    </div>
                </div>
                <!-- Radial Chart 2 -->
                <div class="kpi-card" style="border-left: 4px solid var(--danger);">
                    <div>
                        <div class="kpi-label">Dettes à Récupérer</div>
                        <div class="kpi-val" style="color: var(--danger);">99 000 F</div>
                    </div>
                    <div class="progress-ring-container">
                        <svg class="progress-ring" width="60" height="60">
                            <circle class="progress-ring-circle-bg" cx="30" cy="30" r="25"/>
                            <circle class="progress-ring-circle" style="stroke: var(--danger); stroke-dashoffset: 70;" cx="30" cy="30" r="25"/>
                        </svg>
                    </div>
                </div>
                <!-- Radial Chart 3 -->
                <div class="kpi-card" style="border-left: 4px solid var(--accent);">
                    <div>
                        <div class="kpi-label">Volume Approvisionné</div>
                        <div class="kpi-val" style="color: var(--accent);">4 520 000 F</div>
                    </div>
                    <div class="progress-ring-container">
                        <svg class="progress-ring" width="60" height="60">
                            <circle class="progress-ring-circle-bg" cx="30" cy="30" r="25"/>
                            <circle class="progress-ring-circle" style="stroke: var(--accent); stroke-dashoffset: 40;" cx="30" cy="30" r="25"/>
                        </svg>
                    </div>
                </div>
                <!-- Radial Chart 4 -->
                <div class="kpi-card" style="border-left: 4px solid var(--warning);">
                    <div>
                        <div class="kpi-label">Valeur du Stock</div>
                        <div class="kpi-val" style="color: var(--warning);">3 476 000 F</div>
                    </div>
                    <div class="progress-ring-container">
                        <svg class="progress-ring" width="60" height="60">
                            <circle class="progress-ring-circle-bg" cx="30" cy="30" r="25"/>
                            <circle class="progress-ring-circle" style="stroke: var(--warning); stroke-dashoffset: 15;" cx="30" cy="30" r="25"/>
                        </svg>
                    </div>
                </div>
                <!-- Radial Chart 5 -->
                <div class="kpi-card" style="border-left: 4px solid var(--success);">
                    <div>
                        <div class="kpi-label">Taux de Recouvrement</div>
                        <div class="kpi-val" style="color: var(--success);">25.6 %</div>
                    </div>
                    <div class="progress-ring-container">
                        <svg class="progress-ring" width="60" height="60">
                            <circle class="progress-ring-circle-bg" cx="30" cy="30" r="25"/>
                            <circle class="progress-ring-circle" style="stroke: var(--success); stroke-dashoffset: 116.808;" cx="30" cy="30" r="25"/>
                        </svg>
                    </div>
                </div>
                <!-- Radial Chart 6 -->
                <div class="kpi-card" style="border-left: 4px solid var(--accent);">
                    <div>
                        <div class="kpi-label">Panier Moyen</div>
                        <div class="kpi-val" style="color: var(--accent);">47 750 F</div>
                    </div>
                    <div class="progress-ring-container">
                        <svg class="progress-ring" width="60" height="60">
                            <circle class="progress-ring-circle-bg" cx="30" cy="30" r="25"/>
                            <circle class="progress-ring-circle" style="stroke: var(--accent); stroke-dashoffset: 50;" cx="30" cy="30" r="25"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 32px; align-items: start;">
                <!-- Left column card with tabs -->
                <div class="panel-card" style="padding: 20px;">
                    <div style="display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                        <button id="dash-left-tab-sales" class="nav-item active" style="flex: 1; padding: 10px; font-size: 11px; text-transform: uppercase;" onclick="switchDashLeftTab('sales')">🛒 Ventes Récentes</button>
                        <button id="dash-left-tab-debts" class="nav-item" style="flex: 1; padding: 10px; font-size: 11px; text-transform: uppercase;" onclick="switchDashLeftTab('debts')">🔴 Dettes du Jour</button>
                        <button id="dash-left-tab-ruptures" class="nav-item" style="flex: 1; padding: 10px; font-size: 11px; text-transform: uppercase;" onclick="switchDashLeftTab('ruptures')">⚠️ Ruptures & Alertes</button>
                    </div>

                    <!-- Tab 1: Ventes Récentes -->
                    <div id="dash-left-panel-sales">
                        <div class="panel-title">Flux de Ventes Récentes</div>
                        <table class="debt-table">
                            <thead>
                                <tr>
                                    <th>Facture</th>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Total</th>
                                    <th>Paiement</th>
                                </tr>
                            </thead>
                            <tbody>
                                                                    <tr>
                                        <td style="font-weight: 700; color: var(--text-muted);">#CMD-4</td>
                                        <td>07 Aug 23:48</td>
                                        <td style="font-weight: 700;">Maimouna Diallo</td>
                                        <td style="font-weight: 800; color: var(--accent);">15 000 F</td>
                                        <td>
                                            <span class="badge non-payee">
                                                CRÉDIT                                            </span>
                                        </td>
                                    </tr>
                                                                    <tr>
                                        <td style="font-weight: 700; color: var(--text-muted);">#CMD-3</td>
                                        <td>07 Aug 22:48</td>
                                        <td style="font-weight: 700;">Moussa Sarr</td>
                                        <td style="font-weight: 800; color: var(--accent);">74 000 F</td>
                                        <td>
                                            <span class="badge non-payee">
                                                CRÉDIT                                            </span>
                                        </td>
                                    </tr>
                                                                    <tr>
                                        <td style="font-weight: 700; color: var(--text-muted);">#CMD-2</td>
                                        <td>07 Aug 21:48</td>
                                        <td style="font-weight: 700;">Fama Diouf</td>
                                        <td style="font-weight: 800; color: var(--accent);">44 000 F</td>
                                        <td>
                                            <span class="badge non-payee">
                                                CRÉDIT                                            </span>
                                        </td>
                                    </tr>
                                                                    <tr>
                                        <td style="font-weight: 700; color: var(--text-muted);">#CMD-1</td>
                                        <td>01 Aug 10:30</td>
                                        <td style="font-weight: 700;">Abdou Ndiaye</td>
                                        <td style="font-weight: 800; color: var(--accent);">58 000 F</td>
                                        <td>
                                            <span class="badge payee">
                                                Wave                                            </span>
                                        </td>
                                    </tr>
                                                            </tbody>
                        </table>
                    </div>

                    <!-- Tab 2: Dettes du Jour -->
                    <div id="dash-left-panel-debts" style="display: none;">
                        <div class="panel-title" style="border-left-color: var(--danger);">Dettes à recouvrer aujourd'hui</div>
                        <table class="debt-table" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Date Création</th>
                                    <th>Montant Initial</th>
                                    <th>Reste Dû</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                                                    <tr>
                                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 16px 0;">Aucun crédit en cours créé aujourd'hui.</td>
                                    </tr>
                                                            </tbody>
                        </table>
                    </div>

                    <!-- Tab 3: Ruptures & Stocks Critiques -->
                    <div id="dash-left-panel-ruptures" style="display: none;">
                        <div class="panel-title" style="border-left-color: var(--danger);">Ruptures & Stocks Critiques</div>
                        <div style="display: flex; flex-direction: column; gap: 14px;">
                                                            <div style="background: rgba(251,191,36,0.05); padding: 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.02);">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div style="font-weight: 700; font-size: 13px;">Bidon d&#039;huile 5L</div>
                                            <div style="color: var(--warning); font-weight: 800; font-size: 11px;">5 en stock</div>
                                        </div>
                                        <button type="button" class="btn-quick-action" onclick="toggleDetails('supply-product-drawer-2')" style="border-color: var(--accent); color: var(--accent); background: rgba(45, 212, 191, 0.05);">Approvisionner</button>
                                    </div>

                                    <!-- Inline drawer for quick supply request -->
                                    <div class="details-drawer" id="supply-product-drawer-2" style="margin-top: 10px; padding: 10px;">
                                        <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Commande d'Approvisionnement Rapide :</div>
                                        <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: grid; grid-template-columns: 1.5fr 1fr 1fr auto; gap: 8px; align-items: flex-end;">
                                            <input type="hidden" name="action" value="quick_supply_product">
                                            <input type="hidden" name="produit_id" value="2">
                                            
                                            <div>
                                                <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 2px;">Fournisseur</label>
                                                <select name="fournisseur_id" class="form-control" style="font-size: 11px; padding: 6px;" required>
                                                                                                            <option value="1">Comptoir Céréalier Sénégalais</option>
                                                                                                            <option value="2">Grossiste Diop &amp; Frères</option>
                                                                                                            <option value="3">Sénégal Import-Export</option>
                                                                                                    </select>
                                            </div>
                                            <div>
                                                <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 2px;">Qté à Commander</label>
                                                <input type="number" name="quantite" class="form-control" value="50" min="1" required style="font-size: 11px; padding: 6px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 2px;">Coût Achat (F)</label>
                                                <input type="number" name="cout_achat_unitaire" class="form-control" value="5600" min="0" required style="font-size: 11px; padding: 6px;">
                                            </div>
                                            <button type="submit" class="btn-submit btn-success" style="padding: 6px 12px; font-size: 10px; text-transform: uppercase;">Valider BL</button>
                                        </form>
                                    </div>
                                </div>
                                                            <div style="background: rgba(251,191,36,0.05); padding: 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.02);">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div style="font-weight: 700; font-size: 13px;">Carton de savon</div>
                                            <div style="color: var(--warning); font-weight: 800; font-size: 11px;">3 en stock</div>
                                        </div>
                                        <button type="button" class="btn-quick-action" onclick="toggleDetails('supply-product-drawer-3')" style="border-color: var(--accent); color: var(--accent); background: rgba(45, 212, 191, 0.05);">Approvisionner</button>
                                    </div>

                                    <!-- Inline drawer for quick supply request -->
                                    <div class="details-drawer" id="supply-product-drawer-3" style="margin-top: 10px; padding: 10px;">
                                        <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Commande d'Approvisionnement Rapide :</div>
                                        <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: grid; grid-template-columns: 1.5fr 1fr 1fr auto; gap: 8px; align-items: flex-end;">
                                            <input type="hidden" name="action" value="quick_supply_product">
                                            <input type="hidden" name="produit_id" value="3">
                                            
                                            <div>
                                                <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 2px;">Fournisseur</label>
                                                <select name="fournisseur_id" class="form-control" style="font-size: 11px; padding: 6px;" required>
                                                                                                            <option value="1">Comptoir Céréalier Sénégalais</option>
                                                                                                            <option value="2">Grossiste Diop &amp; Frères</option>
                                                                                                            <option value="3">Sénégal Import-Export</option>
                                                                                                    </select>
                                            </div>
                                            <div>
                                                <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 2px;">Qté à Commander</label>
                                                <input type="number" name="quantite" class="form-control" value="50" min="1" required style="font-size: 11px; padding: 6px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 2px;">Coût Achat (F)</label>
                                                <input type="number" name="cout_achat_unitaire" class="form-control" value="8400" min="0" required style="font-size: 11px; padding: 6px;">
                                            </div>
                                            <button type="submit" class="btn-submit btn-success" style="padding: 6px 12px; font-size: 10px; text-transform: uppercase;">Valider BL</button>
                                        </form>
                                    </div>
                                </div>
                                                            <div style="background: rgba(248,113,113,0.05); padding: 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.02);">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div style="font-weight: 700; font-size: 13px;">Huile de palme 1L</div>
                                            <div style="color: var(--danger); font-weight: 800; font-size: 11px;">0 en stock</div>
                                        </div>
                                        <button type="button" class="btn-quick-action" onclick="toggleDetails('supply-product-drawer-6')" style="border-color: var(--accent); color: var(--accent); background: rgba(45, 212, 191, 0.05);">Approvisionner</button>
                                    </div>

                                    <!-- Inline drawer for quick supply request -->
                                    <div class="details-drawer" id="supply-product-drawer-6" style="margin-top: 10px; padding: 10px;">
                                        <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Commande d'Approvisionnement Rapide :</div>
                                        <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: grid; grid-template-columns: 1.5fr 1fr 1fr auto; gap: 8px; align-items: flex-end;">
                                            <input type="hidden" name="action" value="quick_supply_product">
                                            <input type="hidden" name="produit_id" value="6">
                                            
                                            <div>
                                                <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 2px;">Fournisseur</label>
                                                <select name="fournisseur_id" class="form-control" style="font-size: 11px; padding: 6px;" required>
                                                                                                            <option value="1">Comptoir Céréalier Sénégalais</option>
                                                                                                            <option value="2">Grossiste Diop &amp; Frères</option>
                                                                                                            <option value="3">Sénégal Import-Export</option>
                                                                                                    </select>
                                            </div>
                                            <div>
                                                <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 2px;">Qté à Commander</label>
                                                <input type="number" name="quantite" class="form-control" value="50" min="1" required style="font-size: 11px; padding: 6px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 2px;">Coût Achat (F)</label>
                                                <input type="number" name="cout_achat_unitaire" class="form-control" value="1400" min="0" required style="font-size: 11px; padding: 6px;">
                                            </div>
                                            <button type="submit" class="btn-submit btn-success" style="padding: 6px 12px; font-size: 10px; text-transform: uppercase;">Valider BL</button>
                                        </form>
                                    </div>
                                </div>
                                                    </div>
                    </div>
                </div>

                <!-- Right column card with tabs -->
                <div class="panel-card" style="padding: 20px;">
                    <div style="display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                        <button id="dash-right-tab-supplies" class="nav-item active" style="flex: 1; padding: 10px; font-size: 11px; text-transform: uppercase;" onclick="switchDashRightTab('supplies')">📦 Livraisons du Jour</button>
                        <button id="dash-right-tab-debtors" class="nav-item" style="flex: 1; padding: 10px; font-size: 11px; text-transform: uppercase;" onclick="switchDashRightTab('debtors')">👥 Clients Débiteurs</button>
                        <button id="dash-right-tab-fournisseurs" class="nav-item" style="flex: 1; padding: 10px; font-size: 11px; text-transform: uppercase;" onclick="switchDashRightTab('fournisseurs')">🤝 Solde Fournisseurs</button>
                    </div>

                    <!-- Tab 1: Approvisionnements attendus aujourd'hui -->
                    <div id="dash-right-panel-supplies">
                        <div class="panel-title" style="border-left-color: var(--warning);">Approvisionnements attendus aujourd'hui</div>
                        <table class="debt-table" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Réf BL</th>
                                    <th>Fournisseur</th>
                                    <th>Valeur Lot</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                                                    <tr>
                                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px 0;">Aucune livraison attendue aujourd'hui.</td>
                                    </tr>
                                                            </tbody>
                        </table>
                    </div>

                    <!-- Tab 2: Clients Débiteurs -->
                    <div id="dash-right-panel-debtors" style="display: none;">
                        <div class="panel-title" style="border-left-color: var(--danger);">Clients avec Dettes en cours</div>
                        <table class="debt-table" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Dettes</th>
                                    <th>Cumul Dû</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                                                                                            <tr>
                                            <td style="font-weight: 700;">
                                                Moussa Sarr                                                <div style="font-size: 10px; color: var(--text-muted); font-weight: normal;">769876543</div>
                                            </td>
                                            <td style="text-align: center; font-weight: 700;">1</td>
                                            <td style="font-weight: 800; color: var(--danger);">50 000 F</td>
                                            <td>
                                                <button type="button" class="btn-quick-action" onclick="toggleDetails('client-debts-drawer-3')" style="border-color: var(--accent); color: var(--accent); background: rgba(45, 212, 191, 0.05);">Dettes</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="padding: 0; border: none;">
                                                <div class="details-drawer" id="client-debts-drawer-3">
                                                    <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Dettes en cours de Moussa Sarr :</div>
                                                    <table class="debt-table" style="font-size: 10px;">
                                                        <thead>
                                                            <tr>
                                                                <th>Réf Dette</th>
                                                                <th>Date</th>
                                                                <th>Initial</th>
                                                                <th>Payé</th>
                                                                <th>Reste Dû</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                                                                                            <tr>
                                                                    <td style="font-weight: 700; color: var(--text-muted);">#DT-2</td>
                                                                    <td>07 Aug 22:48</td>
                                                                    <td style="font-weight: 700;">74 000 F</td>
                                                                    <td style="color: var(--success);">24 000 F</td>
                                                                    <td style="color: var(--danger); font-weight: 800;">50 000 F</td>
                                                                    <td>
                                                                        <button type="button" class="btn-quick-action" onclick="switchView('dettes'); toggleDetails('debt-repay-drawer-2')" style="border-color: var(--danger); color: var(--danger);">Rembourser</button>
                                                                    </td>
                                                                </tr>
                                                                                                                    </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                                                            <tr>
                                            <td style="font-weight: 700;">
                                                Fama Diouf                                                <div style="font-size: 10px; color: var(--text-muted); font-weight: normal;">781234567</div>
                                            </td>
                                            <td style="text-align: center; font-weight: 700;">1</td>
                                            <td style="font-weight: 800; color: var(--danger);">34 000 F</td>
                                            <td>
                                                <button type="button" class="btn-quick-action" onclick="toggleDetails('client-debts-drawer-2')" style="border-color: var(--accent); color: var(--accent); background: rgba(45, 212, 191, 0.05);">Dettes</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="padding: 0; border: none;">
                                                <div class="details-drawer" id="client-debts-drawer-2">
                                                    <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Dettes en cours de Fama Diouf :</div>
                                                    <table class="debt-table" style="font-size: 10px;">
                                                        <thead>
                                                            <tr>
                                                                <th>Réf Dette</th>
                                                                <th>Date</th>
                                                                <th>Initial</th>
                                                                <th>Payé</th>
                                                                <th>Reste Dû</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                                                                                            <tr>
                                                                    <td style="font-weight: 700; color: var(--text-muted);">#DT-1</td>
                                                                    <td>07 Aug 21:48</td>
                                                                    <td style="font-weight: 700;">44 000 F</td>
                                                                    <td style="color: var(--success);">10 000 F</td>
                                                                    <td style="color: var(--danger); font-weight: 800;">34 000 F</td>
                                                                    <td>
                                                                        <button type="button" class="btn-quick-action" onclick="switchView('dettes'); toggleDetails('debt-repay-drawer-1')" style="border-color: var(--danger); color: var(--danger);">Rembourser</button>
                                                                    </td>
                                                                </tr>
                                                                                                                    </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                                                            <tr>
                                            <td style="font-weight: 700;">
                                                Maimouna Diallo                                                <div style="font-size: 10px; color: var(--text-muted); font-weight: normal;">701122334</div>
                                            </td>
                                            <td style="text-align: center; font-weight: 700;">1</td>
                                            <td style="font-weight: 800; color: var(--danger);">15 000 F</td>
                                            <td>
                                                <button type="button" class="btn-quick-action" onclick="toggleDetails('client-debts-drawer-4')" style="border-color: var(--accent); color: var(--accent); background: rgba(45, 212, 191, 0.05);">Dettes</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="padding: 0; border: none;">
                                                <div class="details-drawer" id="client-debts-drawer-4">
                                                    <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Dettes en cours de Maimouna Diallo :</div>
                                                    <table class="debt-table" style="font-size: 10px;">
                                                        <thead>
                                                            <tr>
                                                                <th>Réf Dette</th>
                                                                <th>Date</th>
                                                                <th>Initial</th>
                                                                <th>Payé</th>
                                                                <th>Reste Dû</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                                                                                            <tr>
                                                                    <td style="font-weight: 700; color: var(--text-muted);">#DT-3</td>
                                                                    <td>07 Aug 23:48</td>
                                                                    <td style="font-weight: 700;">15 000 F</td>
                                                                    <td style="color: var(--success);">0 F</td>
                                                                    <td style="color: var(--danger); font-weight: 800;">15 000 F</td>
                                                                    <td>
                                                                        <button type="button" class="btn-quick-action" onclick="switchView('dettes'); toggleDetails('debt-repay-drawer-3')" style="border-color: var(--danger); color: var(--danger);">Rembourser</button>
                                                                    </td>
                                                                </tr>
                                                                                                                    </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                                                                                </tbody>
                        </table>
                    </div>

                    <!-- Tab 3: Fournisseurs & Cumul Dû -->
                    <div id="dash-right-panel-fournisseurs" style="display: none;">
                        <div class="panel-title" style="border-left-color: var(--accent);">Facturation / Cumul par Fournisseur</div>
                        <table class="debt-table" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Fournisseur</th>
                                    <th>Reste à Payer</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                                                    <tr>
                                        <td style="font-weight: 700;">
                                            Comptoir Céréalier Sénégalais                                            <div style="font-size: 10px; color: var(--text-muted); font-weight: normal;">Tél : 338245678</div>
                                        </td>
                                        <td style="font-weight: 800; color: var(--accent);">525 000 F</td>
                                        <td style="display: flex; gap: 6px; align-items: center;">
                                            <button type="button" class="btn-quick-action" onclick="toggleDetails('supplier-invoices-drawer-1')" style="border-color: var(--accent); color: var(--accent); background: rgba(45, 212, 191, 0.05);">Détail</button>
                                            
                                                                                            <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: inline; margin: 0;">
                                                    <input type="hidden" name="action" value="pay_supplier">
                                                    <input type="hidden" name="fournisseur_id" value="1">
                                                    <button type="submit" class="btn-quick-action" style="border-color: var(--success); color: var(--success); background: rgba(52, 211, 153, 0.05);">Tout Payer</button>
                                                </form>
                                                                                    </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="padding: 0; border: none;">
                                            <div class="details-drawer" id="supplier-invoices-drawer-1" style="border: 1px solid rgba(45, 212, 191, 0.25); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 12px; padding: 14px 16px; margin: 8px 0;">
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px dashed var(--border-color); padding-bottom: 8px;">
                                                    <div style="font-weight: 800; font-size: 11px; color: var(--accent);">
                                                        Factures en attente de règlement — Comptoir Céréalier Sénégalais :
                                                    </div>
                                                    <div style="font-size: 10px; font-weight: 800; color: var(--danger);">
                                                        Total Dû : 525 000 FCFA
                                                    </div>
                                                </div>
                                                <table class="debt-table" style="font-size: 11px;">
                                                    <thead>
                                                        <tr>
                                                            <th>Réf BL</th>
                                                            <th>Date Réception</th>
                                                            <th>Montant Facture</th>
                                                            <th>Statut Règlement</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                                                                                    <tr>
                                                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 10px 0;">Aucune facture impayée pour ce fournisseur.</td>
                                                            </tr>
                                                                                                            </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                                                    <tr>
                                        <td style="font-weight: 700;">
                                            Grossiste Diop &amp; Frères                                            <div style="font-size: 10px; color: var(--text-muted); font-weight: normal;">Tél : 773456789</div>
                                        </td>
                                        <td style="font-weight: 800; color: var(--accent);">320 000 F</td>
                                        <td style="display: flex; gap: 6px; align-items: center;">
                                            <button type="button" class="btn-quick-action" onclick="toggleDetails('supplier-invoices-drawer-2')" style="border-color: var(--accent); color: var(--accent); background: rgba(45, 212, 191, 0.05);">Détail</button>
                                            
                                                                                            <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: inline; margin: 0;">
                                                    <input type="hidden" name="action" value="pay_supplier">
                                                    <input type="hidden" name="fournisseur_id" value="2">
                                                    <button type="submit" class="btn-quick-action" style="border-color: var(--success); color: var(--success); background: rgba(52, 211, 153, 0.05);">Tout Payer</button>
                                                </form>
                                                                                    </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="padding: 0; border: none;">
                                            <div class="details-drawer" id="supplier-invoices-drawer-2" style="border: 1px solid rgba(45, 212, 191, 0.25); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 12px; padding: 14px 16px; margin: 8px 0;">
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px dashed var(--border-color); padding-bottom: 8px;">
                                                    <div style="font-weight: 800; font-size: 11px; color: var(--accent);">
                                                        Factures en attente de règlement — Grossiste Diop &amp; Frères :
                                                    </div>
                                                    <div style="font-size: 10px; font-weight: 800; color: var(--danger);">
                                                        Total Dû : 320 000 FCFA
                                                    </div>
                                                </div>
                                                <table class="debt-table" style="font-size: 11px;">
                                                    <thead>
                                                        <tr>
                                                            <th>Réf BL</th>
                                                            <th>Date Réception</th>
                                                            <th>Montant Facture</th>
                                                            <th>Statut Règlement</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                                                                                                                                                    <tr>
                                                                    <td style="font-weight: 700; color: var(--text-muted);">#BL-DIP-099</td>
                                                                    <td>03 Aug 2026</td>
                                                                    <td style="font-weight: 800; color: var(--accent);">320 000 F</td>
                                                                    <td>
                                                                        <span class="badge badge-danger">NON PAYÉ</span>
                                                                    </td>
                                                                    <td>
                                                                        <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: inline; margin: 0;">
                                                                            <input type="hidden" name="action" value="pay_supplier_invoice">
                                                                            <input type="hidden" name="approvisionnement_id" value="2">
                                                                            <button type="submit" class="btn-quick-action" style="border-color: var(--success); color: var(--success); background: rgba(52, 211, 153, 0.08); font-weight: 700;">✓ Payer</button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                                                                                                                                        </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                                                    <tr>
                                        <td style="font-weight: 700;">
                                            Sénégal Import-Export                                            <div style="font-size: 10px; color: var(--text-muted); font-weight: normal;">Tél : 338211010</div>
                                        </td>
                                        <td style="font-weight: 800; color: var(--accent);">190 000 F</td>
                                        <td style="display: flex; gap: 6px; align-items: center;">
                                            <button type="button" class="btn-quick-action" onclick="toggleDetails('supplier-invoices-drawer-3')" style="border-color: var(--accent); color: var(--accent); background: rgba(45, 212, 191, 0.05);">Détail</button>
                                            
                                                                                            <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: inline; margin: 0;">
                                                    <input type="hidden" name="action" value="pay_supplier">
                                                    <input type="hidden" name="fournisseur_id" value="3">
                                                    <button type="submit" class="btn-quick-action" style="border-color: var(--success); color: var(--success); background: rgba(52, 211, 153, 0.05);">Tout Payer</button>
                                                </form>
                                                                                    </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="padding: 0; border: none;">
                                            <div class="details-drawer" id="supplier-invoices-drawer-3" style="border: 1px solid rgba(45, 212, 191, 0.25); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 12px; padding: 14px 16px; margin: 8px 0;">
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px dashed var(--border-color); padding-bottom: 8px;">
                                                    <div style="font-weight: 800; font-size: 11px; color: var(--accent);">
                                                        Factures en attente de règlement — Sénégal Import-Export :
                                                    </div>
                                                    <div style="font-size: 10px; font-weight: 800; color: var(--danger);">
                                                        Total Dû : 190 000 FCFA
                                                    </div>
                                                </div>
                                                <table class="debt-table" style="font-size: 11px;">
                                                    <thead>
                                                        <tr>
                                                            <th>Réf BL</th>
                                                            <th>Date Réception</th>
                                                            <th>Montant Facture</th>
                                                            <th>Statut Règlement</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                                                                                    <tr>
                                                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 10px 0;">Aucune facture impayée pour ce fournisseur.</td>
                                                            </tr>
                                                                                                            </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= VIEW: POS (SALES CONSOLE) ================= -->
        <div id="view-pos" class="view-section">
            <!-- POS Stats Grid -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--success);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">CA Encaissé Net</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">92 000 F</div>
                    </div>
                    <span style="font-size: 24px;">💰</span>
                </div>
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--danger);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Encours Client Total</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">99 000 F</div>
                    </div>
                    <span style="font-size: 24px;">🛑</span>
                </div>
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--accent);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Commandes Enregistrées</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">4 ventes</div>
                    </div>
                    <span style="font-size: 24px;">📊</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 600px 1fr; gap: 32px; align-items: start; margin-bottom: 32px;">
                <!-- Left panel: POS ticket creator (sticky) -->
                <div class="panel-card" style="margin-bottom: 0; padding: 24px; border: 1px solid rgba(59, 130, 246, 0.2); background: linear-gradient(180deg, rgba(17, 24, 43, 0.5) 0%, rgba(10, 15, 30, 0.3) 100%); position: sticky; top: 24px;">
                    <div class="panel-title" style="border-left-color: var(--accent); display: flex; justify-content: space-between; align-items: center;">
                        <span>🛒 Nouvelle Vente</span>
                        <span style="font-size: 11px; font-weight: 600; color: var(--text-muted); background: rgba(255,255,255,0.03); padding: 4px 8px; border-radius: 6px;">Terminal POS</span>
                    </div>
                    <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" id="order-creation-form">
                        <input type="hidden" name="action" value="create_order">
                        
                        <div class="form-group">
                            <label for="client_id">Client Acheteur</label>
                            <div style="position: relative;">
                                <select name="client_id" id="client-select" class="form-control" style="width: 100%; appearance: none; padding-right: 30px;" onchange="updateClientLimitInfo()">
                                                                            <option value="6" data-limit="300000">
                                            Cisse Awa (783332211)                                        </option>
                                                                            <option value="4" data-limit="120000">
                                            Diallo Maimouna (701122334)                                        </option>
                                                                            <option value="2" data-limit="200000">
                                            Diouf Fama (781234567)                                        </option>
                                                                            <option value="10" data-limit="250000">
                                            Fall Fatou (789998877)                                        </option>
                                                                            <option value="7" data-limit="150000">
                                            Faye Babacar (762221100)                                        </option>
                                                                            <option value="9" data-limit="100000">
                                            Gueye Ibrahima (778887766)                                        </option>
                                                                            <option value="8" data-limit="400000">
                                            Mbacke Khady (704443322)                                        </option>
                                                                            <option value="1" data-limit="150000">
                                            Ndiaye Abdou (776543210)                                        </option>
                                                                            <option value="3" data-limit="250000">
                                            Sarr Moussa (769876543)                                        </option>
                                                                            <option value="5" data-limit="180000">
                                            Sow Ousmane (775554433)                                        </option>
                                                                    </select>
                                <span style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-muted); font-size: 12px;">▼</span>
                            </div>
                            <span id="credit-limit-info" style="font-size:11px; color:var(--text-muted); font-weight:600; margin-top:4px; display:block;"></span>
                        </div>

                        <!-- Articles Dynamic add -->
                        <div style="border-top: 1px dashed var(--border-color); padding-top: 16px; margin-top: 16px; margin-bottom: 16px;">
                            <label style="font-size: 12px; font-weight: 700; color: var(--accent); display: block; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Sélection des Articles</label>
                            <div style="display: grid; grid-template-columns: 2.2fr 0.8fr auto; gap: 8px; align-items: flex-end; margin-bottom: 16px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label for="pos-item-select" style="font-size: 10px;">Article</label>
                                    <select id="pos-item-select" class="form-control" style="background-color: #0b0f1a; color: white; padding: 10px; font-size: 12px;">
                                                                                    <option value="2" data-price="8000" data-name="Bidon d&#039;huile 5L" data-stock="5">
                                                🟡 Bidon d&#039;huile 5L (5)                                            </option>
                                                                                    <option value="5" data-price="15000" data-name="Carton de lait" data-stock="40">
                                                🟢 Carton de lait (40)                                            </option>
                                                                                    <option value="3" data-price="12000" data-name="Carton de savon" data-stock="3">
                                                🟡 Carton de savon (3)                                            </option>
                                                                                    <option value="6" data-price="2000" data-name="Huile de palme 1L" data-stock="0">
                                                🔴 Huile de palme 1L (0)                                            </option>
                                                                                    <option value="4" data-price="1500" data-name="Paquet de sucre 1kg" data-stock="200">
                                                🟢 Paquet de sucre 1kg (200)                                            </option>
                                                                                    <option value="1" data-price="25000" data-name="Sac de riz 50kg" data-stock="100">
                                                🟢 Sac de riz 50kg (100)                                            </option>
                                                                            </select>
                                </div>
                                <div class="form-group" style="margin-bottom: 0; position: relative;">
                                    <label for="pos-qty" style="font-size: 10px;">Qté</label>
                                    <input type="number" id="pos-qty" class="form-control" value="1" min="1" style="padding: 10px; font-size: 12px;" onfocus="showKeypad('pos-qty')">
                                </div>
                                <button type="button" class="btn-submit" onclick="addToCart(event)" style="height: 38px; width: 38px; font-size: 18px; display: flex; justify-content: center; align-items: center; border-radius: 8px; padding: 0; flex-shrink: 0; min-width: 38px;">+</button>
                            </div>

                            <!-- Keypad for tactile inputs -->
                            <div class="keypad-container" id="pos-keypad" style="max-width: 100%;">
                                <button type="button" class="keypad-btn" onclick="pressKey(1)">1</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(2)">2</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(3)">3</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(4)">4</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(5)">5</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(6)">6</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(7)">7</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(8)">8</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(9)">9</button>
                                <button type="button" class="keypad-btn" onclick="pressKey('C')" style="color: var(--danger);">C</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(0)">0</button>
                                <button type="button" class="keypad-btn" onclick="hideKeypad()" style="color: var(--success); font-size: 12px;">OK</button>
                            </div>

                            <!-- Cart Items list table -->
                            <table class="debt-table" style="font-size: 11px; margin-top: 16px;">
                                <thead>
                                    <tr>
                                        <th style="padding-bottom: 8px;">Produit</th>
                                        <th style="padding-bottom: 8px;">Qté</th>
                                        <th style="padding-bottom: 8px;">Total</th>
                                        <th style="padding-bottom: 8px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-rows">
                                    <tr id="empty-cart-row">
                                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px 0; border-bottom: none;">Panier vide. Ajoutez des articles.</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div id="hidden-cart-inputs"></div>
                        </div>

                        <!-- Digital Display Panel -->
                        <div style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(30, 41, 59, 0.4) 100%); border: 1px solid rgba(59, 130, 246, 0.15); border-radius: 16px; padding: 14px; text-align: center; margin-bottom: 20px; box-shadow: inset 0 0 15px rgba(59, 130, 246, 0.08);">
                            <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 1px; display: block; margin-bottom: 4px;">Montant Total Net à Payer</span>
                            <div style="font-size: 24px; font-weight: 900; color: #60a5fa; letter-spacing: -0.5px; font-family: monospace; text-shadow: 0 0 10px rgba(96, 165, 250, 0.3);">
                                <span id="montant_total_display_text">0</span> <span style="font-size: 14px; font-weight: 700;">FCFA</span>
                            </div>
                            <input type="hidden" name="montant_total" id="montant_total_display" value="0">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
                             <div class="form-group" style="margin-bottom: 0;">
                                 <label for="mode_reglement" style="font-size: 10px;">Règlement</label>
                                 <select name="mode_reglement" class="form-control" style="background-color: #0b0f1a; padding: 10px; font-size: 12px;">
                                     <option value="Wave">Wave</option>
                                     <option value="Orange Money">OM</option>
                                     <option value="Especes">Espèces</option>
                                 </select>
                             </div>
                             <div class="form-group" style="margin-bottom: 0;">
                                 <label for="pos-montant-verse" style="font-size: 10px;">Versé (Avance)</label>
                                 <input type="number" name="montant_verse" id="pos-montant-verse" class="form-control" value="0" min="0" style="padding: 10px; font-size: 12px;" onfocus="showKeypad('pos-montant-verse')">
                             </div>
                        </div>

                        <button type="submit" class="btn-submit btn-success" style="padding: 14px; font-weight: 800; font-size: 13px; width: 100%;">Valider la Vente (DML)</button>
                    </form>
                </div>

                <!-- Right side: Registry logs -->
                <div class="panel-card" style="margin-bottom: 0;">
                    <div class="panel-title">Registre Général des Ventes & Commandes</div>
                    <table class="debt-table" id="orders-main-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Total Facture</th>
                                <th>Règlement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted);">#CMD-4</td>
                                    <td style="font-weight: 700;">
                                        Maimouna Diallo                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 701122334</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent);">15 000 F</td>
                                    <td>
                                                                                                                                    <span class="badge badge-danger">CRÉDIT TOTAL</span>
                                                                                                                        </td>
                                    <td>
                                        <button class="btn-quick-action" onclick="toggleDetails('order-details-4')">Lignes</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <div class="details-drawer" id="order-details-4">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Détails Facture :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté</th>
                                                        <th>P.U.</th>
                                                        <th>Sous-total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>Paquet de sucre 1kg</td>
                                                            <td>10</td>
                                                            <td>1 500 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">15 000 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted);">#CMD-3</td>
                                    <td style="font-weight: 700;">
                                        Moussa Sarr                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 769876543</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent);">74 000 F</td>
                                    <td>
                                                                                                                                    <span class="badge badge-warning">AVANCE (Credit)</span>
                                                                                                                        </td>
                                    <td>
                                        <button class="btn-quick-action" onclick="toggleDetails('order-details-3')">Lignes</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <div class="details-drawer" id="order-details-3">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Détails Facture :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté</th>
                                                        <th>P.U.</th>
                                                        <th>Sous-total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>Sac de riz 50kg</td>
                                                            <td>2</td>
                                                            <td>25 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">50 000 F</td>
                                                        </tr>
                                                                                                            <tr>
                                                            <td>Carton de savon</td>
                                                            <td>2</td>
                                                            <td>12 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">24 000 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted);">#CMD-2</td>
                                    <td style="font-weight: 700;">
                                        Fama Diouf                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 781234567</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent);">44 000 F</td>
                                    <td>
                                                                                                                                    <span class="badge badge-warning">AVANCE (Credit)</span>
                                                                                                                        </td>
                                    <td>
                                        <button class="btn-quick-action" onclick="toggleDetails('order-details-2')">Lignes</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <div class="details-drawer" id="order-details-2">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Détails Facture :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté</th>
                                                        <th>P.U.</th>
                                                        <th>Sous-total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>Bidon d&#039;huile 5L</td>
                                                            <td>3</td>
                                                            <td>8 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">24 000 F</td>
                                                        </tr>
                                                                                                            <tr>
                                                            <td>Paquet de sucre 1kg</td>
                                                            <td>13</td>
                                                            <td>1 500 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">19 500 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted);">#CMD-1</td>
                                    <td style="font-weight: 700;">
                                        Abdou Ndiaye                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 776543210</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent);">58 000 F</td>
                                    <td>
                                                                                    <span class="badge badge-success">COMPTANT (Wave)</span>
                                                                            </td>
                                    <td>
                                        <button class="btn-quick-action" onclick="toggleDetails('order-details-1')">Lignes</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <div class="details-drawer" id="order-details-1">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Détails Facture :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté</th>
                                                        <th>P.U.</th>
                                                        <th>Sous-total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>Sac de riz 50kg</td>
                                                            <td>2</td>
                                                            <td>25 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">50 000 F</td>
                                                        </tr>
                                                                                                            <tr>
                                                            <td>Bidon d&#039;huile 5L</td>
                                                            <td>1</td>
                                                            <td>8 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">8 000 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                                    </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= VIEW: DEBTS (GESTION DETTES) ================= -->
        <!-- ================= VIEW: DEBTS (GESTION DETTES) ================= -->
        <div id="view-dettes" class="view-section">
            <!-- Debts Stats Grid -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--danger);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Créances Actives</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">99 000 F</div>
                    </div>
                    <span style="font-size: 24px;">💸</span>
                </div>
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--warning);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Clients Débiteurs</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">3 clients</div>
                    </div>
                    <span style="font-size: 24px;">👥</span>
                </div>
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--success);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Total Recouvrements</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">34 000 F</div>
                    </div>
                    <span style="font-size: 24px;">📈</span>
                </div>
            </div>

            <div style="display: block;">
                <!-- Full width: Debt registry logs -->
                <div class="panel-card" style="margin-bottom: 0;">
                    <div class="panel-title">
                        <span>Registre des Dettes Actives</span>
                        <input type="text" id="debt-search" class="search-control" placeholder="Rechercher un client..." onkeyup="filterDebtsTable()">
                    </div>
                    <table class="debt-table" id="debts-main-table">
                        <thead>
                            <tr>
                                <th>ID Dette</th>
                                <th>Date Création</th>
                                <th>Client</th>
                                <th>Montant Initial</th>
                                <th>Montant Payé</th>
                                <th>Reste Dû</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                                            <tr id="debt-row-3" data-client-name="maimouna diallo 701122334" style="transition: all 0.2s;">
                                    <td style="font-weight: 700; color: var(--text-muted);">
                                        #DT-3                                                                                    <span style="font-size: 10px; color: var(--text-muted); display: block; font-weight: normal; margin-top: 2px;">#CMD-4</span>
                                                                            </td>
                                    <td style="font-size: 12px;">07 Aug 2026 23:48</td>
                                    <td style="font-weight: 700;">
                                        Maimouna Diallo                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 701122334</div>
                                    </td>
                                    <td style="font-weight: 700; color: var(--text-main);">15 000 F</td>
                                    <td style="font-weight: 700; color: var(--success);">0 F</td>
                                    <td style="color: var(--danger); font-weight: 800;">15 000 F</td>
                                    <td>
                                        <span class="badge badge-danger">
                                            NON SOLDEE                                        </span>
                                    </td>
                                    <td style="display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('debt-lines-3')">Articles</button>
                                        <button class="btn-quick-action" style="border-color: var(--accent); color: var(--accent);" onclick="toggleDetails('debt-details-3')">💳 Paiements</button>
                                                                                    <button class="btn-quick-action" style="border-color: var(--warning); color: var(--warning);" onclick="toggleDetails('debt-repay-drawer-3')">Rembourser</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Payments list -->
                                        <div class="details-drawer" id="debt-details-3">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Paiements enregistrés :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Versement</th>
                                                        <th>Mode</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr><td colspan="3" style="text-align: center; color: var(--text-muted);">Aucun acompte versé.</td></tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Product lines -->
                                        <div class="details-drawer" id="debt-lines-3">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Articles de la Vente à Crédit :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté</th>
                                                        <th>P.U.</th>
                                                        <th>Sous-total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                                <tr>
                                                                <td>Paquet de sucre 1kg</td>
                                                                <td>10</td>
                                                                <td>1 500 F</td>
                                                                <td style="font-weight: 700; color: var(--accent);">15 000 F</td>
                                                            </tr>
                                                                                                        </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 3: Remboursement form -->
                                                                                      <div class="details-drawer" id="debt-repay-drawer-3" style="border: 1px solid rgba(45, 212, 191, 0.25); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">
                                                 
                                                 <!-- Header row with title and badge -->
                                                 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                                                     <div style="display: flex; align-items: center; gap: 8px;">
                                                         <span style="font-size: 16px;">💳</span>
                                                         <span style="font-weight: 800; font-size: 13px; color: var(--text-main);">
                                                             Nouveau Remboursement — <span style="color: var(--accent);">Maimouna Diallo</span>
                                                         </span>
                                                     </div>
                                                     <div style="background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.3); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; color: var(--danger);">
                                                         Reste dû : 15 000 FCFA
                                                     </div>
                                                 </div>

                                                 <!-- Quick preset amount chips -->
                                                 <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 16px;">
                                                     <span style="font-size: 10px; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Raccourcis :</span>
                                                     <button type="button" onclick="setRepayAmount(3, 15000)" style="background: rgba(45, 212, 191, 0.1); border: 1px solid var(--accent); color: var(--accent); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">Tout solder (15 000 F)</button>
                                                     <button type="button" onclick="setRepayAmount(3, 7500)" style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); color: var(--text-main); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">50% (7 500 F)</button>
                                                 </div>

                                                 <!-- Form fields grid -->
                                                 <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                                                     <input type="hidden" name="action" value="add_payment">
                                                     <input type="hidden" name="dette_id" value="3">

                                                     <div style="flex: 1; min-width: 200px;">
                                                         <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Montant du Versement (FCFA)</label>
                                                         <div style="position: relative;">
                                                             <input type="number" name="montant_verse" id="repay-input-3" class="form-control" max="15000" value="15000" min="1" required style="font-size: 13px; font-weight: 700; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;">
                                                         </div>
                                                     </div>

                                                     <div style="flex: 1; min-width: 200px;">
                                                         <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Canal de Paiement</label>
                                                         <select name="mode_paiement" class="form-control" style="font-size: 13px; font-weight: 600; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;" required>
                                                             <option value="Orange Money">🟠 Orange Money</option>
                                                             <option value="Wave">🌊 Wave</option>
                                                             <option value="Especes">💵 Espèces (Cash)</option>
                                                             <option value="Virement">🏦 Virement Bceao</option>
                                                         </select>
                                                     </div>

                                                     <div>
                                                         <button type="submit" class="btn-submit btn-success" style="padding: 11px 24px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; border-radius: 10px; height: 42px;">
                                                             ✓ Enregistrer le Remboursement
                                                         </button>
                                                     </div>
                                                 </form>
                                             </div>
                                                                             </td>
                                </tr>
                                                            <tr id="debt-row-2" data-client-name="moussa sarr 769876543" style="transition: all 0.2s;">
                                    <td style="font-weight: 700; color: var(--text-muted);">
                                        #DT-2                                                                                    <span style="font-size: 10px; color: var(--text-muted); display: block; font-weight: normal; margin-top: 2px;">#CMD-3</span>
                                                                            </td>
                                    <td style="font-size: 12px;">07 Aug 2026 22:48</td>
                                    <td style="font-weight: 700;">
                                        Moussa Sarr                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 769876543</div>
                                    </td>
                                    <td style="font-weight: 700; color: var(--text-main);">74 000 F</td>
                                    <td style="font-weight: 700; color: var(--success);">24 000 F</td>
                                    <td style="color: var(--danger); font-weight: 800;">50 000 F</td>
                                    <td>
                                        <span class="badge badge-danger">
                                            NON SOLDEE                                        </span>
                                    </td>
                                    <td style="display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('debt-lines-2')">Articles</button>
                                        <button class="btn-quick-action" style="border-color: var(--accent); color: var(--accent);" onclick="toggleDetails('debt-details-2')">💳 Paiements</button>
                                                                                    <button class="btn-quick-action" style="border-color: var(--warning); color: var(--warning);" onclick="toggleDetails('debt-repay-drawer-2')">Rembourser</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Payments list -->
                                        <div class="details-drawer" id="debt-details-2">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Paiements enregistrés :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Versement</th>
                                                        <th>Mode</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>2026-08-07 22:48:53</td>
                                                            <td style="font-weight: 700; color: var(--success);">24 000 F</td>
                                                            <td>Wave</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Product lines -->
                                        <div class="details-drawer" id="debt-lines-2">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Articles de la Vente à Crédit :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté</th>
                                                        <th>P.U.</th>
                                                        <th>Sous-total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                                <tr>
                                                                <td>Sac de riz 50kg</td>
                                                                <td>2</td>
                                                                <td>25 000 F</td>
                                                                <td style="font-weight: 700; color: var(--accent);">50 000 F</td>
                                                            </tr>
                                                                                                                    <tr>
                                                                <td>Carton de savon</td>
                                                                <td>2</td>
                                                                <td>12 000 F</td>
                                                                <td style="font-weight: 700; color: var(--accent);">24 000 F</td>
                                                            </tr>
                                                                                                        </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 3: Remboursement form -->
                                                                                      <div class="details-drawer" id="debt-repay-drawer-2" style="border: 1px solid rgba(45, 212, 191, 0.25); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">
                                                 
                                                 <!-- Header row with title and badge -->
                                                 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                                                     <div style="display: flex; align-items: center; gap: 8px;">
                                                         <span style="font-size: 16px;">💳</span>
                                                         <span style="font-weight: 800; font-size: 13px; color: var(--text-main);">
                                                             Nouveau Remboursement — <span style="color: var(--accent);">Moussa Sarr</span>
                                                         </span>
                                                     </div>
                                                     <div style="background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.3); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; color: var(--danger);">
                                                         Reste dû : 50 000 FCFA
                                                     </div>
                                                 </div>

                                                 <!-- Quick preset amount chips -->
                                                 <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 16px;">
                                                     <span style="font-size: 10px; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Raccourcis :</span>
                                                     <button type="button" onclick="setRepayAmount(2, 50000)" style="background: rgba(45, 212, 191, 0.1); border: 1px solid var(--accent); color: var(--accent); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">Tout solder (50 000 F)</button>
                                                     <button type="button" onclick="setRepayAmount(2, 25000)" style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); color: var(--text-main); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">50% (25 000 F)</button>
                                                 </div>

                                                 <!-- Form fields grid -->
                                                 <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                                                     <input type="hidden" name="action" value="add_payment">
                                                     <input type="hidden" name="dette_id" value="2">

                                                     <div style="flex: 1; min-width: 200px;">
                                                         <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Montant du Versement (FCFA)</label>
                                                         <div style="position: relative;">
                                                             <input type="number" name="montant_verse" id="repay-input-2" class="form-control" max="50000" value="50000" min="1" required style="font-size: 13px; font-weight: 700; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;">
                                                         </div>
                                                     </div>

                                                     <div style="flex: 1; min-width: 200px;">
                                                         <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Canal de Paiement</label>
                                                         <select name="mode_paiement" class="form-control" style="font-size: 13px; font-weight: 600; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;" required>
                                                             <option value="Orange Money">🟠 Orange Money</option>
                                                             <option value="Wave">🌊 Wave</option>
                                                             <option value="Especes">💵 Espèces (Cash)</option>
                                                             <option value="Virement">🏦 Virement Bceao</option>
                                                         </select>
                                                     </div>

                                                     <div>
                                                         <button type="submit" class="btn-submit btn-success" style="padding: 11px 24px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; border-radius: 10px; height: 42px;">
                                                             ✓ Enregistrer le Remboursement
                                                         </button>
                                                     </div>
                                                 </form>
                                             </div>
                                                                             </td>
                                </tr>
                                                            <tr id="debt-row-1" data-client-name="fama diouf 781234567" style="transition: all 0.2s;">
                                    <td style="font-weight: 700; color: var(--text-muted);">
                                        #DT-1                                                                                    <span style="font-size: 10px; color: var(--text-muted); display: block; font-weight: normal; margin-top: 2px;">#CMD-2</span>
                                                                            </td>
                                    <td style="font-size: 12px;">07 Aug 2026 21:48</td>
                                    <td style="font-weight: 700;">
                                        Fama Diouf                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 781234567</div>
                                    </td>
                                    <td style="font-weight: 700; color: var(--text-main);">44 000 F</td>
                                    <td style="font-weight: 700; color: var(--success);">10 000 F</td>
                                    <td style="color: var(--danger); font-weight: 800;">34 000 F</td>
                                    <td>
                                        <span class="badge badge-danger">
                                            NON SOLDEE                                        </span>
                                    </td>
                                    <td style="display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('debt-lines-1')">Articles</button>
                                        <button class="btn-quick-action" style="border-color: var(--accent); color: var(--accent);" onclick="toggleDetails('debt-details-1')">💳 Paiements</button>
                                                                                    <button class="btn-quick-action" style="border-color: var(--warning); color: var(--warning);" onclick="toggleDetails('debt-repay-drawer-1')">Rembourser</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Payments list -->
                                        <div class="details-drawer" id="debt-details-1">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Paiements enregistrés :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Versement</th>
                                                        <th>Mode</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>2026-08-07 21:48:53</td>
                                                            <td style="font-weight: 700; color: var(--success);">10 000 F</td>
                                                            <td>Orange Money</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Product lines -->
                                        <div class="details-drawer" id="debt-lines-1">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Articles de la Vente à Crédit :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté</th>
                                                        <th>P.U.</th>
                                                        <th>Sous-total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                                <tr>
                                                                <td>Bidon d&#039;huile 5L</td>
                                                                <td>3</td>
                                                                <td>8 000 F</td>
                                                                <td style="font-weight: 700; color: var(--accent);">24 000 F</td>
                                                            </tr>
                                                                                                                    <tr>
                                                                <td>Paquet de sucre 1kg</td>
                                                                <td>13</td>
                                                                <td>1 500 F</td>
                                                                <td style="font-weight: 700; color: var(--accent);">19 500 F</td>
                                                            </tr>
                                                                                                        </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 3: Remboursement form -->
                                                                                      <div class="details-drawer" id="debt-repay-drawer-1" style="border: 1px solid rgba(45, 212, 191, 0.25); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">
                                                 
                                                 <!-- Header row with title and badge -->
                                                 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                                                     <div style="display: flex; align-items: center; gap: 8px;">
                                                         <span style="font-size: 16px;">💳</span>
                                                         <span style="font-weight: 800; font-size: 13px; color: var(--text-main);">
                                                             Nouveau Remboursement — <span style="color: var(--accent);">Fama Diouf</span>
                                                         </span>
                                                     </div>
                                                     <div style="background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.3); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; color: var(--danger);">
                                                         Reste dû : 34 000 FCFA
                                                     </div>
                                                 </div>

                                                 <!-- Quick preset amount chips -->
                                                 <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 16px;">
                                                     <span style="font-size: 10px; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Raccourcis :</span>
                                                     <button type="button" onclick="setRepayAmount(1, 34000)" style="background: rgba(45, 212, 191, 0.1); border: 1px solid var(--accent); color: var(--accent); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">Tout solder (34 000 F)</button>
                                                     <button type="button" onclick="setRepayAmount(1, 17000)" style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); color: var(--text-main); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">50% (17 000 F)</button>
                                                 </div>

                                                 <!-- Form fields grid -->
                                                 <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                                                     <input type="hidden" name="action" value="add_payment">
                                                     <input type="hidden" name="dette_id" value="1">

                                                     <div style="flex: 1; min-width: 200px;">
                                                         <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Montant du Versement (FCFA)</label>
                                                         <div style="position: relative;">
                                                             <input type="number" name="montant_verse" id="repay-input-1" class="form-control" max="34000" value="34000" min="1" required style="font-size: 13px; font-weight: 700; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;">
                                                         </div>
                                                     </div>

                                                     <div style="flex: 1; min-width: 200px;">
                                                         <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Canal de Paiement</label>
                                                         <select name="mode_paiement" class="form-control" style="font-size: 13px; font-weight: 600; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;" required>
                                                             <option value="Orange Money">🟠 Orange Money</option>
                                                             <option value="Wave">🌊 Wave</option>
                                                             <option value="Especes">💵 Espèces (Cash)</option>
                                                             <option value="Virement">🏦 Virement Bceao</option>
                                                         </select>
                                                     </div>

                                                     <div>
                                                         <button type="submit" class="btn-submit btn-success" style="padding: 11px 24px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; border-radius: 10px; height: 42px;">
                                                             ✓ Enregistrer le Remboursement
                                                         </button>
                                                     </div>
                                                 </form>
                                             </div>
                                                                             </td>
                                </tr>
                                                    </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= VIEW: SUPPLIES (APPROVISIONNEMENT) ================= -->
        <div id="view-supplies" class="view-section">
            <!-- Supplies Stats Grid -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--accent);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Coût Total des Entrées</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">4 520 000 F</div>
                    </div>
                    <span style="font-size: 24px;">📥</span>
                </div>
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--warning);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Bons de Réception (BL)</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">2 BL reçus</div>
                    </div>
                    <span style="font-size: 24px;">📄</span>
                </div>
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--success);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Fournisseurs Actifs</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">3 entreprises</div>
                    </div>
                    <span style="font-size: 24px;">🤝</span>
                </div>
            </div>

            <div style="display: block;">
                <!-- Full width: deliveries table list -->
                <div class="panel-card" style="padding: 20px; margin-bottom: 0;">
                    <div class="panel-title" style="font-size: 15px; margin-bottom: 16px;">Bordereaux de Livraison (Réceptions)</div>
                    <table class="debt-table" id="supplies-main-table" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Réf BL</th>
                                <th>Fournisseur</th>
                                <th>Valeur Lot</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted); padding: 8px 0;">BL-SEN-102</td>
                                    <td style="padding: 8px 0;">
                                        Sénégal Import-Export                                        <div style="font-size:10px; color:var(--text-muted);">Tél : 338211010</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent); padding: 8px 0;">190 000 F</td>
                                    <td style="padding: 8px 0;">
                                        <span class="badge badge-warning">
                                            EN COURS                                        </span>
                                    </td>
                                    <td style="padding: 8px 0; display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('supply-details-4')">Lignes</button>
                                                                                    <button type="button" class="btn-quick-action" style="border-color: var(--success); color: var(--success); background: rgba(52, 211, 153, 0.05);" onclick="toggleDetails('supply-receive-drawer-4')">Réceptionner</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Supply lines -->
                                        <div class="details-drawer" id="supply-details-4">
                                            <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Détails Réception :</div>
                                            <table class="debt-table" style="font-size: 10px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté Livrée</th>
                                                        <th>Coût Unitaire</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>Paquet de sucre 1kg</td>
                                                            <td>50</td>
                                                            <td>1 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">50 000 F</td>
                                                        </tr>
                                                                                                            <tr>
                                                            <td>Carton de lait</td>
                                                            <td>10</td>
                                                            <td>14 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">140 000 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Confirm Reception Form inline -->
                                                                                    <div class="details-drawer" id="supply-receive-drawer-4" style="border: 1px solid rgba(52, 211, 153, 0.3); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">
                                                
                                                <!-- Header row -->
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                        <span style="font-size: 16px;">📦</span>
                                                        <span style="font-weight: 800; font-size: 13px; color: var(--text-main);">
                                                            Réceptionner le BL — <span style="color: var(--accent);">BL-SEN-102</span>
                                                        </span>
                                                    </div>
                                                    <div style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; color: var(--warning);">
                                                        Fournisseur : Sénégal Import-Export                                                    </div>
                                                </div>

                                                <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="">
                                                    <input type="hidden" name="action" value="receive_supply">
                                                    <input type="hidden" name="approvisionnement_id" value="4">

                                                    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                                                                                                                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 10px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">
                                                                <div>
                                                                    <div style="font-weight: 700; font-size: 13px; color: white;">Paquet de sucre 1kg</div>
                                                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                                                        Quantité théorique commandée : <strong style="color: var(--text-main);">50</strong>
                                                                    </div>
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                                    <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Qté Reçue :</label>
                                                                    <input type="number" name="quantites_livrees[4]" class="form-control" value="50" min="0" required style="width: 100px; padding: 6px 10px; font-size: 13px; font-weight: 700; text-align: center; background: #0b0f1a;">
                                                                </div>
                                                            </div>
                                                                                                                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 10px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">
                                                                <div>
                                                                    <div style="font-weight: 700; font-size: 13px; color: white;">Carton de lait</div>
                                                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                                                        Quantité théorique commandée : <strong style="color: var(--text-main);">10</strong>
                                                                    </div>
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                                    <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Qté Reçue :</label>
                                                                    <input type="number" name="quantites_livrees[5]" class="form-control" value="10" min="0" required style="width: 100px; padding: 6px 10px; font-size: 13px; font-weight: 700; text-align: center; background: #0b0f1a;">
                                                                </div>
                                                            </div>
                                                                                                            </div>

                                                    <div style="display: flex; justify-content: flex-end;">
                                                        <button type="submit" class="btn-submit btn-success" style="padding: 11px 24px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; border-radius: 10px;">
                                                            ✓ Valider la Réception en Stock
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                                                            </td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted); padding: 8px 0;">BL-CCS-101</td>
                                    <td style="padding: 8px 0;">
                                        Comptoir Céréalier Sénégalais                                        <div style="font-size:10px; color:var(--text-muted);">Tél : 338245678</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent); padding: 8px 0;">525 000 F</td>
                                    <td style="padding: 8px 0;">
                                        <span class="badge badge-warning">
                                            EN COURS                                        </span>
                                    </td>
                                    <td style="padding: 8px 0; display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('supply-details-3')">Lignes</button>
                                                                                    <button type="button" class="btn-quick-action" style="border-color: var(--success); color: var(--success); background: rgba(52, 211, 153, 0.05);" onclick="toggleDetails('supply-receive-drawer-3')">Réceptionner</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Supply lines -->
                                        <div class="details-drawer" id="supply-details-3">
                                            <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Détails Réception :</div>
                                            <table class="debt-table" style="font-size: 10px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté Livrée</th>
                                                        <th>Coût Unitaire</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>Sac de riz 50kg</td>
                                                            <td>25</td>
                                                            <td>21 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">525 000 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Confirm Reception Form inline -->
                                                                                    <div class="details-drawer" id="supply-receive-drawer-3" style="border: 1px solid rgba(52, 211, 153, 0.3); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">
                                                
                                                <!-- Header row -->
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                        <span style="font-size: 16px;">📦</span>
                                                        <span style="font-weight: 800; font-size: 13px; color: var(--text-main);">
                                                            Réceptionner le BL — <span style="color: var(--accent);">BL-CCS-101</span>
                                                        </span>
                                                    </div>
                                                    <div style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; color: var(--warning);">
                                                        Fournisseur : Comptoir Céréalier Sénégalais                                                    </div>
                                                </div>

                                                <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="">
                                                    <input type="hidden" name="action" value="receive_supply">
                                                    <input type="hidden" name="approvisionnement_id" value="3">

                                                    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                                                                                                                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 10px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">
                                                                <div>
                                                                    <div style="font-weight: 700; font-size: 13px; color: white;">Sac de riz 50kg</div>
                                                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                                                        Quantité théorique commandée : <strong style="color: var(--text-main);">25</strong>
                                                                    </div>
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                                    <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Qté Reçue :</label>
                                                                    <input type="number" name="quantites_livrees[1]" class="form-control" value="25" min="0" required style="width: 100px; padding: 6px 10px; font-size: 13px; font-weight: 700; text-align: center; background: #0b0f1a;">
                                                                </div>
                                                            </div>
                                                                                                            </div>

                                                    <div style="display: flex; justify-content: flex-end;">
                                                        <button type="submit" class="btn-submit btn-success" style="padding: 11px 24px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; border-radius: 10px;">
                                                            ✓ Valider la Réception en Stock
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                                                            </td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted); padding: 8px 0;">BL-DIP-099</td>
                                    <td style="padding: 8px 0;">
                                        Grossiste Diop &amp; Frères                                        <div style="font-size:10px; color:var(--text-muted);">Tél : 773456789</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent); padding: 8px 0;">320 000 F</td>
                                    <td style="padding: 8px 0;">
                                        <span class="badge badge-success">
                                            REÇU                                        </span>
                                    </td>
                                    <td style="padding: 8px 0; display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('supply-details-2')">Lignes</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Supply lines -->
                                        <div class="details-drawer" id="supply-details-2">
                                            <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Détails Réception :</div>
                                            <table class="debt-table" style="font-size: 10px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté Livrée</th>
                                                        <th>Coût Unitaire</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>Bidon d&#039;huile 5L</td>
                                                            <td>20</td>
                                                            <td>7 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">140 000 F</td>
                                                        </tr>
                                                                                                            <tr>
                                                            <td>Carton de savon</td>
                                                            <td>15</td>
                                                            <td>12 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">180 000 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Confirm Reception Form inline -->
                                                                            </td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted); padding: 8px 0;">BL-CCS-098</td>
                                    <td style="padding: 8px 0;">
                                        Comptoir Céréalier Sénégalais                                        <div style="font-size:10px; color:var(--text-muted);">Tél : 338245678</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent); padding: 8px 0;">4 200 000 F</td>
                                    <td style="padding: 8px 0;">
                                        <span class="badge badge-success">
                                            REÇU                                        </span>
                                    </td>
                                    <td style="padding: 8px 0; display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('supply-details-1')">Lignes</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Supply lines -->
                                        <div class="details-drawer" id="supply-details-1">
                                            <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Détails Réception :</div>
                                            <table class="debt-table" style="font-size: 10px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté Livrée</th>
                                                        <th>Coût Unitaire</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>Sac de riz 50kg</td>
                                                            <td>200</td>
                                                            <td>21 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">4 200 000 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Confirm Reception Form inline -->
                                                                            </td>
                                </tr>
                                                    </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= VIEW: PRODUCTS & TIERS CATALOG ================= -->
        <div id="view-catalog" class="view-section">
            <!-- Catalog Stats Grid -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--success);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Valeur Totale Stock</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">3 476 000 F</div>
                    </div>
                    <span style="font-size: 24px;">📦</span>
                </div>
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--accent);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Articles au Catalogue</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">6 références</div>
                    </div>
                    <span style="font-size: 24px;">🏷️</span>
                </div>
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--warning);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Clients Enregistrés</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">10 clients</div>
                    </div>
                    <span style="font-size: 24px;">👥</span>
                </div>
            </div>

            <!-- Tab Navigation for Catalog -->
            <div style="display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                <button id="catalog-tab-btn-products" class="nav-item active" style="padding: 10px 20px; font-size: 12px; text-transform: uppercase; font-weight: 700;" onclick="switchCatalogTab('products')">🏷️ Gestion Produits</button>
                <button id="catalog-tab-btn-clients" class="nav-item" style="padding: 10px 20px; font-size: 12px; text-transform: uppercase; font-weight: 700;" onclick="switchCatalogTab('clients')">👥 Gestion Clients</button>
                <button id="catalog-tab-btn-suppliers" class="nav-item" style="padding: 10px 20px; font-size: 12px; text-transform: uppercase; font-weight: 700;" onclick="switchCatalogTab('suppliers')">🤝 Gestion Fournisseurs</button>
            </div>

            <!-- TAB 1: Gestion Produits -->
            <div id="catalog-panel-products" style="display: grid; grid-template-columns: 600px 1fr; gap: 32px; align-items: start;">
                <!-- Left: Form -->
                <div class="panel-card" style="margin-bottom: 0;">
                    <div class="panel-title">Ajouter un Article</div>
                    <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="">
                        <input type="hidden" name="action" value="add_product">
                        <div class="form-group">
                            <label for="nom">Nom de l'Article</label>
                            <input type="text" name="nom" class="form-control" placeholder="Ex: Carton de savon" required>
                        </div>
                        <div class="form-group">
                            <label for="prix_unitaire">Prix de Vente (FCFA)</label>
                            <input type="number" name="prix_unitaire" class="form-control" placeholder="Ex: 12000" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="quantite_stock">Stock Initial</label>
                            <input type="number" name="quantite_stock" class="form-control" placeholder="Ex: 50" min="0" required>
                        </div>
                        <button type="submit" class="btn-submit btn-success" style="width: 100%;">Enregistrer le Produit (DML)</button>
                    </form>
                </div>

                <!-- Right: Product list -->
                <div class="panel-card" style="margin-bottom: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <label style="font-size: 13px; font-weight: 700; color: var(--accent); text-transform: uppercase;">Catalogue Courant</label>
                        <input type="text" id="catalog-search" class="search-control" placeholder="Filtrer les produits..." onkeyup="filterProductsTable()">
                    </div>
                    <table class="debt-table" id="catalog-main-table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Prix de Vente</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                                                            <tr data-product-name="bidon d&#039;huile 5l">
                                    <td style="font-weight: 700;">Bidon d&#039;huile 5L</td>
                                    <td>8 000 F</td>
                                    <td style="font-weight: 700; color: var(--danger);">
                                        5                                    </td>
                                </tr>
                                                            <tr data-product-name="carton de lait">
                                    <td style="font-weight: 700;">Carton de lait</td>
                                    <td>15 000 F</td>
                                    <td style="font-weight: 700; color: var(--success);">
                                        40                                    </td>
                                </tr>
                                                            <tr data-product-name="carton de savon">
                                    <td style="font-weight: 700;">Carton de savon</td>
                                    <td>12 000 F</td>
                                    <td style="font-weight: 700; color: var(--danger);">
                                        3                                    </td>
                                </tr>
                                                            <tr data-product-name="huile de palme 1l">
                                    <td style="font-weight: 700;">Huile de palme 1L</td>
                                    <td>2 000 F</td>
                                    <td style="font-weight: 700; color: var(--danger);">
                                        0                                    </td>
                                </tr>
                                                            <tr data-product-name="paquet de sucre 1kg">
                                    <td style="font-weight: 700;">Paquet de sucre 1kg</td>
                                    <td>1 500 F</td>
                                    <td style="font-weight: 700; color: var(--success);">
                                        200                                    </td>
                                </tr>
                                                            <tr data-product-name="sac de riz 50kg">
                                    <td style="font-weight: 700;">Sac de riz 50kg</td>
                                    <td>25 000 F</td>
                                    <td style="font-weight: 700; color: var(--success);">
                                        100                                    </td>
                                </tr>
                                                    </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: Gestion Clients -->
            <div id="catalog-panel-clients" style="display: none; grid-template-columns: 600px 1fr; gap: 32px; align-items: start;">
                <!-- Left: Form -->
                <div class="panel-card" style="margin-bottom: 0;">
                    <div class="panel-title">Enregistrer un Client</div>
                    <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="">
                        <input type="hidden" name="action" value="add_client">
                        <div class="form-row" style="display: flex; gap: 12px;">
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label for="prenom">Prénom</label>
                                <input type="text" name="prenom" class="form-control" placeholder="Ex: Abdou" required>
                            </div>
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label for="nom">Nom</label>
                                <input type="text" name="nom" class="form-control" placeholder="Ex: Ndiaye" required>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 12px;">
                            <label for="telephone">Téléphone</label>
                            <input type="text" name="telephone" class="form-control" placeholder="Ex: 776543210" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" class="form-control" placeholder="Ex: client@email.sn">
                        </div>
                        <div class="form-group">
                            <label for="limite_credit">Limite de Crédit (FCFA)</label>
                            <input type="number" name="limite_credit" class="form-control" value="150000" min="0" required>
                        </div>
                        <button type="submit" class="btn-submit" style="width: 100%;">Créer le Compte Client (DML)</button>
                    </form>
                </div>

                <!-- Right: Clients list -->
                <div class="panel-card" style="margin-bottom: 0;">
                    <label style="font-size: 13px; font-weight: 700; color: var(--accent); display: block; margin-bottom: 12px; text-transform: uppercase;">Répertoire Clients</label>
                    <table class="debt-table" id="clients-main-table" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Téléphone</th>
                                <th>Limite de Crédit</th>
                            </tr>
                        </thead>
                        <tbody>
                                                            <tr>
                                    <td style="font-weight: 700;">Awa Cisse</td>
                                    <td>783332211</td>
                                    <td style="font-weight: 700; color: var(--accent);">300 000 F</td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700;">Maimouna Diallo</td>
                                    <td>701122334</td>
                                    <td style="font-weight: 700; color: var(--accent);">120 000 F</td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700;">Fama Diouf</td>
                                    <td>781234567</td>
                                    <td style="font-weight: 700; color: var(--accent);">200 000 F</td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700;">Fatou Fall</td>
                                    <td>789998877</td>
                                    <td style="font-weight: 700; color: var(--accent);">250 000 F</td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700;">Babacar Faye</td>
                                    <td>762221100</td>
                                    <td style="font-weight: 700; color: var(--accent);">150 000 F</td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700;">Ibrahima Gueye</td>
                                    <td>778887766</td>
                                    <td style="font-weight: 700; color: var(--accent);">100 000 F</td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700;">Khady Mbacke</td>
                                    <td>704443322</td>
                                    <td style="font-weight: 700; color: var(--accent);">400 000 F</td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700;">Abdou Ndiaye</td>
                                    <td>776543210</td>
                                    <td style="font-weight: 700; color: var(--accent);">150 000 F</td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700;">Moussa Sarr</td>
                                    <td>769876543</td>
                                    <td style="font-weight: 700; color: var(--accent);">250 000 F</td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700;">Ousmane Sow</td>
                                    <td>775554433</td>
                                    <td style="font-weight: 700; color: var(--accent);">180 000 F</td>
                                </tr>
                                                    </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: Gestion Fournisseurs -->
            <div id="catalog-panel-suppliers" style="display: none; grid-template-columns: 600px 1fr; gap: 32px; align-items: start;">
                <!-- Left: Form -->
                <div class="panel-card" style="margin-bottom: 0;">
                    <div class="panel-title">Enregistrer un Fournisseur</div>
                    <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="">
                        <input type="hidden" name="action" value="add_supplier">
                        <div class="form-group">
                            <label for="nom">Nom de l'Entreprise</label>
                            <input type="text" name="nom" class="form-control" placeholder="Ex: Comptoir Céréalier Sénégalais" required>
                        </div>
                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="text" name="telephone" class="form-control" placeholder="Ex: 338245678" required>
                        </div>
                        <div class="form-group">
                            <label for="adresse">Adresse / Dépôt</label>
                            <input type="text" name="adresse" class="form-control" placeholder="Ex: Hangar 4, Port de Dakar" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail (Optionnel)</label>
                            <input type="email" name="email" class="form-control" placeholder="Ex: contact@fournisseur.sn">
                        </div>
                        <button type="submit" class="btn-submit" style="width: 100%;">Créer le Fournisseur (DML)</button>
                    </form>
                </div>

                <!-- Right: Suppliers list -->
                <div class="panel-card" style="margin-bottom: 0;">
                    <label style="font-size: 13px; font-weight: 700; color: var(--accent); display: block; margin-bottom: 12px; text-transform: uppercase;">Répertoire Fournisseurs</label>
                    <table class="debt-table" id="suppliers-main-table" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Entreprise</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                            </tr>
                        </thead>
                        <tbody>
                                                            <tr>
                                    <td style="font-weight: 700;">Comptoir Céréalier Sénégalais</td>
                                    <td>338245678</td>
                                    <td>Port de Dakar, Hangar 4</td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700;">Grossiste Diop &amp; Frères</td>
                                    <td>773456789</td>
                                    <td>Marché Grand Yoff, Lot B</td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700;">Sénégal Import-Export</td>
                                    <td>338211010</td>
                                    <td>Zone Industrielle de Hann</td>
                                </tr>
                                                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Single Page routing, Cart calculations, keypads & autocomplete -->
    <script>
        let activeInputId = null;
        let currentUserRole = null;

        const rolePermissions = {
            admin: { name: "👑 Admin Boutique", allowedViews: ['dashboard', 'pos', 'dettes', 'supplies', 'catalog'], defaultView: 'dashboard' },
            vente: { name: "🛒 Chargé de Vente", allowedViews: ['pos', 'dettes'], defaultView: 'pos' },
            stock: { name: "📦 Chargé de Stock", allowedViews: ['supplies', 'catalog'], defaultView: 'supplies' },
            inventaire: { name: "📋 Inventaire", allowedViews: ['catalog'], defaultView: 'catalog' }
        };

        function updateProfileInfoHint() {
            const roleKey = document.getElementById("login-role-select").value;
            const hintBox = document.getElementById("role-desc-hint");
            if (roleKey === 'admin') {
                hintBox.innerHTML = "💡 <strong>Admin Boutique</strong> : Contrôle total sur la comptabilité, ventes, dettes, approvisionnements et paramétrage.";
            } else if (roleKey === 'vente') {
                hintBox.innerHTML = "💡 <strong>Chargé de Vente</strong> : Accès restreint à la caisse tactile POS et au registre de suivi des dettes clients.";
            } else if (roleKey === 'stock') {
                hintBox.innerHTML = "💡 <strong>Chargé de Stock</strong> : Gestion des approvisionnements, réception de marchandises et catalogue produits/fournisseurs.";
            } else if (roleKey === 'inventaire') {
                hintBox.innerHTML = "💡 <strong>Inventaire</strong> : Mode consultation et comptage des stocks et répertoires tiers.";
            }
        }

        function selectQuickProfile(roleKey, email, roleName) {
            document.getElementById("login-role-select").value = roleKey;
            const emailInput = document.getElementById("login-email");
            if (emailInput) emailInput.value = email;

            document.querySelectorAll(".quick-profile-card").forEach(card => {
                card.style.borderColor = "rgba(255, 255, 255, 0.08)";
                card.style.borderWidth = "1px";
                card.style.background = "rgba(22, 30, 49, 0.4)";
                card.classList.remove("active");
            });

            const activeCard = document.getElementById("profile-card-" + roleKey);
            if (activeCard) {
                activeCard.style.borderColor = "var(--accent)";
                activeCard.style.borderWidth = "2px";
                activeCard.style.background = "rgba(22, 30, 49, 0.75)";
                activeCard.classList.add("active");
            }
        }

        function handleLogin(event) {
            event.preventDefault();
            const selectedRole = document.getElementById("login-role-select").value;
            currentUserRole = selectedRole;
            localStorage.setItem("erp_logged_role", selectedRole);
            
            document.getElementById("login-screen").style.display = "none";
            applyRolePermissions();
        }

        function logout() {
            localStorage.removeItem("erp_logged_role");
            currentUserRole = null;
            document.getElementById("login-screen").style.display = "flex";
        }

        function applyRolePermissions() {
            const role = localStorage.getItem("erp_logged_role") || currentUserRole;
            if (!role || !rolePermissions[role]) {
                document.getElementById("login-screen").style.display = "flex";
                return;
            }

            document.getElementById("login-screen").style.display = "none";
            const roleConfig = rolePermissions[role];
            document.getElementById("current-user-role").innerText = roleConfig.name;

            // Afficher ou masquer les éléments du menu
            const views = ['dashboard', 'pos', 'dettes', 'supplies', 'catalog'];
            views.forEach(v => {
                const navBtn = document.getElementById("nav-" + v);
                if (navBtn) {
                    if (roleConfig.allowedViews.includes(v)) {
                        navBtn.style.display = "inline-block";
                    } else {
                        navBtn.style.display = "none";
                    }
                }
            });

            // Basculer vers la vue autorisée par défaut ou sauvegardée
            let currentView = localStorage.getItem("active_erp_view");
            if (!roleConfig.allowedViews.includes(currentView)) {
                currentView = roleConfig.defaultView;
            }
            switchView(currentView);
        }

        function switchView(viewId) {
            const role = localStorage.getItem("erp_logged_role");
            if (role && rolePermissions[role] && !rolePermissions[role].allowedViews.includes(viewId)) {
                alert("Accès non autorisé pour le profil " + rolePermissions[role].name);
                return;
            }

            document.querySelectorAll(".nav-menu .nav-item").forEach(item => item.classList.remove("active"));
            const activeNav = document.getElementById("nav-" + viewId);
            if (activeNav) activeNav.classList.add("active");

            document.querySelectorAll(".view-section").forEach(sec => sec.classList.remove("active-view"));
            const targetSection = document.getElementById("view-" + viewId);
            if (targetSection) targetSection.classList.add("active-view");

            localStorage.setItem("active_erp_view", viewId);
            hideKeypad();
        }

        function switchSupplyTab(tabName) {
            document.getElementById("supply-tab-btn-create").classList.remove("active");
            document.getElementById("supply-tab-btn-supplier").classList.remove("active");
            document.getElementById("supply-panel-create").style.display = "none";
            document.getElementById("supply-panel-supplier").style.display = "none";
            document.getElementById("supply-panel-receive-confirm").style.display = "none";

            if (tabName === 'create') {
                document.getElementById("supply-tab-btn-create").classList.add("active");
                document.getElementById("supply-panel-create").style.display = "block";
            } else if (tabName === 'supplier') {
                document.getElementById("supply-tab-btn-supplier").classList.add("active");
                document.getElementById("supply-panel-supplier").style.display = "block";
            }
            hideKeypad();
        }

        function startReception(appId, blRef, items) {
            document.getElementById("supply-panel-create").style.display = "none";
            document.getElementById("supply-panel-supplier").style.display = "none";
            document.getElementById("supply-panel-receive-confirm").style.display = "block";

            document.getElementById("receive-bl-ref").innerText = blRef;
            document.getElementById("receive-app-id").value = appId;

            const container = document.getElementById("receive-items-container");
            container.innerHTML = "";

            items.forEach(item => {
                container.innerHTML += `
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 12px; padding: 12px;">
                        <div style="font-weight: 700; font-size: 13px; margin-bottom: 8px; color: var(--text-main);">${item.nom}</div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; align-items: center;">
                            <div style="font-size: 11px; color: var(--text-muted);">
                                Attendu : <strong>${item.quantite}</strong>
                            </div>
                            <div>
                                <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 4px; text-transform: uppercase;">Qté Reçue</label>
                                <input type="number" name="quantites_livrees[${item.produit_id}]" class="form-control" value="${item.quantite}" min="0" required style="padding: 8px; font-size: 12px;">
                            </div>
                        </div>
                    </div>
                `;
            });
            
            document.getElementById("supply-tab-btn-create").classList.remove("active");
            document.getElementById("supply-tab-btn-supplier").classList.remove("active");
            hideKeypad();
        }

        function cancelReception() {
            document.getElementById("supply-panel-receive-confirm").style.display = "none";
            document.getElementById("supply-panel-create").style.display = "block";
            document.getElementById("supply-tab-btn-create").classList.add("active");
        }

        function switchDashLeftTab(tabName) {
            document.getElementById("dash-left-tab-sales").classList.remove("active");
            document.getElementById("dash-left-tab-debts").classList.remove("active");
            document.getElementById("dash-left-tab-ruptures").classList.remove("active");
            document.getElementById("dash-left-panel-sales").style.display = "none";
            document.getElementById("dash-left-panel-debts").style.display = "none";
            document.getElementById("dash-left-panel-ruptures").style.display = "none";

            if (tabName === 'sales') {
                document.getElementById("dash-left-tab-sales").classList.add("active");
                document.getElementById("dash-left-panel-sales").style.display = "block";
            } else if (tabName === 'debts') {
                document.getElementById("dash-left-tab-debts").classList.add("active");
                document.getElementById("dash-left-panel-debts").style.display = "block";
            } else if (tabName === 'ruptures') {
                document.getElementById("dash-left-tab-ruptures").classList.add("active");
                document.getElementById("dash-left-panel-ruptures").style.display = "block";
            }
        }

        function switchDashRightTab(tabName) {
            document.getElementById("dash-right-tab-supplies").classList.remove("active");
            document.getElementById("dash-right-tab-debtors").classList.remove("active");
            document.getElementById("dash-right-tab-fournisseurs").classList.remove("active");
            document.getElementById("dash-right-panel-supplies").style.display = "none";
            document.getElementById("dash-right-panel-debtors").style.display = "none";
            document.getElementById("dash-right-panel-fournisseurs").style.display = "none";

            if (tabName === 'supplies') {
                document.getElementById("dash-right-tab-supplies").classList.add("active");
                document.getElementById("dash-right-panel-supplies").style.display = "block";
            } else if (tabName === 'debtors') {
                document.getElementById("dash-right-tab-debtors").classList.add("active");
                document.getElementById("dash-right-panel-debtors").style.display = "block";
            } else if (tabName === 'fournisseurs') {
                document.getElementById("dash-right-tab-fournisseurs").classList.add("active");
                document.getElementById("dash-right-panel-fournisseurs").style.display = "block";
            }
        }

        function approvisionnerProduit(productId) {
            switchView('supplies');
            switchSupplyTab('create');
            const select = document.getElementById("supply-item-select");
            if (select) {
                select.value = productId;
            }
            const costInput = document.getElementById("supply-cost");
            if (costInput) {
                costInput.focus();
            }
        }

        function setRepayAmount(debtId, amount) {
            const input = document.getElementById("repay-input-" + debtId);
            if (input) {
                input.value = amount;
                input.focus();
            }
        }

        function toggleDetails(panelId) {
            const panel = document.getElementById(panelId);
            if (!panel) return;
            const isVisible = window.getComputedStyle(panel).display !== 'none';
            panel.style.display = isVisible ? 'none' : 'block';

            // Find parent tr and toggle it too!
            const parentRow = panel.closest('tr');
            if (parentRow) {
                // If this is Dettes, hide other drawers in same tr when opening one
                if (panelId.includes('debt-')) {
                    const drawers = Array.from(parentRow.querySelectorAll('.details-drawer'));
                    drawers.forEach(dr => {
                        if (dr.id !== panelId) dr.style.display = 'none';
                    });
                }

                // If at least one details-drawer inside this row is block, show the parent row, else hide it.
                const drawers = Array.from(parentRow.querySelectorAll('.details-drawer'));
                const anyOpen = drawers.some(dr => window.getComputedStyle(dr).display !== 'none');
                parentRow.style.display = anyOpen ? '' : 'none';
            }
        }

        // Initialize view
        document.addEventListener("DOMContentLoaded", () => {
            applyRolePermissions();
            updateClientLimitInfo();

            // Initialisation de la pagination sur tous les tableaux principaux
            initPaginatedTable("debts-main-table", 10);
            initPaginatedTable("orders-main-table", 10);
            initPaginatedTable("supplies-main-table", 10);
            initPaginatedTable("catalog-main-table", 10);
            initPaginatedTable("clients-main-table", 10);

            // Auto dismiss toast after 4 seconds
            const mainToast = document.getElementById("main-toast");
            if (mainToast) {
                setTimeout(() => {
                    mainToast.style.animation = "slideIn 0.3s ease reverse forwards";
                    setTimeout(() => mainToast.remove(), 300);
                }, 4000);
            }
        });

        function switchCatalogTab(tabName) {
            document.getElementById("catalog-tab-btn-products").classList.remove("active");
            document.getElementById("catalog-tab-btn-clients").classList.remove("active");
            const suppBtn = document.getElementById("catalog-tab-btn-suppliers");
            if (suppBtn) suppBtn.classList.remove("active");

            document.getElementById("catalog-panel-products").style.display = "none";
            document.getElementById("catalog-panel-clients").style.display = "none";
            const suppPanel = document.getElementById("catalog-panel-suppliers");
            if (suppPanel) suppPanel.style.display = "none";

            if (tabName === 'products') {
                document.getElementById("catalog-tab-btn-products").classList.add("active");
                document.getElementById("catalog-panel-products").style.display = "grid";
            } else if (tabName === 'clients') {
                document.getElementById("catalog-tab-btn-clients").classList.add("active");
                document.getElementById("catalog-panel-clients").style.display = "grid";
            } else if (tabName === 'suppliers') {
                if (suppBtn) suppBtn.classList.add("active");
                if (suppPanel) suppPanel.style.display = "grid";
            }
            hideKeypad();
        }

        // POS Virtual tactile keyboard
        function showKeypad(inputId) {
            activeInputId = inputId;
            // Hide all keypads first
            document.getElementById("pos-keypad").style.display = "none";
            document.getElementById("payment-keypad").style.display = "none";
            const supplyKeypad = document.getElementById("supply-keypad");
            if (supplyKeypad) supplyKeypad.style.display = "none";

            if (inputId === 'pos-qty' || inputId === 'pos-montant-verse') {
                document.getElementById("pos-keypad").style.display = "grid";
            } else if (inputId === 'payment-amount') {
                document.getElementById("payment-keypad").style.display = "grid";
            } else if ((inputId === 'supply-qty' || inputId === 'supply-cost') && supplyKeypad) {
                supplyKeypad.style.display = "grid";
            }
        }

        function pressKey(key) {
            if (!activeInputId) return;
            const input = document.getElementById(activeInputId);

            if (key === 'C') {
                input.value = "";
            } else {
                input.value = (input.value === "1" || input.value === "0") && activeInputId === 'pos-qty' ? key : input.value + key;
            }
        }

        function hideKeypad() {
            document.getElementById("pos-keypad").style.display = "none";
            document.getElementById("payment-keypad").style.display = "none";
            const supplyKeypad = document.getElementById("supply-keypad");
            if (supplyKeypad) supplyKeypad.style.display = "none";
            activeInputId = null;
        }

        // Live autocomplete search filters
        function filterDebtsTable() {
            const query = document.getElementById("debt-search").value.toLowerCase();
            const rows = document.querySelectorAll("#debts-main-table tbody > tr");

            rows.forEach(row => {
                const cell = row.querySelector("td");
                if (cell && cell.getAttribute("colspan") !== null) return;

                const searchVal = row.getAttribute("data-client-name");
                if (searchVal) {
                    row.style.display = searchVal.includes(query) ? "" : "none";
                }
            });

            const table = document.getElementById("debts-main-table");
            if (table && table.updatePagination) {
                table.updatePagination();
            }
        }

        function filterProductsTable() {
            const query = document.getElementById("catalog-search").value.toLowerCase();
            const rows = document.querySelectorAll("#catalog-main-table tbody > tr");

            rows.forEach(row => {
                const searchVal = row.getAttribute("data-product-name");
                if (searchVal) {
                    row.style.display = searchVal.includes(query) ? "" : "none";
                }
            });

            const table = document.getElementById("catalog-main-table");
            if (table && table.updatePagination) {
                table.updatePagination();
            }
        }

        // POS Shopping Cart logic
        const cart = [];

        function addToCart(event) {
            event.preventDefault();
            const select = document.getElementById("pos-item-select");
            const price = parseFloat(select.options[select.selectedIndex].getAttribute("data-price"));
            const name = select.options[select.selectedIndex].getAttribute("data-name");
            const stock = parseInt(select.options[select.selectedIndex].getAttribute("data-stock"));
            const id = select.value;
            const qty = parseInt(document.getElementById("pos-qty").value);

            if (qty <= 0) return;

            if (qty > stock) {
                alert(`Stock insuffisant pour ${name} (${stock} disponible) !`);
                return;
            }

            const existing = cart.find(item => item.id === id);
            if (existing) {
                if (existing.qty + qty > stock) {
                    alert(`Stock insuffisant (${stock} disponible) !`);
                    return;
                }
                existing.qty += qty;
                existing.total = existing.qty * price;
            } else {
                cart.push({ id, name, price, qty, total: qty * price });
            }

            renderCart();
            hideKeypad();
        }

        function removeCartItem(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function renderCart() {
            const body = document.getElementById("cart-rows");
            const textDisplay = document.getElementById("montant_total_display_text");
            const valueInput = document.getElementById("montant_total_display");
            const hiddenInputs = document.getElementById("hidden-cart-inputs");

            if (cart.length === 0) {
                body.innerHTML = `
                    <tr id="empty-cart-row">
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px 0; border-bottom: none;">Panier vide. Ajoutez des articles.</td>
                    </tr>
                `;
                textDisplay.innerText = "0";
                valueInput.value = 0;
                hiddenInputs.innerHTML = "";
                document.getElementById("pos-montant-verse").value = 0;
                return;
            }

            body.innerHTML = "";
            hiddenInputs.innerHTML = "";
            let overallTotal = 0;

            cart.forEach((item, index) => {
                overallTotal += item.total;
                body.innerHTML += `
                    <tr>
                        <td style="padding: 8px 0; font-weight:700;">${item.name}</td>
                        <td style="padding: 8px 0;">${item.qty}</td>
                        <td style="padding: 8px 0; font-weight:800; color:var(--accent);">${new Intl.NumberFormat('fr-FR').format(item.total)} F</td>
                        <td style="padding: 8px 0; text-align:right;">
                            <button type="button" onclick="removeCartItem(${index})" style="background:none; border:none; color:var(--danger); cursor:pointer; font-size:14px;">🗑️</button>
                        </td>
                    </tr>
                `;
                hiddenInputs.innerHTML += `
                    <input type="hidden" name="product_ids[]" value="${item.id}">
                    <input type="hidden" name="product_qtys[]" value="${item.qty}">
                `;
            });

            textDisplay.innerText = new Intl.NumberFormat('fr-FR').format(overallTotal);
            valueInput.value = overallTotal;
            
            // Par défaut, le paiement est au comptant (montant versé = montant total)
            document.getElementById("pos-montant-verse").value = overallTotal;
        }

        function updateClientLimitInfo() {
            const select = document.getElementById("client-select");
            if (!select || select.selectedIndex < 0) return;
            const opt = select.options[select.selectedIndex];
            if (!opt) return;
            const limit = parseFloat(opt.getAttribute("data-limit"));
            document.getElementById("credit-limit-info").innerText = `Limite de crédit autorisée : ${new Intl.NumberFormat('fr-FR').format(limit)} FCFA`;
        }

        // Remboursements actions
        function openPaymentPanel(detteId, resteDu, clientName) {
            document.getElementById("payment-debt-id").value = detteId;
            document.getElementById("payment-client-name").value = clientName;
            document.getElementById("payment-max-limit").value = new Intl.NumberFormat('fr-FR').format(resteDu) + " F";
            document.getElementById("payment-amount").max = resteDu;
            document.getElementById("payment-amount").value = resteDu;
            
            // Toggle form views
            document.getElementById("action-placeholder").style.display = "none";
            document.getElementById("sms-alert-form-wrapper").style.display = "none";
            document.getElementById("remboursement-form-wrapper").style.display = "block";
            
            highlightDebtRow(detteId);
            showKeypad('payment-amount');
        }

        // Alert SMS triggers
        function generateSMSAlert(detteId, prenom, reste, telephone) {
            const area = document.getElementById("sms-text-area");
            area.value = `RAPPEL DE REMBOURSEMENT\n\nCher(e) ${prenom},\nNous vous rappelons qu'un solde restant de ${new Intl.NumberFormat('fr-FR').format(reste)} FCFA est en attente de régularisation sur votre compte StoreManager.\nMerci de procéder au règlement via Wave, Orange Money ou Espèces.\n\nContact : ${telephone}.`;

            // Toggle form views
            document.getElementById("action-placeholder").style.display = "none";
            document.getElementById("remboursement-form-wrapper").style.display = "none";
            document.getElementById("sms-alert-form-wrapper").style.display = "block";
            
            highlightDebtRow(detteId);
            hideKeypad();
        }

        function copySMSTemplate() {
            const area = document.getElementById("sms-text-area");
            area.select();
            document.execCommand("copy");
            alert("Rappel de relance copié dans le presse-papier !");
        }

        function highlightDebtRow(detteId) {
            // Reset previous highlights
            document.querySelectorAll("#debts-main-table tbody tr").forEach(tr => {
                tr.style.background = "";
                tr.style.borderLeft = "";
            });
            // Apply outline glow to selected row
            const targetRow = document.getElementById("debt-row-" + detteId);
            if (targetRow) {
                targetRow.style.background = "rgba(59, 130, 246, 0.08)";
                targetRow.style.borderLeft = "4px solid var(--accent)";
            }
        }

        // Approvisionnement Dynamic Cart Logic
        const supplyCart = [];

        function addSupplyItem(event) {
            event.preventDefault();
            const select = document.getElementById("supply-item-select");
            const name = select.options[select.selectedIndex].getAttribute("data-name");
            const id = select.value;
            const qty = parseInt(document.getElementById("supply-qty").value);
            const cost = parseFloat(document.getElementById("supply-cost").value);

            if (qty <= 0 || isNaN(cost) || cost < 0) {
                alert("Veuillez saisir une quantité et un coût d'achat valides !");
                return;
            }

            const existing = supplyCart.find(item => item.id === id);
            if (existing) {
                existing.qty += qty;
                existing.cost = cost;
                existing.total = existing.qty * cost;
            } else {
                supplyCart.push({ id, name, qty, cost, total: qty * cost });
            }

            renderSupplyCart();
            document.getElementById("supply-cost").value = "";
            hideKeypad();
        }

        function removeSupplyItem(index) {
            supplyCart.splice(index, 1);
            renderSupplyCart();
        }

        function renderSupplyCart() {
            const body = document.getElementById("supply-cart-rows");
            const textDisplay = document.getElementById("supply_total_display_text");
            const valueInput = document.getElementById("supply_total_display");
            const hiddenInputs = document.getElementById("hidden-supply-inputs");

            if (supplyCart.length === 0) {
                body.innerHTML = `
                    <tr id="empty-supply-row">
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 12px 0;">Aucun article ajouté.</td>
                    </tr>
                `;
                textDisplay.innerText = "0";
                valueInput.value = 0;
                hiddenInputs.innerHTML = "";
                return;
            }

            body.innerHTML = "";
            hiddenInputs.innerHTML = "";
            let overallTotal = 0;

            supplyCart.forEach((item, index) => {
                overallTotal += item.total;
                body.innerHTML += `
                    <tr>
                        <td style="padding: 6px 0; font-weight:700;">${item.name}</td>
                        <td style="padding: 6px 0;">${item.qty}</td>
                        <td style="padding: 6px 0;">${new Intl.NumberFormat('fr-FR').format(item.cost)} F</td>
                        <td style="padding: 6px 0; font-weight:800; color:var(--accent);">${new Intl.NumberFormat('fr-FR').format(item.total)} F</td>
                        <td style="padding: 6px 0; text-align:right;">
                            <button type="button" onclick="removeSupplyItem(${index})" style="background:none; border:none; color:var(--danger); cursor:pointer; font-size:12px;">🗑️</button>
                        </td>
                    </tr>
                `;
                hiddenInputs.innerHTML += `
                    <input type="hidden" name="product_ids[]" value="${item.id}">
                    <input type="hidden" name="product_qtys[]" value="${item.qty}">
                    <input type="hidden" name="product_costs[]" value="${item.cost}">
                `;
            });

            textDisplay.innerText = new Intl.NumberFormat('fr-FR').format(overallTotal);
            valueInput.value = overallTotal;
        }
        // Système de pagination côté client intelligent
        function initPaginatedTable(tableId, rowsPerPage = 10) {
            const table = document.getElementById(tableId);
            if (!table) return;

            const tbody = table.querySelector("tbody");
            if (!tbody) return;

            const allRows = Array.from(tbody.children);
            const groups = [];
            for (let i = 0; i < allRows.length; i++) {
                const r = allRows[i];
                const cells = r.querySelectorAll("td");
                if (cells.length > 0 && cells[0].getAttribute("colspan") === null) {
                    const nextRow = allRows[i + 1];
                    const hasDetail = nextRow && nextRow.querySelector(".details-drawer");
                    groups.push({
                        main: r,
                        detail: hasDetail ? nextRow : null
                    });
                }
            }

            let pagerContainer = document.getElementById(tableId + "-pager");
            if (!pagerContainer) {
                pagerContainer = document.createElement("div");
                pagerContainer.id = tableId + "-pager";
                pagerContainer.style.display = "flex";
                pagerContainer.style.justifyContent = "center";
                pagerContainer.style.alignItems = "center";
                pagerContainer.style.gap = "8px";
                pagerContainer.style.marginTop = "16px";
                pagerContainer.style.padding = "10px 0";
                table.parentNode.insertBefore(pagerContainer, table.nextSibling);
            }

            table.updatePagination = function() {
                const activeGroups = groups.filter(g => g.main.style.display !== "none");
                const totalPages = Math.ceil(activeGroups.length / rowsPerPage);
                let currentPage = 1;

                function showPage(page) {
                    if (page < 1) page = 1;
                    if (page > totalPages) page = totalPages;
                    currentPage = page;
                    
                    const start = (page - 1) * rowsPerPage;
                    const end = start + rowsPerPage;

                    activeGroups.forEach((group, idx) => {
                        const inRange = idx >= start && idx < end;
                        group.main.style.setProperty("display", inRange ? "" : "none", "important");
                        if (group.detail) {
                            if (!inRange) {
                                group.detail.style.display = "none";
                            } else {
                                const drawers = Array.from(group.detail.querySelectorAll(".details-drawer"));
                                const drawerVisible = drawers.some(dr => window.getComputedStyle(dr).display !== 'none');
                                group.detail.style.display = drawerVisible ? "" : "none";
                            }
                        }
                    });

                    groups.forEach(g => {
                        if (!activeGroups.includes(g)) {
                            g.main.style.display = "none";
                            if (g.detail) g.detail.style.display = "none";
                        }
                    });

                    renderPager();
                }

                function renderPager() {
                    pagerContainer.innerHTML = "";
                    if (totalPages <= 1) {
                        pagerContainer.style.display = "none";
                        return;
                    }
                    pagerContainer.style.display = "flex";

                    const prevBtn = document.createElement("button");
                    prevBtn.className = "btn-quick-action";
                    prevBtn.innerText = "◀";
                    prevBtn.disabled = currentPage === 1;
                    prevBtn.style.opacity = currentPage === 1 ? "0.4" : "1";
                    prevBtn.onclick = (e) => { e.preventDefault(); if (currentPage > 1) showPage(currentPage - 1); };
                    pagerContainer.appendChild(prevBtn);

                    let startPage = Math.max(1, currentPage - 2);
                    let endPage = Math.min(totalPages, startPage + 4);
                    if (endPage - startPage < 4) {
                        startPage = Math.max(1, endPage - 4);
                    }

                    for (let i = startPage; i <= endPage; i++) {
                        const pageBtn = document.createElement("button");
                        pageBtn.className = "btn-quick-action";
                        pageBtn.innerText = i;
                        pageBtn.style.minWidth = "30px";
                        if (i === currentPage) {
                            pageBtn.style.background = "var(--accent)";
                            pageBtn.style.borderColor = "var(--accent)";
                            pageBtn.style.color = "white";
                        }
                        pageBtn.onclick = (e) => { e.preventDefault(); showPage(i); };
                        pagerContainer.appendChild(pageBtn);
                    }

                    const nextBtn = document.createElement("button");
                    nextBtn.className = "btn-quick-action";
                    nextBtn.innerText = "▶";
                    nextBtn.disabled = currentPage === totalPages;
                    nextBtn.style.opacity = currentPage === totalPages ? "0.4" : "1";
                    nextBtn.onclick = (e) => { e.preventDefault(); if (currentPage < totalPages) showPage(currentPage + 1); };
                    pagerContainer.appendChild(nextBtn);
                }

                showPage(1);
            };

            table.updatePagination();
        }
    </script>
</body>
</html>