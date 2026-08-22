<?php

use App\Model\DTO\PaginationModel;
use App\Model\DTO\FilteredModel;

$pagination = $pagination ?? ($viewData['pagination'] ?? new PaginationModel());
$filteredTableau = $filteredTableau ?? ($viewData['filteredTableau'] ?? new FilteredModel());
$label = $entityLabel ?? ($viewData['entityLabel'] ?? "élément(s)");
$extra = $extraParams ?? ($viewData['extraParams'] ?? []);

$totalElements = $pagination->getTotalElements() ?? 0;
$start = $pagination->getStart() ?? 0;
$end = $pagination->getEnd() ?? 0;
$currentPage = $pagination->getCurrentPage() ?? 1;
$totalPages = $pagination->getTotalPages() ?? 1;

$params = $extra;
if ($filteredTableau instanceof FilteredModel) {
    foreach ($filteredTableau->getFilters() as $k => $v) {
        if ($v !== null && $v !== '' && $v !== '0' && $v !== 'ALL') {
            $params[$k] = $v;
        }
    }
}

$queryString = http_build_query($params);
$querySuffix = $queryString ? '&' . $queryString : '';
?>

<div class="table-footer">
    <span>
        <?php if ($totalElements > 0): ?>
            Affichage de <b><?= $start ?></b> à <b><?= $end ?></b> sur <b><?= $totalElements ?></b> <?= $label ?>
        <?php else: ?>
            0 <?= $label ?>
        <?php endif; ?>
    </span>

    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= ($currentPage - 1) . $querySuffix ?>" class="page-link" title="Page précédente">&laquo;</a>
        <?php else: ?>
            <span class="page-link disabled">&laquo;</span>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == $currentPage): ?>
                <span class="page-link active"><?= $i ?></span>
            <?php else: ?>
                <a href="?page=<?= $i . $querySuffix ?>" class="page-link"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?= ($currentPage + 1) . $querySuffix ?>" class="page-link" title="Page suivante">&raquo;</a>
        <?php else: ?>
            <span class="page-link disabled">&raquo;</span>
        <?php endif; ?>
    </div>
</div>
