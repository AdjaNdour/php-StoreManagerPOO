<?php
use App\Core\Helpers;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;

$tab = $viewData['tab'] ?? $viewData['activeTab'] ?? 'products';
$produits = $viewData['produits'] ?? [];
$clients = $viewData['clients'] ?? [];
$fournisseurs = $viewData['fournisseurs'] ?? [];
$allFournisseurs = $viewData['allFournisseurs'] ?? \App\Service\FournisseurService::getAll();

$valeurTotaleStock = $viewData['valeurTotaleStock'] ?? array_reduce($produits, fn($carry, $p) => $carry + ($p->getStockInitial() * $p->getCoutAchat()), 0);
$totalArticles = $viewData['totalArticles'] ?? count($produits);
$totalClients = $viewData['totalClients'] ?? count($clients);
$totalFournisseurs = $viewData['totalFournisseurs'] ?? count($fournisseurs);

$filteredTableau = $viewData['filteredTableau'] ?? $viewData['filtered'] ?? new FilteredModel();
$pagination = $viewData['pagination'] ?? new PaginationModel();
?>

<div id="view-catalog" class="view-section" style="display: block;">
    <!-- Catalog Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--success); margin-bottom: 0;">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Valeur Totale Stock</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;"><?= $valeurTotaleStock ?> F</div>
            </div>
            <span style="font-size: 24px;">📦</span>
        </div>
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--accent); margin-bottom: 0;">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Articles au Catalogue</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;"><?= $totalArticles ?> références</div>
            </div>
            <span style="font-size: 24px;">🏷️</span>
        </div>
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--warning); margin-bottom: 0;">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Clients Enregistrés</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;"><?= $totalClients ?> clients</div>
            </div>
            <span style="font-size: 24px;">👥</span>
        </div>
    </div>

    <!-- Tab Navigation for Catalog -->
    <div style="display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <a href="<?php Helpers::pathUrl("produits?tab=products"); ?>" class="nav-item <?= $tab === 'products' ? 'active' : '' ?>" style="padding: 10px 20px; font-size: 12px; text-transform: uppercase; font-weight: 700;">🏷️ Gestion Produits</a>
        <a href="<?php Helpers::pathUrl("produits?tab=clients"); ?>" class="nav-item <?= $tab === 'clients' ? 'active' : '' ?>" style="padding: 10px 20px; font-size: 12px; text-transform: uppercase; font-weight: 700;">👥 Gestion Clients</a>
        <a href="<?php Helpers::pathUrl("produits?tab=suppliers"); ?>" class="nav-item <?= $tab === 'suppliers' ? 'active' : '' ?>" style="padding: 10px 20px; font-size: 12px; text-transform: uppercase; font-weight: 700;">🤝 Gestion Fournisseurs</a>
    </div>

    <!-- TAB 1: Gestion Produits -->
    <?php if ($tab === 'products'): ?>
        <div id="catalog-panel-products" style="display: grid; grid-template-columns: 420px 1fr; gap: 32px; align-items: start;">
            <!-- Left: Form -->
            <div class="panel-card" style="margin-bottom: 0;">
                <div class="panel-title">Ajouter un Article</div>
                <form method="POST" action="<?php Helpers::pathUrl("produits/add"); ?>">
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
                    <button type="submit" class="btn-submit btn-success" style="width: 100%;">Enregistrer le Produit</button>
                </form>
            </div>

            <!-- Right: Product list -->
            <div class="panel-card" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <label style="font-size: 13px; font-weight: 700; color: var(--accent); text-transform: uppercase;">Catalogue Courant</label>
                    <form method="GET" action="<?php Helpers::pathUrl("produits"); ?>" style="display: flex; gap: 8px;">
                        <input type="hidden" name="tab" value="products">
                        <input type="text" name="search" class="search-control" placeholder="Filtrer les produits..." value="<?= $filteredTableau->getFilter('search') ?? '' ?>">
                        <button type="submit" class="btn-action" style="padding: 6px 12px;">OK</button>
                    </form>
                </div>
                <table class="debt-table" id="catalog-main-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Article</th>
                            <th>Prix de Vente</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($produits)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px 0;">Aucun article trouvé.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($produits as $p): ?>
                                <tr>
                                    <td style="font-family: monospace; color: var(--text-muted);"><?= $p->getCode() ?></td>
                                    <td style="font-weight: 700;"><?= $p->getLibelle() ?></td>
                                    <td style="font-weight: 700; color: var(--accent);"><?= $p->getPrixVente() ?> F</td>
                                    <td style="font-weight: 700; color: <?= $p->getStockInitial() <= $p->getSeuilAlerte() ? 'var(--danger)' : 'var(--success)' ?>;">
                                        <?= $p->getStockInitial() ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination Component -->
                <?php
                $entityLabel = "article(s)";
                $extraParams = ['tab' => 'products'];
                require PATHBASE . "/src/Views/components/pagination.php";
                ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- TAB 2: Gestion Clients -->
    <?php if ($tab === 'clients'): ?>
        <div id="catalog-panel-clients" style="display: grid; grid-template-columns: 420px 1fr; gap: 32px; align-items: start;">
            <!-- Left: Form -->
            <div class="panel-card" style="margin-bottom: 0;">
                <div class="panel-title">Enregistrer un Client</div>
                <form method="POST" action="<?php Helpers::pathUrl("clients/add"); ?>">
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
                    <button type="submit" class="btn-submit" style="width: 100%;">Créer le Compte Client</button>
                </form>
            </div>

            <!-- Right: Clients list -->
            <div class="panel-card" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <label style="font-size: 13px; font-weight: 700; color: var(--accent); text-transform: uppercase;">Répertoire Clients</label>
                    <form method="GET" action="<?php Helpers::pathUrl("produits"); ?>" style="display: flex; gap: 8px;">
                        <input type="hidden" name="tab" value="clients">
                        <input type="text" name="search" class="search-control" placeholder="Filtrer les clients..." value="<?= $filteredTableau->getFilter('search') ?? '' ?>">
                        <button type="submit" class="btn-action" style="padding: 6px 12px;">OK</button>
                    </form>
                </div>
                <table class="debt-table" id="clients-main-table" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Téléphone</th>
                            <th>Limite de Crédit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clients)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 16px 0;">Aucun client trouvé.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($clients as $c): ?>
                                <tr>
                                    <td style="font-weight: 700;"><?= $c->getNomComplet() ?></td>
                                    <td><?= $c->getTelephone() ?></td>
                                    <td style="font-weight: 700; color: var(--accent);"><?= $c->getLimiteCredit() ?> F</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination Component -->
                <?php
                $entityLabel = "client(s)";
                $extraParams = ['tab' => 'clients'];
                require PATHBASE . "/src/Views/components/pagination.php";
                ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- TAB 3: Gestion Fournisseurs -->
    <?php if ($tab === 'suppliers'): ?>
        <div id="catalog-panel-suppliers" style="display: grid; grid-template-columns: 420px 1fr; gap: 32px; align-items: start;">
            <!-- Left: Form -->
            <div class="panel-card" style="margin-bottom: 0;">
                <div class="panel-title">Enregistrer un Fournisseur</div>
                <form method="POST" action="<?php Helpers::pathUrl("fournisseurs/add"); ?>">
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
                    <button type="submit" class="btn-submit" style="width: 100%;">Créer le Fournisseur</button>
                </form>
            </div>

            <!-- Right: Suppliers list -->
            <div class="panel-card" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <label style="font-size: 13px; font-weight: 700; color: var(--accent); text-transform: uppercase;">Répertoire Fournisseurs</label>
                    <form method="GET" action="<?php Helpers::pathUrl("produits"); ?>" style="display: flex; gap: 8px;">
                        <input type="hidden" name="tab" value="suppliers">
                        <input type="text" name="search" class="search-control" placeholder="Filtrer les fournisseurs..." value="<?= $filteredTableau->getFilter('search') ?? '' ?>">
                        <button type="submit" class="btn-action" style="padding: 6px 12px;">OK</button>
                    </form>
                </div>
                <table class="debt-table" id="suppliers-main-table" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($fournisseurs)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 16px 0;">Aucun fournisseur trouvé.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($fournisseurs as $f): ?>
                                <tr>
                                    <td style="font-weight: 700;"><?= $f->getNom() ?></td>
                                    <td><?= $f->getTelephone() ?></td>
                                    <td><?= $f->getAdresse() ?? '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination Component -->
                <?php
                $entityLabel = "fournisseur(s)";
                $extraParams = ['tab' => 'suppliers'];
                require PATHBASE . "/src/Views/components/pagination.php";
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>
