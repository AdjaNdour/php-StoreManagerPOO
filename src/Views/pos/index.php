<?php
$allVentes = $viewData['allVentes'] ?? [];
$nbrVentes = $viewData['nbrVentes'] ?? 0;
$montantTotal = $viewData['montantTotal'] ?? 0;
$montantEncaisse = $viewData['montantEncaisse'] ?? 0;
$clients = $viewData['clients'] ?? [];
$produits = $viewData['produits'] ?? [];
$modePaiement = $viewData['modePaiement'] ?? [];
$panier = $viewData['panier'] ?? [];
$montantTotalPanier = $viewData['montantTotalPanier'] ?? 0;
?>

<div id="view-pos" class="view-section">
    <div class="pos-stats">
        <div class="pos-stat-card success">
            <div><span>CA Encaissé Net</span><strong><?= number_format($montantEncaisse, 0, ',', ' ') ?> F</strong></div><span class="stat-icon">💰</span>
        </div>
        <div class="pos-stat-card danger">
            <div><span>Encours Client Total</span><strong><?= number_format($montantTotal - $montantEncaisse, 0, ',', ' ') ?> F</strong></div><span class="stat-icon">🛑</span>
        </div>
        <div class="pos-stat-card accent">
            <div><span>Commandes Enregistrées</span><strong><?= $nbrVentes ?> ventes</strong></div><span class="stat-icon">📊</span>
        </div>
    </div>

    <div class="pos-layout">
        <div class="pos-ticket panel-card">
            <div class="panel-title"><span>🛒 Nouvelle Vente</span><span class="terminal-badge">Terminal POS</span></div>

            <form method="POST" action="validerVente" id="order-creation-form">
                <input type="hidden" name="action" value="create_order">

                <div class="form-group">
                    <label for="client-select">Client Acheteur</label>
                    <select name="client_id" id="client-select" class="form-control">
                        <option value="0">Choisir client</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= $client->getId() ?>"><?= $client->getPrenom() . ' ' . $client->getNom() . ' (' . $client->getTelephone() . ')' ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="credit-info">Limite de crédit autorisée : 300 000 FCFA</span>
                </div>

                <div class="articles-section">
                    <label class="section-label">Sélection des Articles</label>

                    <div class="article-form">
                        <div class="form-group article-select">
                            <label for="pos-item-select">Produits</label>
                            <select name="produit_id" id="pos-item-select" class="form-control">
                                <option value="0">Choisir produit</option>
                                <?php foreach ($produits as $produit): ?>
                                    <option value="<?= $produit->getId() ?>"><?= $produit->getLibelle() ?>, Quantité dispo : <?= $produit->getStockInitial() ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group quantity">
                            <label for="pos-qty">Qté</label>
                            <input type="number" name="quantite" id="pos-qty" class="form-control" value="1" min="1">
                        </div>

                        <button type="submit" name="btnSaveVente" value="addPanier" class="btn-add">+</button>
                    </div>

                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Qté</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart-rows">

                            <?php if (empty($panier)): ?>
                                <tr>
                                    <td colspan="4" class="empty-cart">Panier vide. Ajoutez des articles.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($panier as $index => $ligne): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($ligne['libelle']) ?></td>
                                        <td><?= $ligne['quantite'] ?></td>
                                        <td><?= number_format($ligne['montant'], 0, ',', ' ') ?> FCFA</td>
                                        <td><button type="submit" name="index" value="<?= $index ?>" formaction="supprimerDuPanier" formmethod="POST" class="btn-action">Supprimer</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

                <div class="total-display">
                    <span>Montant Total Net à Payer</span>
                    <div><strong id="montant_total_display_text"><?= number_format($montantTotalPanier, 0, ',', ' ') ?></strong><small>FCFA</small></div>
                    <input type="hidden" name="montant_total" id="montant_total_display" value="<?= $montantTotalPanier ?>">
                </div>

                <div class="payment-grid">
                    <div class="form-group">
                        <label for="mode_reglement">Règlement</label>
                        <select name="mode_reglement" id="mode_reglement" class="form-control">
                            <option value="0">Choisir le mode de règlement</option>
                            <?php foreach ($modePaiement as $mode): ?>
                                <option value="<?= $mode->getId() ?>"><?= $mode->getNom() ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pos-montant-verse">Versé (Avance)</label>
                        <input type="number" name="montant_verse" id="pos-montant-verse" class="form-control" value="0" min="0">
                    </div>
                </div>

                <button type="submit" name="btnSaveVente" value="addVente" class="btn-submit btn-success">Valider la Vente</button>
            </form>
        </div>

        <div class="panel-card sales-registry">
            <div class="panel-title">Registre Général des Ventes & Commandes</div>

            <table class="sales-table" id="orders-main-table">
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
                    <?php foreach ($allVentes as $vente): ?>
                        <tr>
                            <td class="invoice-id"><?= $vente->getId() ?></td>
                            <td class="client-name"><?= $vente->getClient()->getNom() ?> <small>Tél : <?= $vente->getClient()->getTelephone() ?></small></td>
                            <td class="invoice-total"><?= number_format($vente->getMontantTotal(), 0, ',', ' ') ?> FCFA</td>
                            <td><span class="badge danger"><?= $vente->getStatut() ?></span></td>
                            <td><button type="button" class="btn-action">Lignes</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>