<div class="p-1 mb-3 bg-transparent">
    <div class="pagination justify-content-center">
        <?php if ($currentPage !== 1) : ?>
            <a href="?<?= $urlParameter; ?>&page=1" class="btn btn-primary mr-2"><i class="fas fa-angle-double-left"></i> First</a> &nbsp;
            <a href="?<?= $urlParameter; ?>&page=<?= (sanitizePageNumber($currentPage - 1, $totalPages)); ?>" class="btn btn-primary mr-2"><i class="fas fa-angle-left"></i> Previous</a>
        <?php endif; ?>

        <?php
        $startPage = max(1, $currentPage - 1);
        $endPage = min($totalPages, $currentPage + 1);
        for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = ($i === $currentPage) ? 'btn-custom-success active' : '';
            ?>
            &nbsp;<a href="?<?= $urlParameter; ?>&page=<?= $i; ?>" class="btn <?= $activeClass; ?>"><?= $i; ?></a>
        <?php } ?>

        <?php if ($currentPage < $totalPages) : ?>
            &nbsp;<a href="?<?= $urlParameter; ?>&page=<?= (sanitizePageNumber($currentPage + 1, $totalPages)); ?>" class="btn btn-primary mr-2">Next <i class="fas fa-angle-right"></i></a>
            &nbsp;<a href="?<?= $urlParameter; ?>&page=<?= $totalPages; ?>" class="btn btn-primary mr-2">Last <i class="fas fa-angle-double-right"></i></a>
        <?php endif; ?>
    </div>
</div>
