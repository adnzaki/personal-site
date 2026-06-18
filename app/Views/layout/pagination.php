<?php

/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Navigasi halaman" class="w-full flex justify-center my-8">
    <ul class="inline-flex items-center -space-x-px rounded-xl bg-white p-1.5 shadow-sm border border-slate-100">

        <li>
            <?php if ($pager->hasPreviousPage()) : ?>
                <a href="<?= $pager->getPreviousPage() ?>" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-blue-600 rounded-lg transition-all duration-200 group">
                    <svg class="w-4 h-4 mr-1.5 text-slate-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Sebelumnya
                </a>
            <?php else : ?>
                <span class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-slate-300 bg-white cursor-not-allowed rounded-lg">
                    <svg class="w-4 h-4 mr-1.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Sebelumnya
                </span>
            <?php endif; ?>
        </li>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <?php if ($link['active']) : ?>
                    <span aria-current="page" class="inline-flex items-center justify-center min-w-[40px] h-10 px-3.5 text-sm font-bold text-white bg-blue-600 rounded-lg shadow-sm shadow-blue-600/10 select-none">
                        <?= $link['title'] ?>
                    </span>
                <?php else : ?>
                    <a href="<?= $link['uri'] ?>" class="inline-flex items-center justify-center min-w-[40px] h-10 px-3.5 text-sm font-semibold text-slate-600 bg-white hover:bg-slate-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                        <?= $link['title'] ?>
                    </a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>

        <li>
            <?php if ($pager->hasNextPage()) : ?>
                <a href="<?= $pager->getNextPage() ?>" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-blue-600 rounded-lg transition-all duration-200 group">
                    Berikutnya
                    <svg class="w-4 h-4 ml-1.5 text-slate-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            <?php else : ?>
                <span class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-slate-300 bg-white cursor-not-allowed rounded-lg">
                    Berikutnya
                    <svg class="w-4 h-4 ml-1.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            <?php endif; ?>
        </li>

    </ul>
</nav>