<?php
use Adja\Core\Helpers;

$allAppros = $viewData['appros'] ?? [];
$stats = $viewData['statistiques'] ?? null;
$totalCoutAppro = $stats->total_cout_appro ?? 0;
$totalBl = $stats->total_bl ?? 0 ;
$totalFournisseursActifs = $stats->total_fournisseurs_actifs ?? 0 ;
$filteredTableau = $viewData['filteredTableau'] ?? null;
$pagination = $viewData['pagination'] ?? null;
?>

<div id="view-supplies" class="view-section" style="display: block;">
    <!-- Supplies Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--accent); margin-bottom: 0;">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Coût Total des Entrées</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;"><?= $totalCoutAppro ?> F</div>
            </div>
            <span style="font-size: 24px;">📥</span>
        </div>

        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--warning); margin-bottom: 0;">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Bons de Réception (BL)</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;"><?= $totalBl ?> BL reçus</div>
            </div>
            <span style="font-size: 24px;">📄</span>
        </div>

        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--success); margin-bottom: 0;">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Fournisseurs Actifs</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;"><?= $totalFournisseursActifs ?> entreprises</div>
            </div>
            <span style="font-size: 24px;">🤝</span>
        </div>
    </div>

    <!-- Deliveries table -->
    <div class="panel-card" style="padding: 20px; margin-bottom: 0;">
        <div class="panel-title" style="flex-wrap: wrap; gap: 12px; margin-bottom: 16px;">
            <span>Bordereaux de Livraison (Réceptions)</span>
        </div>

        <!-- Filters form -->
        <form method="GET" action="<?= Helpers::pathUrl("appros", WEB_ROUTE); ?>" class="filtres" style="display: flex; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; background: rgba(11, 15, 25, 0.6); padding: 12px; border-radius: 12px; border: 1px solid var(--border-color); align-items: center;">
            <input type="text" name="search" class="search-control" style="flex: 1; min-width: 180px;" value="<?= $filteredTableau->getFilter('search') ?? '' ?>" placeholder="Rechercher Réf BL, fournisseur...">

            <select name="statut" class="form-control" style="width: auto; min-width: 140px; padding: 9px 12px; font-size: 12px;" onchange="this.form.submit()">
                <option value="0">Tous les statuts</option>
                <option value="EN_COURS" <?= ($filteredTableau->getFilter('statut') === 'EN_COURS' || $filteredTableau->getFilter('statut') === 'EN COURS') ? 'selected' : '' ?>>EN COURS</option>
                <option value="RECU" <?= $filteredTableau->getFilter('statut') === 'RECU' ? 'selected' : '' ?>>REÇU</option>
            </select>

            <button type="submit" class="btn-action" style="padding: 9px 16px;">Filtrer</button>
            <a href="<?= Helpers::pathUrl("appros", WEB_ROUTE); ?>" class="btn-action" style="text-decoration: none;">Réinitialiser</a>

            <span style="margin-left: auto; font-size: 11px; color: var(--text-muted);">
                <b><?= $pagination->getTotalElements() ?? 0 ?></b> bon(s)
            </span>
        </form>

        <table class="debt-table" id="supplies-main-table" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Réf BL</th>
                    <th>Fournisseur</th>
                    <th>Valeur Lot</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($allAppros)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px 0;">
                            Aucun bon de livraison enregistré.
                        </td>
                    </tr>
                <?php else: ?>

                    <?php foreach ($allAppros as $appro): ?>
                        <?php
                        $estRecu = !empty($appro->getDateReception());
                        $lignesApprovisionnement = $appro->getLignes();
                        ?>

                        <tr>
                            <td style="font-weight: 700; color: var(--text-muted); padding: 10px 0;">
                                <?= $appro->getReferenceBl() ?>
                                <div style="font-size: 10px; color: var(--text-muted);">
                                    <?= $appro->getDateAppro() ?? date('d/m/Y') ?>
                                </div>
                            </td>

                            <td style="padding: 10px 0;">
                                <div style="font-weight: 700;">
                                    <?= $appro->getFournisseur() ? $appro->getFournisseur()->getNom() : 'Fournisseur' ?>
                                </div>
                                <div style="font-size:10px; color:var(--text-muted);">
                                    Tél : <?= $appro->getFournisseur() ? $appro->getFournisseur()->getTelephone() : '' ?>
                                </div>
                            </td>

                            <td style="font-weight: 800; color: var(--accent); padding: 10px 0;">
                                <?= $appro->getCoutAchat() ?> F
                            </td>

                            <td style="padding: 10px 0;">
                                <?php if ($estRecu): ?>
                                    <span class="badge badge-success" style="background: rgba(52, 211, 153, 0.15); color: var(--success); border: 1px solid rgba(52, 211, 153, 0.3);">
                                        REÇU
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-warning">
                                        EN COURS
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td style="padding: 10px 0; display: flex; gap: 6px; flex-wrap: wrap;">
                                <button type="button" class="btn-quick-action" onclick="toggleDetails('supply-details-<?= $appro->getId() ?>')">
                                    Lignes (<?= count($appro->getLignes()) ?>)
                                </button>

                                <?php if (!$estRecu): ?>
                                    <button type="button" class="btn-quick-action" style="border-color: var(--success); color: var(--success); background: rgba(52, 211, 153, 0.05);" onclick="toggleDetails('supply-receive-drawer-<?= $appro->getId() ?>')">
                                        Réceptionner
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="5" style="padding: 0; border: none;">

                                <!-- Drawer 1: Supply lines -->
                                <div class="details-drawer" id="supply-details-<?= $appro->getId() ?>" style="display: none; padding: 14px 20px; background: rgba(8, 12, 24, 0.95); border-top: 1px dashed var(--border-color); border-bottom: 1px dashed var(--border-color);">

                                    <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">
                                        Détails Réception :
                                    </div>

                                    <table class="debt-table" style="font-size: 10px; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Qté Commandée</th>
                                                <th>Qté Reçue</th>
                                                <th>Coût Unitaire</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($appro->getLignes() as $ligneApprov): ?>
                                                <tr>
                                                    <td style="font-weight: 700;"><?= $ligneApprov->getProduit()->getLibelle() ?></td>
                                                    <td><?= $ligneApprov->getQuantiteAppro() ?> </td>
                                                    <td><?= $ligneApprov->getQuantiteRecue() ?> </td>
                                                    <td><?= $ligneApprov->getPrixAchat() ?> F </td>
                                                    <td style="font-weight: 700; color: var(--accent);"><?= $ligneApprov->getSousTotal() ?> F</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Drawer 2: Confirm Reception Form inline -->
                                <?php if (!$estRecu): ?>
                                    <div class="details-drawer" id="supply-receive-drawer-<?= $appro->getId() ?>" style="display: none; border: 1px solid rgba(52, 211, 153, 0.3); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">

                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">

                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <span style="font-size: 16px;">📦</span>

                                                <span style="font-weight: 800; font-size: 13px; color: var(--text-main);">
                                                    Réceptionner le BL —
                                                    <span style="color: var(--accent);">
                                                        <?= $appro->getReferenceBl() ?>
                                                    </span>
                                                </span>
                                            </div>

                                            <div style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; color: var(--warning);">
                                                Fournisseur :
                                                <?= $appro->getFournisseur() ? $appro->getFournisseur()->getNom() : '' ?>
                                            </div>
                                        </div>

                                        <form method="POST" action="<?= Helpers::pathUrl("appros/receive", WEB_ROUTE); ?>">

                                            <input type="hidden" name="approvisionnement_id" value="<?= $appro->getId() ?>">

                                            <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">

                                                <?php foreach ($appro->getLignes() as $ligneApprov): ?>

                                                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 10px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">

                                                        <div>
                                                            <div style="font-weight: 700; font-size: 13px; color: white;">
                                                                <?= $ligneApprov->getProduit()->getLibelle() ?>
                                                            </div>

                                                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                                                Quantité théorique commandée :
                                                                <strong style="color: var(--text-main);">
                                                                    <?= $ligneApprov->getQuantiteAppro() ?>
                                                                </strong>
                                                            </div>
                                                        </div>

                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">
                                                                Qté Reçue :
                                                            </label>

                                                            <input type="number" name="quantites_demandees[<?= $ligneApprov->getId() ?>]" class="form-control" value="<?= $ligneApprov->getQuantiteAppro() ?>" min="0" 
                                                                   style="width: 100px; padding: 6px 10px; font-size: 13px; font-weight: 700; text-align: center; background: #0b0f1a;">
                                                        </div>

                                                    </div>

                                                <?php endforeach; ?>

                                            </div>

                                            <div style="display: flex; justify-content: flex-end;">
                                                <button type="submit" class="btn-submit btn-success" style="padding: 11px 24px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; border-radius: 10px;">
                                                    ✓ Valider la Réception en Stock
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
        $entityLabel = "bon(s)";
        require PATHBASE . "/src/Views/components/pagination.php";
        ?>

    </div>
</div>

<script>
function toggleDetails(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
}
</script>

