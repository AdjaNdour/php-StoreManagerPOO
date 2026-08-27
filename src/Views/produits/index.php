<?php
use Adja\Core\Helpers;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;

$tab = $viewData['tab'] ?? 'products';
$produits = $viewData['produits'] ?? [];
$clients = $viewData['clients'] ?? [];
$fournisseurs = $viewData['fournisseurs'] ?? [];
$allFournisseurs = $viewData['allFournisseurs'] ?? \App\Service\FournisseurService::getAll();

$stats = $viewData['stats'] ?? null;
$valeurTotaleStock = $stats->valeurTotaleStock ?? 0;
$totalArticles = $stats->totalArticles ?? 0 ;
$totalClients = $stats->totalClients ?? 0 ;
$totalFournisseurs = $stats->totalFournisseurs ?? 0 ;

$filteredTableau = $viewData['filteredTableau'] ?? null;
$pagination = $viewData['pagination'] ?? null;

$errors = $viewData['errors'] ?? \Adja\Core\SessionManager::getData('errors') ?? [];
if (\Adja\Core\SessionManager::hasKey('errors')) {
    \Adja\Core\SessionManager::removeData('errors');
}
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
        <a href="<?= Helpers::pathUrl("produits?tab=products", WEB_ROUTE); ?>" class="nav-item <?= $tab === 'products' ? 'active' : '' ?>" style="padding: 10px 20px; font-size: 12px; text-transform: uppercase; font-weight: 700;">🏷️ Gestion Produits</a>
        <a href="<?= Helpers::pathUrl("produits?tab=clients", WEB_ROUTE); ?>" class="nav-item <?= $tab === 'clients' ? 'active' : '' ?>" style="padding: 10px 20px; font-size: 12px; text-transform: uppercase; font-weight: 700;">👥 Gestion Clients</a>
        <a href="<?= Helpers::pathUrl("produits?tab=suppliers", WEB_ROUTE); ?>" class="nav-item <?= $tab === 'suppliers' ? 'active' : '' ?>" style="padding: 10px 20px; font-size: 12px; text-transform: uppercase; font-weight: 700;">🤝 Gestion Fournisseurs</a>
    </div>

    <!-- TAB 1: Gestion Produits -->
    <?php if ($tab === 'products'): ?>
        <div id="catalog-panel-products" style="display: grid; grid-template-columns: 420px 1fr; gap: 32px; align-items: start;">
            <!-- Left: Form -->
            <div class="panel-card" style="margin-bottom: 0;">
                <div class="panel-title">Ajouter un Article</div>
                <form method="POST" action="<?= Helpers::pathUrl("produits/add", WEB_ROUTE); ?>">
                    <div class="form-group">
                        <label for="nom">Nom de l'Article</label>
                        <input type="text" name="nom" id="nom" class="form-control" placeholder="Ex: Carton de savon" >
                        <?php if (!empty($errors['nom']) || !empty($errors['libelle'])): ?>
                            <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['nom'] ?? $errors['libelle'] ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-row" style="display: flex; gap: 12px;">
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <label for="categorie">Catégorie</label>
                            <input type="text" name="categorie" id="categorie" class="form-control" placeholder="Ex: Général" value="Général">
                            <?php if (!empty($errors['categorie'])): ?>
                                <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['categorie'] ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row" style="display: flex; gap: 12px; margin-top: 12px;">
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <label for="prix_unitaire">Prix Vente (FCFA)</label>
                            <input type="number" name="prix_unitaire" id="prix_unitaire" class="form-control" placeholder="Ex: 12000" min="0" >
                            <?php if (!empty($errors['prix_unitaire']) || !empty($errors['prix_vente'])): ?>
                                <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['prix_unitaire'] ?? $errors['prix_vente'] ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <label for="cout_achat">Coût Achat (FCFA)</label>
                            <input type="number" name="cout_achat" id="cout_achat" class="form-control" placeholder="Ex: 9000" min="0">
                            <?php if (!empty($errors['cout_achat'])): ?>
                                <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['cout_achat'] ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row" style="display: flex; gap: 12px; margin-top: 12px;">
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <label for="quantite_stock">Stock Initial</label>
                            <input type="number" name="quantite_stock" id="quantite_stock" class="form-control" placeholder="Ex: 50" min="0" >
                            <?php if (!empty($errors['quantite_stock']) || !empty($errors['stock_initial'])): ?>
                                <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['quantite_stock'] ?? $errors['stock_initial'] ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 12px;">
                        <label for="fournisseur_id">Fournisseur Associé (Optionnel)</label>
                        <select name="fournisseur_id" id="fournisseur_id" class="form-control">
                            <option value="0">Aucun fournisseur (Optionnel)</option>
                            <?php foreach ($allFournisseurs as $f): ?>
                                <option value="<?= $f->getId() ?>"><?= $f->getNom() ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-submit btn-success" style="width: 100%; margin-top: 14px;">Enregistrer le Produit</button>
                </form>
            </div>

            <!-- Right: Product list -->
            <div class="panel-card" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <label style="font-size: 13px; font-weight: 700; color: var(--accent); text-transform: uppercase;">Catalogue Courant</label>
                    <form method="GET" action="<?= Helpers::pathUrl("produits", WEB_ROUTE); ?>" style="display: flex; gap: 8px;">
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
                <form method="POST" action="<?= Helpers::pathUrl("clients/add", WEB_ROUTE); ?>">
                    <div class="form-row" style="display: flex; gap: 12px;">
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <label for="prenom">Prénom</label>
                            <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Ex: Abdou" >
                            <?php if (!empty($errors['prenom'])): ?>
                                <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['prenom'] ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" id="nom" class="form-control" placeholder="Ex: Ndiaye" >
                            <?php if (!empty($errors['nom'])): ?>
                                <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['nom'] ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top: 12px;">
                        <label for="telephone">Téléphone</label>
                        <input type="text" name="telephone" id="telephone" class="form-control" placeholder="Ex: 776543210" >
                        <?php if (!empty($errors['telephone'])): ?>
                            <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['telephone'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Ex: client@email.sn">
                        <?php if (!empty($errors['email'])): ?>
                            <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['email'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="limite_credit">Limite de Crédit (FCFA)</label>
                        <input type="number" name="limite_credit" id="limite_credit" class="form-control" value="150000" min="0" >
                        <?php if (!empty($errors['limite_credit'])): ?>
                            <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['limite_credit'] ?></small>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn-submit" style="width: 100%;">Créer le Compte Client</button>
                </form>
            </div>

            <!-- Right: Clients list -->
            <div class="panel-card" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <label style="font-size: 13px; font-weight: 700; color: var(--accent); text-transform: uppercase;">Répertoire Clients</label>
                    <form method="GET" action="<?= Helpers::pathUrl("produits", WEB_ROUTE); ?>" style="display: flex; gap: 8px;">
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
                <form method="POST" action="<?= Helpers::pathUrl("fournisseurs/add", WEB_ROUTE); ?>">
                    <div class="form-group">
                        <label for="nom">Nom de l'Entreprise</label>
                        <input type="text" name="nom" id="nom" class="form-control" placeholder="Ex: Comptoir Céréalier Sénégalais" >
                        <?php if (!empty($errors['nom'])): ?>
                            <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['nom'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="text" name="telephone" id="telephone" class="form-control" placeholder="Ex: 338245678" >
                        <?php if (!empty($errors['telephone'])): ?>
                            <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['telephone'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="adresse">Adresse / Dépôt</label>
                        <input type="text" name="adresse" id="adresse" class="form-control" placeholder="Ex: Hangar 4, Port de Dakar" >
                        <?php if (!empty($errors['adresse'])): ?>
                            <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['adresse'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail (Optionnel)</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Ex: contact@fournisseur.sn">
                        <?php if (!empty($errors['email'])): ?>
                            <small style="color: var(--danger, #ef4444); font-size: 11px; margin-top: 4px; display: block; font-weight: 600;"><?= $errors['email'] ?></small>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn-submit" style="width: 100%;">Créer le Fournisseur</button>
                </form>
            </div>

            <!-- Right: Suppliers list -->
            <div class="panel-card" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <label style="font-size: 13px; font-weight: 700; color: var(--accent); text-transform: uppercase;">Répertoire Fournisseurs</label>
                    <form method="GET" action="<?= Helpers::pathUrl("produits", WEB_ROUTE); ?>" style="display: flex; gap: 8px;">
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
