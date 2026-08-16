<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'StoreManager Pro' ?> | ERP Tactical Workspace</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php Helpers::asset('base.css'); ?>">
    <link rel="stylesheet" href="<?php Helpers::asset('ventes.css'); ?>">
    <link rel="stylesheet" href="<?php Helpers::asset('dettes.css'); ?>">
</head>

<body>

    <div class="toast-box" id="toast-box">
    </div>

    <div class="app-container">
        <div class="navbar">
            <div class="nav-logo">
                <span>📦</span> StoreManager Pro
            </div>
            <div class="nav-menu">
                <a href="">Tableau de Bord</a>
                <a href="<?php Helpers::pathUrl("pos") ?>">Ventes / POS</a>
                <a href="<?php Helpers::pathUrl("dettes") ?>">Gestion Dettes</a>
                <a href="">Approvisionnements</a>
                <a href="">Produits & Tiers</a>
                <div style="margin-left: auto; display: flex; align-items: center; gap: 14px;">
                    <div style="text-align: right;">
                        <div style="font-size: 12px; font-weight: 800; color: var(--accent);">phot</div>
                        <div style="font-size: 10px; color: var(--text-muted);">ndour</div>
                    </div>
                    <a href="" class="btn-quick-action" style="border-color: var(--danger); color: var(--danger); background: rgba(248, 113, 113, 0.08); padding: 8px 12px;">Déconnexion 🚪</a>
                </div>
            </div>

        </div>
        <main>
            <?= $contentView ?? '' ?>
        </main>
</body>

</html>