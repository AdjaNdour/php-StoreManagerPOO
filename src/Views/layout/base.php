<?php
use App\Core\Helpers;
use App\Core\SessionManager;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'StoreManager Pro' ?> | ERP Tactical Workspace</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php Helpers::asset('base.css'); ?>">
    <link rel="stylesheet" href="<?php Helpers::asset('dashboard.css'); ?>">
    <link rel="stylesheet" href="<?php Helpers::asset('ventes.css'); ?>">
    <link rel="stylesheet" href="<?php Helpers::asset('dettes.css'); ?>">
    <link rel="stylesheet" href="<?php Helpers::asset('appros.css'); ?>">
    <link rel="stylesheet" href="<?php Helpers::asset('catalog.css'); ?>">
</head>

<body>

    <div class="app-container">
        <!-- Top Navbar with 5 Modules -->
        <div class="navbar">
            <div class="nav-logo">
                <span>📦</span> StoreManager Pro
            </div>
            <div class="nav-menu">
                <?php
                $currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
                $isDashboard = (str_contains($currentUri, 'dashboard') || $currentUri === '/' || $currentUri === '');
                $isPos = str_contains($currentUri, 'pos') || str_contains($currentUri, 'ventes');
                $isDettes = str_contains($currentUri, 'dettes');
                $isAppros = str_contains($currentUri, 'appros') || str_contains($currentUri, 'approvisionnements');
                $isCatalog = str_contains($currentUri, 'produits') || str_contains($currentUri, 'catalog') || str_contains($currentUri, 'tiers');
                ?>
                <a href="<?php Helpers::pathUrl("dashboard"); ?>" class="nav-item <?= $isDashboard ? 'active' : '' ?>">Tableau de Bord</a>
                <a href="<?php Helpers::pathUrl("pos"); ?>" class="nav-item <?= $isPos ? 'active' : '' ?>">Ventes / POS</a>
                <a href="<?php Helpers::pathUrl("dettes"); ?>" class="nav-item <?= $isDettes ? 'active' : '' ?>">Gestion Dettes</a>
                <a href="<?php Helpers::pathUrl("appros"); ?>" class="nav-item <?= $isAppros ? 'active' : '' ?>">Approvisionnements</a>
                <a href="<?php Helpers::pathUrl("produits"); ?>" class="nav-item <?= $isCatalog ? 'active' : '' ?>">Produits & Tiers</a>
            </div>

            <div style="margin-left: auto; display: flex; align-items: center; gap: 14px;">
                <div style="text-align: right;">
                    <div style="font-size: 12px; font-weight: 800; color: var(--accent);"><?php Helpers::showProfil(); ?></div>
                    <div style="font-size: 10px; color: var(--text-muted);"><?php Helpers::showRole(); ?></div>
                </div>
                <a href="<?php Helpers::pathUrl("logout"); ?>" class="btn-quick-action" style="border-color: var(--danger); color: var(--danger); background: rgba(248, 113, 113, 0.08); padding: 8px 12px;">Déconnexion 🚪</a>
            </div>
        </div>

        <main>
            <?php if (!empty($flashError)): ?>
                <div class="flash-box flash-error"><?= $flashError ?></div>
            <?php endif; ?>
            <?php if (!empty($flashSuccess)): ?>
                <div class="flash-box flash-success"><?= $flashSuccess ?></div>
            <?php endif; ?>
            <?= $contentView ?? '' ?>
        </main>
    </div>
</body>

</html>