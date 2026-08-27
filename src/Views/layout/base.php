<?php
use Adja\Core\Helpers;
use Adja\Core\SessionManager;

$flashError = SessionManager::getData('error');
$flashSuccess = SessionManager::getData('success');
if ($flashError !== null) SessionManager::remove('error');
if ($flashSuccess !== null) SessionManager::remove('success');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'StoreManager Pro' ?> | ERP Tactical Workspace</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= Helpers::asset('css/base.css', WEB_ROUTE); ?>">
    <link rel="stylesheet" href="<?= Helpers::asset('css/dashboard.css', WEB_ROUTE); ?>">
    <link rel="stylesheet" href="<?= Helpers::asset('css/ventes.css', WEB_ROUTE); ?>">
    <link rel="stylesheet" href="<?= Helpers::asset('css/dettes.css', WEB_ROUTE); ?>">
    <link rel="stylesheet" href="<?= Helpers::asset('css/appros.css', WEB_ROUTE); ?>">
    <link rel="stylesheet" href="<?= Helpers::asset('css/catalog.css', WEB_ROUTE); ?>">
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
                <a href="<?= Helpers::pathUrl("dashboard", WEB_ROUTE); ?>" class="nav-item <?= $isDashboard ? 'active' : '' ?>">Tableau de Bord</a>
                <a href="<?= Helpers::pathUrl("pos", WEB_ROUTE); ?>" class="nav-item <?= $isPos ? 'active' : '' ?>">Ventes / POS</a>
                <a href="<?= Helpers::pathUrl("dettes", WEB_ROUTE); ?>" class="nav-item <?= $isDettes ? 'active' : '' ?>">Gestion Dettes</a>
                <a href="<?= Helpers::pathUrl("appros", WEB_ROUTE); ?>" class="nav-item <?= $isAppros ? 'active' : '' ?>">Approvisionnements</a>
                <a href="<?= Helpers::pathUrl("produits", WEB_ROUTE); ?>" class="nav-item <?= $isCatalog ? 'active' : '' ?>">Produits & Tiers</a>
            </div>

            <div style="margin-left: auto; display: flex; align-items: center; gap: 14px;">
                <?php
                $keyUserConnect = defined('KEY_USERCONNECT') ? KEY_USERCONNECT : 'userConnect';
                $userConnect = SessionManager::getData($keyUserConnect);
                $nomComplet = ($userConnect instanceof \App\Model\Entity\Utilisateur) ? $userConnect->getNomComplet() : ((is_array($userConnect)) ? (($userConnect['prenom'] ?? '') . ' ' . ($userConnect['nom'] ?? '')) : 'Utilisateur');
                $roleNom = ($userConnect instanceof \App\Model\Entity\Utilisateur) ? ($userConnect->getRole()?->getNom() ?? 'GESTIONNAIRE') : (is_array($userConnect) && isset($userConnect['role']) ? (is_array($userConnect['role']) ? ($userConnect['role']['nom'] ?? 'GESTIONNAIRE') : (string)$userConnect['role']) : 'GESTIONNAIRE');
                ?>
                <div style="text-align: right;">
                    <div style="font-size: 12px; font-weight: 800; color: var(--accent);"><?= htmlspecialchars($nomComplet) ?></div>
                    <div style="font-size: 10px; color: var(--text-muted);"><?= htmlspecialchars(strtoupper($roleNom)) ?></div>
                </div>
                <a href="<?= Helpers::pathUrl("logout", WEB_ROUTE); ?>" class="btn-quick-action" style="border-color: var(--danger); color: var(--danger); background: rgba(248, 113, 113, 0.08); padding: 8px 12px;">Déconnexion 🚪</a>
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