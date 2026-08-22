<?php
use App\Core\Helpers;

$kpis = $viewData['kpis'] ?? [];
$ventesRecentes = $viewData['ventesRecentes'] ?? $viewData['dernieresVentes'] ?? [];
$dettesDuJour = $viewData['dettesDuJour'] ?? [];
$rupturesAlertes = $viewData['rupturesAlertes'] ?? $viewData['rupturesEtAlertes'] ?? [];
$livraisonsDuJour = $viewData['livraisonsDuJour'] ?? [];
$clientsDebiteurs = $viewData['clientsDebiteurs'] ?? [];
$soldeFournisseurs = $viewData['soldeFournisseurs'] ?? [];
$fournisseurs = $viewData['fournisseurs'] ?? \App\Service\FournisseurService::getAll();
?>

<div id="view-dashboard" class="view-section" style="display: block;">
    <div class="kpi-grid">
        <!-- Radial Chart 1: Ventes Comptant -->
        <div class="kpi-card" style="border-left: 4px solid var(--success);">
            <div>
                <div class="kpi-label">Ventes Comptant</div>
                <div class="kpi-val" style="color: var(--success);"><?= $kpis['ventesComptant'] ?? 0 ?> F</div>
            </div>
            <div class="progress-ring-container">
                <svg class="progress-ring" width="60" height="60">
                    <circle class="progress-ring-circle-bg" cx="30" cy="30" r="25"/>
                    <circle class="progress-ring-circle" style="stroke: var(--success); stroke-dashoffset: 20;" cx="30" cy="30" r="25"/>
                </svg>
            </div>
        </div>

        <!-- Radial Chart 2: Dettes à Récupérer -->
        <div class="kpi-card" style="border-left: 4px solid var(--danger);">
            <div>
                <div class="kpi-label">Dettes à Récupérer</div>
                <div class="kpi-val" style="color: var(--danger);"><?= $kpis['dettesARecuperer'] ?? 0 ?> F</div>
            </div>
            <div class="progress-ring-container">
                <svg class="progress-ring" width="60" height="60">
                    <circle class="progress-ring-circle-bg" cx="30" cy="30" r="25"/>
                    <circle class="progress-ring-circle" style="stroke: var(--danger); stroke-dashoffset: 70;" cx="30" cy="30" r="25"/>
                </svg>
            </div>
        </div>

        <!-- Radial Chart 3: Volume Approvisionné -->
        <div class="kpi-card" style="border-left: 4px solid var(--accent);">
            <div>
                <div class="kpi-label">Volume Approvisionné</div>
                <div class="kpi-val" style="color: var(--accent);"><?= $kpis['volumeApprovisionne'] ?? 0 ?> F</div>
            </div>
            <div class="progress-ring-container">
                <svg class="progress-ring" width="60" height="60">
                    <circle class="progress-ring-circle-bg" cx="30" cy="30" r="25"/>
                    <circle class="progress-ring-circle" style="stroke: var(--accent); stroke-dashoffset: 40;" cx="30" cy="30" r="25"/>
                </svg>
            </div>
        </div>

        <!-- Radial Chart 4: Valeur du Stock -->
        <div class="kpi-card" style="border-left: 4px solid var(--warning);">
            <div>
                <div class="kpi-label">Valeur du Stock</div>
                <div class="kpi-val" style="color: var(--warning);"><?= $kpis['valeurStock'] ?? 0 ?> F</div>
            </div>
            <div class="progress-ring-container">
                <svg class="progress-ring" width="60" height="60">
                    <circle class="progress-ring-circle-bg" cx="30" cy="30" r="25"/>
                    <circle class="progress-ring-circle" style="stroke: var(--warning); stroke-dashoffset: 15;" cx="30" cy="30" r="25"/>
                </svg>
            </div>
        </div>

        <!-- Radial Chart 5: Taux de Recouvrement -->
        <div class="kpi-card" style="border-left: 4px solid var(--success);">
            <div>
                <div class="kpi-label">Taux de Recouvrement</div>
                <div class="kpi-val" style="color: var(--success);"><?= $kpis['tauxRecouvrement'] ?? 0 ?> %</div>
            </div>
            <div class="progress-ring-container">
                <svg class="progress-ring" width="60" height="60">
                    <circle class="progress-ring-circle-bg" cx="30" cy="30" r="25"/>
                    <circle class="progress-ring-circle" style="stroke: var(--success); stroke-dashoffset: 60;" cx="30" cy="30" r="25"/>
                </svg>
            </div>
        </div>

        <!-- Radial Chart 6: Panier Moyen -->
        <div class="kpi-card" style="border-left: 4px solid var(--accent);">
            <div>
                <div class="kpi-label">Panier Moyen</div>
                <div class="kpi-val" style="color: var(--accent);"><?= $kpis['panierMoyen'] ?? 0 ?> F</div>
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
                <button id="dash-left-tab-sales" class="nav-item active" style="flex: 1; padding: 10px; font-size: 11px; text-transform: uppercase; cursor: pointer;" onclick="switchDashLeftTab('sales')">🛒 Ventes Récentes</button>
                <button id="dash-left-tab-debts" class="nav-item" style="flex: 1; padding: 10px; font-size: 11px; text-transform: uppercase; cursor: pointer;" onclick="switchDashLeftTab('debts')">🔴 Dettes du Jour</button>
                <button id="dash-left-tab-ruptures" class="nav-item" style="flex: 1; padding: 10px; font-size: 11px; text-transform: uppercase; cursor: pointer;" onclick="switchDashLeftTab('ruptures')">⚠️ Ruptures & Alertes</button>
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
                            <th>Statut / Mode</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ventesRecentes)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 16px 0;">Aucune vente récente.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ventesRecentes as $v): ?>
                                <tr>
                                    <td style="font-weight: 700; color: var(--text-muted);">#<?= $v->getNumeroFacture() ?></td>
                                    <td><?= $v->getDateVente() ?? date('d/m/Y') ?></td>
                                    <td style="font-weight: 700;"><?= $v->getClient()->getNomComplet() ?></td>
                                    <td style="font-weight: 800; color: var(--accent);"><?= $v->getMontantTotal() ?> F</td>
                                    <td>
                                        <?php if ($v->getStatut() === 'PAYEE'): ?>
                                            <span class="badge payee"><?= $v->getModePaiement() ? $v->getModePaiement()->getNom() : 'PAYEE' ?></span>
                                        <?php elseif ($v->getStatut() === 'AVANCE'): ?>
                                            <span class="badge warning">AVANCE</span>
                                        <?php else: ?>
                                            <span class="badge non-payee">CREDIT</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
                        <?php if (empty($dettesDuJour)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 16px 0;">Aucun crédit en cours créé aujourd'hui.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dettesDuJour as $d): ?>
                                <tr>
                                    <td style="font-weight: 700;"><?= $d->getClient()->getNomComplet() ?></td>
                                    <td><?= $d->getDateDette() ?? date('d/m/Y') ?></td>
                                    <td style="font-weight: 700;"><?= $d->getMontantInitial() ?> F</td>
                                    <td style="font-weight: 800; color: var(--danger);"><?= $d->getMontantRestant() ?> F</td>
                                    <td>
                                        <a href="<?php Helpers::pathUrl("dettes"); ?>" class="btn-quick-action" style="text-decoration: none; border-color: var(--warning); color: var(--warning);">Rembourser</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tab 3: Ruptures & Stocks Critiques -->
            <div id="dash-left-panel-ruptures" style="display: none;">
                <div class="panel-title" style="border-left-color: var(--danger);">Ruptures & Stocks Critiques</div>
                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <?php if (empty($rupturesAlertes)): ?>
                        <div style="text-align: center; color: var(--text-muted); padding: 16px 0;">Tous les stocks sont au-dessus du seuil d'alerte.</div>
                    <?php else: ?>
                        <?php foreach ($rupturesAlertes as $p): ?>
                            <div style="background: rgba(251,191,36,0.05); padding: 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.02);">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-weight: 700; font-size: 13px;"><?= $p->getLibelle() ?></div>
                                        <div style="color: <?= $p->getStockInitial() <= 0 ? 'var(--danger)' : 'var(--warning)' ?>; font-weight: 800; font-size: 11px;"><?= $p->getStockInitial() ?> en stock (seuil: <?= $p->getSeuilAlerte() ?>)</div>
                                    </div>
                                    <button type="button" class="btn-quick-action" onclick="toggleDetails('supply-product-drawer-<?= $p->getId() ?>')" style="border-color: var(--accent); color: var(--accent); background: rgba(45, 212, 191, 0.05);">Approvisionner</button>
                                </div>

                                <!-- Inline drawer for quick supply request -->
                                <div class="details-drawer" id="supply-product-drawer-<?= $p->getId() ?>" style="display: none; margin-top: 10px; padding: 10px; background: rgba(8, 12, 24, 0.95); border-radius: 8px;">
                                    <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Commande d'Approvisionnement Rapide :</div>
                                    <form method="POST" action="<?php Helpers::pathUrl("dashboard/quickSupply"); ?>" style="display: grid; grid-template-columns: 1.5fr 1fr 1fr auto; gap: 8px; align-items: flex-end;">
                                        <input type="hidden" name="produit_id" value="<?= $p->getId() ?>">
                                        <div>
                                            <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 2px;">Fournisseur</label>
                                            <select name="fournisseur_id" class="form-control" style="font-size: 11px; padding: 6px;" required>
                                                <?php foreach ($fournisseurs as $f): ?>
                                                    <option value="<?= $f->getId() ?>" <?= ($p->getFournisseurId() === $f->getId()) ? 'selected' : '' ?>><?= $f->getNom() ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 2px;">Qté</label>
                                            <input type="number" name="quantite" class="form-control" value="50" min="1" required style="font-size: 11px; padding: 6px;">
                                        </div>
                                        <div>
                                            <label style="font-size: 9px; color: var(--text-muted); display: block; margin-bottom: 2px;">Coût Achat (F)</label>
                                            <input type="number" name="cout_achat_unitaire" class="form-control" value="<?= $p->getCoutAchat() > 0 ? $p->getCoutAchat() : round($p->getPrixVente() * 0.7) ?>" min="0" required style="font-size: 11px; padding: 6px;">
                                        </div>
                                        <button type="submit" class="btn-submit btn-success" style="padding: 6px 12px; font-size: 10px; text-transform: uppercase;">Valider BL</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right column card with tabs -->
        <div class="panel-card" style="padding: 20px;">
            <div style="display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                <button id="dash-right-tab-supplies" class="nav-item active" style="flex: 1; padding: 10px; font-size: 11px; text-transform: uppercase; cursor: pointer;" onclick="switchDashRightTab('supplies')">📦 Livraisons du Jour</button>
                <button id="dash-right-tab-debtors" class="nav-item" style="flex: 1; padding: 10px; font-size: 11px; text-transform: uppercase; cursor: pointer;" onclick="switchDashRightTab('debtors')">👥 Clients Débiteurs</button>
                <button id="dash-right-tab-fournisseurs" class="nav-item" style="flex: 1; padding: 10px; font-size: 11px; text-transform: uppercase; cursor: pointer;" onclick="switchDashRightTab('fournisseurs')">🤝 Solde Fournisseurs</button>
            </div>

            <!-- Tab 1: Approvisionnements attendus aujourd'hui -->
            <div id="dash-right-panel-supplies">
                <div class="panel-title" style="border-left-color: var(--warning);">Approvisionnements en attente de réception</div>
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
                        <?php if (empty($livraisonsDuJour)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px 0;">Aucune livraison en attente.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($livraisonsDuJour as $a): ?>
                                <tr>
                                    <td style="font-weight: 700; color: var(--text-muted);"><?= $a->getReferenceBl() ?></td>
                                    <td><?= $a->getFournisseur()->getNom() ?></td>
                                    <td style="font-weight: 800; color: var(--accent);"><?= $a->getCoutAchat() ?> F</td>
                                    <td>
                                        <a href="<?php Helpers::pathUrl("appros"); ?>" class="btn-quick-action" style="text-decoration: none; border-color: var(--success); color: var(--success);">Réceptionner</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
                        <?php if (empty($clientsDebiteurs)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px 0;">Aucun client débiteur.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($clientsDebiteurs as $item): ?>
                                <?php
                                $c = $item['client'];
                                $cId = $c->getId();
                                ?>
                                <tr>
                                    <td style="font-weight: 700;">
                                        <?= $c->getNomComplet() ?>
                                        <div style="font-size: 10px; color: var(--text-muted); font-weight: normal;"><?= $c->getTelephone() ?></div>
                                    </td>
                                    <td style="text-align: center; font-weight: 700;"><?= $item['nbr_dettes'] ?></td>
                                    <td style="font-weight: 800; color: var(--danger);"><?= $item['cumul_du'] ?> F</td>
                                    <td>
                                        <button type="button" class="btn-quick-action" onclick="toggleDetails('client-debts-drawer-<?= $cId ?>')" style="border-color: var(--accent); color: var(--accent); background: rgba(45, 212, 191, 0.05);">Dettes</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="padding: 0; border: none;">
                                        <div class="details-drawer" id="client-debts-drawer-<?= $cId ?>" style="display: none; padding: 12px; background: rgba(8, 12, 24, 0.95); border-radius: 8px;">
                                            <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Dettes en cours de <?= $c->getNomComplet() ?> :</div>
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
                                                    <?php foreach ($item['dettes'] as $dt): ?>
                                                        <tr>
                                                            <td style="font-weight: 700; color: var(--text-muted);">#<?= $dt->getRef() ?></td>
                                                            <td><?= $dt->getDateDette() ?? date('d/m/Y') ?></td>
                                                            <td style="font-weight: 700;"><?= $dt->getMontantInitial() ?> F</td>
                                                            <td style="color: var(--success);"><?= $dt->getMontantVerse() ?> F</td>
                                                            <td style="color: var(--danger); font-weight: 800;"><?= $dt->getMontantRestant() ?> F</td>
                                                            <td>
                                                                <a href="<?php Helpers::pathUrl("dettes"); ?>" class="btn-quick-action" style="text-decoration: none; border-color: var(--danger); color: var(--danger);">Rembourser</a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tab 3: Solde Fournisseurs -->
            <div id="dash-right-panel-fournisseurs" style="display: none;">
                <div class="panel-title" style="border-left-color: var(--accent);">Partenaires Fournisseurs</div>
                <table class="debt-table" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Téléphone</th>
                            <th>Livraisons</th>
                            <th>Volume Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($soldeFournisseurs)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px 0;">Aucun fournisseur enregistré.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($soldeFournisseurs as $sf): ?>
                                <tr>
                                    <td style="font-weight: 700;"><?= $sf->fournisseur_nom ?></td>
                                    <td><?= $sf->fournisseur_telephone ?></td>
                                    <td style="text-align: center; font-weight: 700;"><?= $sf->nbr_appro ?? $sf->total_livraisons ?? 0 ?></td>
                                    <td style="font-weight: 800; color: var(--accent);"><?= $sf->volume_achat ?? $sf->total_volume_appro ?? 0 ?> F</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDetails(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
}

function switchDashLeftTab(tab) {
    document.getElementById('dash-left-tab-sales').classList.remove('active');
    document.getElementById('dash-left-tab-debts').classList.remove('active');
    document.getElementById('dash-left-tab-ruptures').classList.remove('active');

    document.getElementById('dash-left-panel-sales').style.display = 'none';
    document.getElementById('dash-left-panel-debts').style.display = 'none';
    document.getElementById('dash-left-panel-ruptures').style.display = 'none';

    document.getElementById('dash-left-tab-' + tab).classList.add('active');
    document.getElementById('dash-left-panel-' + tab).style.display = 'block';
}

function switchDashRightTab(tab) {
    document.getElementById('dash-right-tab-supplies').classList.remove('active');
    document.getElementById('dash-right-tab-debtors').classList.remove('active');
    document.getElementById('dash-right-tab-fournisseurs').classList.remove('active');

    document.getElementById('dash-right-panel-supplies').style.display = 'none';
    document.getElementById('dash-right-panel-debtors').style.display = 'none';
    document.getElementById('dash-right-panel-fournisseurs').style.display = 'none';

    document.getElementById('dash-right-tab-' + tab).classList.add('active');
    document.getElementById('dash-right-panel-' + tab).style.display = 'block';
}
</script>
