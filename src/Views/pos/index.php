<?php
use App\Core\Helpers;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;

$allVentes = $viewData['allVentes'] ?? $viewData['ventes'] ?? [];
$stats = $viewData['stats'] ?? $viewData['statistiques'] ?? null;
$nbrVentes = $stats->nbr_ventes ?? ($viewData['nbrVentes'] ?? count($allVentes));
$montantTotal = $stats->montant_total ?? ($viewData['montantTotal'] ?? 0);
$montantEncaisse = $stats->montant_encaisse ?? ($viewData['montantEncaisse'] ?? 0);
$clients = $viewData['clients'] ?? [];
$produits = $viewData['produits'] ?? [];
$modePaiement = $viewData['modePaiement'] ?? $viewData['modesPaiement'] ?? [];
$panier = $viewData['panier'] ?? [];
$montantTotalPanier = $viewData['montantTotalPanier'] ?? $viewData['panierTotal'] ?? 0;
$filteredTableau = $viewData['filteredTableau'] ?? $viewData['filtered'] ?? new FilteredModel();
$pagination = $viewData['pagination'] ?? new PaginationModel();
?>

<div id="view-pos" class="view-section">
    <div class="pos-stats">
        <div class="pos-stat-card success">
            <div><span>CA Encaissé Net</span><strong><?= $montantEncaisse ?> F</strong></div><span class="stat-icon">💰</span>
        </div>
        <div class="pos-stat-card danger">
            <div><span>Encours Client Total</span><strong><?= ($montantTotal - $montantEncaisse) ?> F</strong></div><span class="stat-icon">🛑</span>
        </div>
        <div class="pos-stat-card accent">
            <div><span>Commandes Enregistrées</span><strong><?= $nbrVentes ?> ventes</strong></div><span class="stat-icon">📊</span>
        </div>
    </div>

    <div class="pos-layout">
        <div class="pos-ticket panel-card">
            <div class="panel-title"><span>🛒 Nouvelle Vente</span><span class="terminal-badge">Terminal POS</span></div>

            <form method="POST" action="<?php Helpers::pathUrl("validerVente"); ?>" id="order-creation-form">
                <input type="hidden" name="action" value="create_order">

                <div class="form-group">
                    <label for="client-select">Client Acheteur</label>
                    <select name="client_id" id="client-select" class="form-control" onchange="updateCreditInfo(this)">
                        <option value="0">Choisir client</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= $client->getId() ?>" data-limite="<?= $client->getLimiteCredit() ?>">
                                <?= $client->getPrenom() . ' ' . $client->getNom() . ' (' . $client->getTelephone() . ')' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="credit-info" id="credit-info-text">Limite de crédit autorisée : Sélectionnez un client</span>
                </div>

                <div class="articles-section">
                    <label class="section-label">Sélection des Articles</label>

                    <div class="article-form">
                        <div class="form-group article-select">
                            <label for="pos-item-select">Produits</label>
                            <select name="produit_id" id="pos-item-select" class="form-control">
                                <option value="0">Choisir produit</option>
                                <?php foreach ($produits as $produit): ?>
                                    <option value="<?= $produit->getId() ?>">
                                        <?= $produit->getLibelle() ?> (Stock: <?= $produit->getStockInitial() ?> | <?= $produit->getPrixVente() ?> F)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group quantity">
                            <label for="pos-qty">Qté</label>
                            <input type="number" name="quantite" id="pos-qty" class="form-control" value="1" min="1">
                        </div>

                        <button type="submit" name="btnSaveVente" value="addPanier" class="btn-add" title="Ajouter au panier">+</button>
                    </div>

                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Qté</th>
                                <th>P.U.</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart-rows">
                            <?php if (empty($panier)): ?>
                                <tr>
                                    <td colspan="5" class="empty-cart">Panier vide. Ajoutez des articles.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($panier as $index => $ligne): ?>
                                    <tr>
                                        <td><?= $ligne['libelle'] ?></td>
                                        <td><?= $ligne['quantite'] ?></td>
                                        <td><?= $ligne['prix_unitaire'] ?> F</td>
                                        <td style="font-weight: 700; color: var(--accent);"><?= $ligne['montant'] ?> F</td>
                                        <td style="text-align: right;">
                                            <button type="submit" name="index" value="<?= $index ?>" formaction="<?php Helpers::pathUrl("supprimerDuPanier"); ?>" formmethod="POST" class="btn-action" style="color: var(--danger); border-color: rgba(248, 113, 113, 0.3);">✕</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="total-display">
                    <span>Montant Total Net à Payer</span>
                    <div><strong id="montant_total_display_text"><?= $montantTotalPanier ?></strong> <small>FCFA</small></div>
                    <input type="hidden" name="montant_total" id="montant_total_display" value="<?= $montantTotalPanier ?>">
                </div>

                <div class="payment-grid">
                    <div class="form-group">
                        <label for="mode_reglement">Règlement</label>
                        <select name="mode_reglement" id="mode_reglement" class="form-control">
                            <option value="0">Choisir le mode</option>
                            <?php foreach ($modePaiement as $mode): ?>
                                <option value="<?= $mode->getId() ?>"><?= $mode->getNom() ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pos-montant-verse">Versé (Avance)</label>
                        <input type="number" name="montant_verse" id="pos-montant-verse" class="form-control" value="<?= $montantTotalPanier ?>" min="0" max="<?= $montantTotalPanier ?>">
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="btnSaveVente" value="addVente" class="btn-submit btn-success" style="flex: 1;">Valider la Vente</button>
                    <?php if (!empty($panier)): ?>
                        <button type="submit" name="btnSaveVente" value="clearPanier" class="btn-action" style="padding: 0 16px; color: var(--danger); border-color: var(--danger);">Vider</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="panel-card sales-registry">
            <div class="panel-title" style="flex-wrap: wrap; gap: 12px;">
                <span>Registre Général des Ventes & Commandes</span>
            </div>

            <form method="GET" action="<?php Helpers::pathUrl("pos"); ?>" class="filtres" style="display: flex; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; background: rgba(11, 15, 25, 0.6); padding: 12px; border-radius: 12px; border: 1px solid var(--border-color); align-items: center;">
                <input type="text" name="search" class="search-control" style="flex: 1; min-width: 180px;" value="<?= $filteredTableau->getFilter('search') ?? '' ?>" placeholder="N° Facture, client, tél...">
                
                <select name="statut" class="form-control" style="width: auto; min-width: 140px; padding: 9px 12px; font-size: 12px;" onchange="this.form.submit()">
                    <option value="0">Tous les statuts</option>
                    <option value="PAYEE" <?= $filteredTableau->getFilter('statut') === 'PAYEE' ? 'selected' : '' ?>>PAYEE</option>
                    <option value="AVANCE" <?= $filteredTableau->getFilter('statut') === 'AVANCE' ? 'selected' : '' ?>>AVANCE</option>
                    <option value="CREDIT" <?= $filteredTableau->getFilter('statut') === 'CREDIT' ? 'selected' : '' ?>>CREDIT</option>
                </select>

                <button type="submit" class="btn-action" style="padding: 9px 16px;">Filtrer</button>
                <a href="<?php Helpers::pathUrl("pos"); ?>" class="btn-action" style="text-decoration: none;">Réinitialiser</a>

                <span style="margin-left: auto; font-size: 11px; color: var(--text-muted);">
                    <b><?= $pagination->getTotalElements() ?? 0 ?></b> vente(s)
                </span>
            </form>

            <table class="sales-table" id="orders-main-table">
                <thead>
                    <tr>
                        <th>N° Facture</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Total</th>
                        <th>Versé</th>
                        <th>Statut</th>
                        <th>Mode</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($allVentes)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 24px 0;">Aucune vente enregistrée.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($allVentes as $vente): ?>
                            <?php
                            $statutVal = $vente->getStatut();
                            $badgeClass = 'danger';
                            if ($statutVal === 'PAYEE') {
                                $badgeClass = 'success';
                            } elseif ($statutVal === 'AVANCE') {
                                $badgeClass = 'warning';
                            }
                            $lignesVente = $vente->getLignes();
                            ?>
                            <tr>
                                <td class="invoice-id">#<?= $vente->getNumeroFacture() ?></td>
                                <td style="font-size: 12px;"><?= $vente->getDateVente() ?? date('d/m/Y') ?></td>
                                <td class="client-name">
                                    <?= $vente->getClient()->getNomComplet() ?>
                                    <small>Tél : <?= $vente->getClient()->getTelephone() ?></small>
                                </td>
                                <td class="invoice-total"><?= $vente->getMontantTotal() ?> F</td>
                                <td style="font-weight: 700; color: var(--success);"><?= $vente->getMontantVerse() ?> F</td>
                                <td><span class="badge <?= $badgeClass ?>"><?= $statutVal ?></span></td>
                                <td style="font-size: 11px; color: var(--text-muted);">
                                    <?= $vente->getModePaiement() ? $vente->getModePaiement()->getNom() : 'Espèces' ?>
                                </td>
                                <td>
                                    <button type="button" class="btn-action" onclick="toggleVenteDetails('vente-lines-<?= $vente->getId() ?>')">
                                        Articles (<?= count($lignesVente) ?>)
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding: 0; border: none;">
                                    <div class="details-drawer" id="vente-lines-<?= $vente->getId() ?>" style="display: none; padding: 14px 20px; background: rgba(8, 12, 24, 0.95); border-top: 1px dashed var(--border-color); border-bottom: 1px dashed var(--border-color);">
                                        <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">
                                            Détail des articles de la facture #<?= $vente->getNumeroFacture() ?> :
                                        </div>
                                        <table class="debt-table" style="font-size: 11px; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Réf / Code</th>
                                                    <th>Article</th>
                                                    <th>Catégorie</th>
                                                    <th>Quantité</th>
                                                    <th>Prix Unitaire</th>
                                                    <th>Sous-total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($lignesVente)): ?>
                                                    <tr>
                                                        <td colspan="6" style="text-align: center; color: var(--text-muted);">Aucun article dans cette commande.</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($lignesVente as $lv): ?>
                                                        <tr>
                                                            <td style="color: var(--text-muted); font-family: monospace; font-weight: bold;"><?= $lv->getProduit()->getCode() ?></td>
                                                            <td style="font-weight: 700;"><?= $lv->getProduit()->getLibelle() ?></td>
                                                            <td><?= $lv->getProduit()->getCategorie() ?></td>
                                                            <td><?= $lv->getQuantite() ?></td>
                                                            <td><?= $lv->getPrixUnitaire() ?> F</td>
                                                            <td style="font-weight: 700; color: var(--accent);"><?= $lv->getSousTotal() ?> F</td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination Component -->
            <?php
            $entityLabel = "vente(s)";
            require PATHBASE . "/src/Views/components/pagination.php";
            ?>
        </div>
    </div>
</div>

<script>
function toggleVenteDetails(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
}

function updateCreditInfo(select) {
    const opt = select.options[select.selectedIndex];
    const limite = opt.getAttribute('data-limite');
    const textEl = document.getElementById('credit-info-text');
    if (limite !== null && limite !== undefined && opt.value !== '0') {
        textEl.textContent = 'Limite de crédit autorisée : ' + limite + ' FCFA';
    } else {
        textEl.textContent = 'Limite de crédit autorisée : Sélectionnez un client';
    }
}
</script>