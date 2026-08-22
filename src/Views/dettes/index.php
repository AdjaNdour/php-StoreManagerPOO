<?php
use App\Core\Helpers;
use App\Model\DTO\FilteredModel;
use App\Model\DTO\PaginationModel;

$allDettes = $viewData['allDettes'] ?? $viewData['dettes'] ?? [];
$stats = $viewData['stats'] ?? $viewData['statistiques'] ?? null;
$modes = $viewData['modes'] ?? $viewData['modesPaiement'] ?? [];

$creancesActives = $stats->total_restant ?? ($viewData['creancesActives'] ?? 0);
$clientsDebiteurs = $stats->nbr_dettes ?? ($viewData['clientsDebiteurs'] ?? count($allDettes));
$totalRecouvrements = $stats->total_verse ?? ($viewData['totalRecouvrements'] ?? 0);
$produitsParDette = $viewData['produitsParDette'] ?? [];
$filteredTableau = $viewData['filteredTableau'] ?? $viewData['filtered'] ?? new FilteredModel();
$pagination = $viewData['pagination'] ?? new PaginationModel();
?>

<div id="view-dettes" class="view-section" style="display: block;">
    <!-- Debts Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--danger); margin-bottom: 0;">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Créances Actives</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;"><?= $creancesActives ?> F</div>
            </div>
            <span style="font-size: 24px;">💸</span>
        </div>
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--warning); margin-bottom: 0;">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Clients Débiteurs</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;"><?= $clientsDebiteurs ?> clients</div>
            </div>
            <span style="font-size: 24px;">👥</span>
        </div>
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--success); margin-bottom: 0;">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Total Recouvrements</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;"><?= $totalRecouvrements ?> F</div>
            </div>
            <span style="font-size: 24px;">📈</span>
        </div>
    </div>

    <div style="display: block; margin-top: 24px;">
        <div class="panel-card" style="margin-bottom: 0;">
            <div class="panel-title" style="flex-wrap: wrap; gap: 12px;">
                <span>Registre des Dettes & Créances</span>
            </div>

            <!-- Filters form -->
            <form method="GET" action="<?php Helpers::pathUrl("dettes"); ?>" class="filtres" style="display: flex; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; background: rgba(11, 15, 25, 0.6); padding: 12px; border-radius: 12px; border: 1px solid var(--border-color); align-items: center;">
                <input type="text" name="search" class="search-control" style="flex: 1; min-width: 200px;" value="<?= $filteredTableau->getFilter('search') ?? '' ?>" placeholder="Rechercher client, réf dette, facture...">

                <select name="statut" class="form-control" style="width: auto; min-width: 150px; padding: 9px 12px; font-size: 12px;" onchange="this.form.submit()">
                    <option value="0">Toutes les dettes</option>
                    <option value="NON SOLDEE" <?= ($filteredTableau->getFilter('statut') === 'NON SOLDEE' || $filteredTableau->getFilter('statut') === 'NON_SOLDEE') ? 'selected' : '' ?>>NON SOLDEE</option>
                    <option value="SOLDEE" <?= $filteredTableau->getFilter('statut') === 'SOLDEE' ? 'selected' : '' ?>>SOLDEE</option>
                </select>

                <button type="submit" class="btn-action" style="padding: 9px 16px;">Filtrer</button>
                <a href="<?php Helpers::pathUrl("dettes"); ?>" class="btn-action" style="text-decoration: none;">Réinitialiser</a>

                <span style="margin-left: auto; font-size: 11px; color: var(--text-muted);">
                    <b><?= $pagination->getTotalElements() ?? 0 ?></b> dette(s)
                </span>
            </form>

            <table class="debt-table" id="debts-main-table">
                <thead>
                    <tr>
                        <th>Réf Dette / Facture</th>
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
                    <?php if (empty($allDettes)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 24px 0;">Aucune dette ne correspond aux critères de recherche.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($allDettes as $dette): ?>
                            <?php
                            $client = $dette->getClient();
                            $clientNom = $client ? $client->getNomComplet() : 'Client';
                            $clientTel = $client ? $client->getTelephone() : '';
                            $paiements = $dette->getPaiements();
                            $produitsDette = $dette->getLignes();
                            $estSoldee = $dette->estSoldee();
                            ?>
                            <tr id="debt-row-<?= $dette->getId() ?>">
                                <td style="font-weight: 700; color: var(--text-muted);">
                                    #<?= $dette->getRef() ?>
                                    <span style="font-size: 10px; color: var(--text-muted); display: block; font-weight: normal; margin-top: 2px;">
                                        #<?= $dette->getVente() ? $dette->getVente()->getNumeroFacture() : ('CMD-' . $dette->getVenteId()) ?>
                                    </span>
                                </td>
                                <td style="font-size: 12px;"><?= $dette->getDateDette() ?? date('d/m/Y') ?></td>
                                <td style="font-weight: 700;">
                                    <?= $clientNom ?>
                                    <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : <?= $clientTel ?></div>
                                </td>
                                <td style="font-weight: 700; color: var(--text-main);"><?= $dette->getMontantInitial() ?> F</td>
                                <td style="font-weight: 700; color: var(--success);"><?= $dette->getMontantVerse() ?> F</td>
                                <td style="color: var(--danger); font-weight: 800;"><?= $dette->getMontantRestant() ?> F</td>
                                <td>
                                    <?php if ($estSoldee): ?>
                                        <span class="badge" style="background: rgba(52, 211, 153, 0.15); color: var(--success); border: 1px solid rgba(52, 211, 153, 0.3);">
                                            SOLDEE
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">
                                            <?= $dette->getStatutDette() ? $dette->getStatutDette()->getNom() : 'NON SOLDEE' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="display: flex; gap: 6px; flex-wrap: wrap;">
                                    <button type="button" class="btn-quick-action" onclick="toggleDetails('debt-lines-<?= $dette->getId() ?>')">
                                        Articles (<?= count($produitsDette) ?>)
                                    </button>
                                    <button type="button" class="btn-quick-action" style="border-color: var(--accent); color: var(--accent);" onclick="toggleDetails('debt-details-<?= $dette->getId() ?>')">
                                        💳 Paiements (<?= count($paiements) ?>)
                                    </button>
                                    <?php if (!$estSoldee): ?>
                                        <button type="button" class="btn-quick-action" style="border-color: var(--warning); color: var(--warning);" onclick="toggleDetails('debt-repay-drawer-<?= $dette->getId() ?>')">
                                            Rembourser
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="8" style="padding: 0; border: none;">
                                    <!-- Drawer 1: Payments list -->
                                    <div class="details-drawer" id="debt-details-<?= $dette->getId() ?>" style="display: none; padding: 16px 20px; background: rgba(8, 12, 24, 0.95); border-top: 1px dashed var(--border-color); border-bottom: 1px dashed var(--border-color);">
                                        <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">
                                            Historique des versements pour #<?= $dette->getRef() ?> :
                                        </div>
                                        <table class="debt-table" style="font-size: 11px; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Versement</th>
                                                    <th>Mode de Règlement</th>
                                                    <th>Notes / Référence</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($paiements)): ?>
                                                    <tr>
                                                        <td colspan="4" style="text-align: center; color: var(--text-muted);">Aucun versement enregistré pour cette dette.</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($paiements as $p): ?>
                                                        <tr>
                                                            <td><?= $p->getDatePaiement() ?? date('d/m/Y') ?></td>
                                                            <td style="font-weight: 700; color: var(--success);"><?= $p->getMontant() ?> F</td>
                                                            <td><span class="badge payee"><?= $p->getModePaiement() ? $p->getModePaiement()->getNom() : 'Espèces' ?></span></td>
                                                            <td style="color: var(--text-muted);"><?= $p->getNotes() ?? '-' ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Drawer 2: Product lines -->
                                    <div class="details-drawer" id="debt-lines-<?= $dette->getId() ?>" style="display: none; padding: 16px 20px; background: rgba(8, 12, 24, 0.95); border-top: 1px dashed var(--border-color); border-bottom: 1px dashed var(--border-color);">
                                        <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">
                                            Articles de la commande rattachée :
                                        </div>
                                        <table class="debt-table" style="font-size: 11px; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Réf / Code</th>
                                                    <th>Produit</th>
                                                    <th>Catégorie</th>
                                                    <th>Quantité</th>
                                                    <th>Prix Unitaire</th>
                                                    <th>Sous-total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($produitsDette)): ?>
                                                    <tr>
                                                        <td colspan="6" style="text-align: center; color: var(--text-muted);">Aucun article rattaché.</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($produitsDette as $lv): ?>
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

                                    <!-- Drawer 3: Remboursement form -->
                                    <?php if (!$estSoldee): ?>
                                        <div class="details-drawer" id="debt-repay-drawer-<?= $dette->getId() ?>" style="display: none; border: 1px solid rgba(45, 212, 191, 0.25); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                                                <div style="display: flex; align-items: center; gap: 8px;">
                                                    <span style="font-size: 16px;">💳</span>
                                                    <span style="font-weight: 800; font-size: 13px; color: var(--text-main);">
                                                        Nouveau Remboursement — <span style="color: var(--accent);"><?= $clientNom ?></span>
                                                    </span>
                                                </div>
                                                <div style="background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.3); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; color: var(--danger);">
                                                    Reste dû : <?= $dette->getMontantRestant() ?> FCFA
                                                </div>
                                            </div>

                                            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 16px;">
                                                <span style="font-size: 10px; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Raccourcis :</span>
                                                <button type="button" onclick="setRepayAmount(<?= $dette->getId() ?>, <?= (int)$dette->getMontantRestant() ?>)" style="background: rgba(45, 212, 191, 0.1); border: 1px solid var(--accent); color: var(--accent); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">Tout solder (<?= $dette->getMontantRestant() ?> F)</button>
                                                <button type="button" onclick="setRepayAmount(<?= $dette->getId() ?>, <?= (int)round($dette->getMontantRestant() / 2) ?>)" style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); color: var(--text-main); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">50% (<?= round($dette->getMontantRestant() / 2) ?> F)</button>
                                            </div>

                                            <form method="POST" action="<?php Helpers::pathUrl('dettes/rembourser'); ?>" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                                                <input type="hidden" name="action" value="add_payment">
                                                <input type="hidden" name="dette_id" value="<?= $dette->getId() ?>">

                                                <div style="flex: 1; min-width: 180px;">
                                                    <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Montant du Versement (FCFA)</label>
                                                    <input type="number" name="montant_verse" id="repay-input-<?= $dette->getId() ?>" class="form-control" max="<?= (int)$dette->getMontantRestant() ?>" value="<?= (int)$dette->getMontantRestant() ?>" min="1" required style="font-size: 13px; font-weight: 700; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;">
                                                </div>

                                                <div style="flex: 1; min-width: 180px;">
                                                    <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Canal de Paiement</label>
                                                    <select name="mode_paiement" class="form-control" style="font-size: 13px; font-weight: 600; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;" required>
                                                        <?php foreach ($modes as $mode): ?>
                                                            <option value="<?= $mode->getId() ?>"><?= $mode->getNom() ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div>
                                                    <button type="submit" class="btn-submit btn-success" style="padding: 11px 24px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; border-radius: 10px; height: 42px;">
                                                        ✓ Enregistrer le Remboursement
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination Component -->
            <?php
            $entityLabel = "dette(s)";
            require PATHBASE . "/src/Views/components/pagination.php";
            ?>
        </div>
    </div>
</div>

<script>
function toggleDetails(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
}

function setRepayAmount(detteId, amount) {
    const input = document.getElementById('repay-input-' + detteId);
    if (input) {
        input.value = amount;
    }
}
</script>