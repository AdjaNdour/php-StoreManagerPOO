<?php
$allDettes = $viewData['allDettes'] ?? [];
$stats = $viewData['stats'] ?? [];
$modes = $viewData['modes'] ?? [];

$creancesActives = $viewData['creancesActives'] ?? 0;
$clientsDebiteurs = $viewData['clientsDebiteurs'] ?? 0;
$totalRecouvrements = $viewData['totalRecouvrements'] ?? 0;
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
                    <?php if (empty($allDettes)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 16px 0;">Aucune dette active en cours.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($allDettes as $dette): ?>
                            <?php
                            $client = $dette->getClient();
                            $clientNom = $client ? $client->getNomComplet() : 'Client';
                            $clientTel = $client ? $client->getTelephone() : '';
                            // $lignes = $detteRepo->selectLignesVenteByVenteId($dette->getVenteId());
                            $paiements = $dette->getPaiements();
                            ?>
                            <tr id="debt-row-<?= $dette->getId() ?>" data-client-name="<?= strtolower($clientNom . ' ' . $clientTel) ?>" style="transition: all 0.2s;">
                                <td style="font-weight: 700; color: var(--text-muted);">
                                    #<?= $dette->getRef() ?>
                                    <span style="font-size: 10px; color: var(--text-muted); display: block; font-weight: normal; margin-top: 2px;">#<?= $dette->getVente() ? $dette->getVente()->getNumeroFacture() : ('CMD-' . $dette->getVenteId()) ?></span>
                                </td>
                                <td style="font-size: 12px;"><?= $dette->getDateDette() ?? date('d M Y') ?></td>
                                <td style="font-weight: 700;">
                                    <?= $clientNom ?>
                                    <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : <?= $clientTel ?></div>
                                </td>
                                <td style="font-weight: 700; color: var(--text-main);"><?= $dette->getMontantInitial() ?> F</td>
                                <td style="font-weight: 700; color: var(--success);"><?= $dette->getMontantVerse() ?> F</td>
                                <td style="color: var(--danger); font-weight: 800;"><?= $dette->getMontantRestant() ?> F</td>
                                <td>
                                    <span class="badge badge-danger">
                                        <?= $dette->getStatutDette() ? $dette->getStatutDette()->getNom() : ($dette->getMontantRestant() <= 0 ? 'SOLDEE' : 'NON SOLDEE') ?>
                                    </span>
                                </td>
                                <td style="display: flex; gap: 6px;">
                                    <button class="btn-quick-action" onclick="toggleDetails('debt-lines-<?= $dette->getId() ?>')">Articles</button>
                                    <button class="btn-quick-action" style="border-color: var(--accent); color: var(--accent);" onclick="toggleDetails('debt-details-<?= $dette->getId() ?>')">💳 Paiements</button>
                                    <button class="btn-quick-action" style="border-color: var(--warning); color: var(--warning);" onclick="toggleDetails('debt-repay-drawer-<?= $dette->getId() ?>')">Rembourser</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding: 0; border: none;">
                                    <!-- Drawer 1: Payments list -->
                                    <div class="details-drawer" id="debt-details-<?= $dette->getId() ?>">
                                        <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Paiements enregistrés :</div>
                                        <table class="debt-table" style="font-size: 11px;">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Versement</th>
                                                    <th>Mode</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($paiements)): ?>
                                                    <tr>
                                                        <td colspan="4" style="text-align: center; color: var(--text-muted);">Aucun versement enregistré.</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($paiements as $p): ?>
                                                        <tr>
                                                            <td><?= $p->getDatePaiement() ?? date('d M Y') ?></td>
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
                                    <div class="details-drawer" id="debt-lines-<?= $dette->getId() ?>">
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
                                                <?php if (empty($lignes)): ?>
                                                    <tr>
                                                        <td colspan="4" style="text-align: center; color: var(--text-muted);">Aucun article.</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($lignes as $ligne): ?>
                                                        <tr>
                                                            <td><?= $ligne->getProduit() ? $ligne->getProduit()->getLibelle() : ('Article #' . $ligne->getProduitId()) ?></td>
                                                            <td><?= $ligne->getQuantite() ?></td>
                                                            <td><?= $ligne->getPrixUnitaire() ?> F</td>
                                                            <td style="font-weight: 700; color: var(--accent);"><?= $ligne->getSousTotal() ?> F</td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Drawer 3: Remboursement form -->
                                    <div class="details-drawer" id="debt-repay-drawer-<?= $dette->getId() ?>" style="border: 1px solid rgba(45, 212, 191, 0.25); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">

                                        <!-- Header row with title and badge -->
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

                                        <!-- Quick preset amount chips -->
                                        <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 16px;">
                                            <span style="font-size: 10px; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Raccourcis :</span>
                                            <button type="button" onclick="setRepayAmount(<?= $dette->getId() ?>, <?= (int)$dette->getMontantRestant() ?>)" style="background: rgba(45, 212, 191, 0.1); border: 1px solid var(--accent); color: var(--accent); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">Tout solder (<?= $dette->getMontantRestant() ?> F)</button>
                                            <button type="button" onclick="setRepayAmount(<?= $dette->getId() ?>, <?= (int)round($dette->getMontantRestant() / 2) ?>)" style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); color: var(--text-main); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">50% (<?= round($dette->getMontantRestant() / 2) ?> F)</button>
                                        </div>

                                        <!-- Form fields grid -->
                                        <form method="POST" action="<?php Helpers::pathUrl('dettes/rembourser'); ?>" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                                            <input type="hidden" name="action" value="add_payment">
                                            <input type="hidden" name="dette_id" value="<?= $dette->getId() ?>">

                                            <div style="flex: 1; min-width: 200px;">
                                                <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Montant du Versement (FCFA)</label>
                                                <div style="position: relative;">
                                                    <input type="number" name="montant_verse" id="repay-input-<?= $dette->getId() ?>" class="form-control" max="<?= (int)$dette->getMontantRestant() ?>" value="<?= (int)$dette->getMontantRestant() ?>" min="1" required style="font-size: 13px; font-weight: 700; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;">
                                                </div>
                                            </div>

                                            <div style="flex: 1; min-width: 200px;">
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
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleDetails(id) {
        const element = document.getElementById(id);

        if (!element) return;

        if (element.style.display === 'block') {
            element.style.display = 'none';
        } else {
            element.style.display = 'block';
        }
    }

    function filterDebtsTable() {
        const input = document.getElementById('debt-search');
        const table = document.getElementById('debts-main-table');

        if (!input || !table) return;

        const search = input.value.toLowerCase();
        const rows = table.querySelectorAll('tbody > tr[id^="debt-row-"]');

        rows.forEach(function(row) {
            const clientName = row.getAttribute('data-client-name') || '';

            if (clientName.includes(search)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';

                const detailsRow = row.nextElementSibling;

                if (detailsRow) {
                    detailsRow.style.display = 'none';
                }
            }
        });
    }

    function setRepayAmount(detteId, amount) {
        const input = document.getElementById('repay-input-' + detteId);

        if (input) {
            input.value = amount;
        }
    }
</script>