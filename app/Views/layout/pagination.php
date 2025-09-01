<?php

/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */
$pager->setSurroundCount(2)
?>

<nav aria-label="Navigasi halaman">
    <ul class="pagination justify-content-center">
        <li class="page-item <?= $pager->hasPreviousPage() ? '' : 'disabled' ?>">
            <a class="page-link" href="<?= $pager->getPreviousPage() ?>" tabindex="-1" aria-disabled="true">Sebelumnya</a>
        </li>
        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item" <?= $link['active'] ? 'aria-current="page"' : '' ?>>
                <a class="<?= $link['active'] ? 'page-link active' : 'page-link' ?>" href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach; ?>

        <li class="page-item <?= $pager->hasNextPage() ? '' : 'disabled' ?>">
            <a class="page-link" href="<?= $pager->getNextPage() ?>">Berikutnya</a>
        </li>
    </ul>
</nav>