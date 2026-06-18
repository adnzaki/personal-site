<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

    <!-- LEFT COLUMN: MAIN CERITA FEED -->
    <div class="lg:col-span-2 space-y-10">
        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <h3 class="text-lg font-bold text-slate-900 tracking-tight">Semua Cerita</h3>
            <span class="text-xs text-slate-400 font-medium">Menampilkan rilisan artikel berkala</span>
        </div>

        <!-- Feed Article -->
        <?php foreach ($posts as $post) : ?>
            <article class="relative bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col md:flex-row group">

                <div class="relative w-full md:w-64 md:h-auto md:shrink-0 bg-slate-100 overflow-hidden">
                    <img src="<?= $post->media ?>" alt="Article Thumbnail" class="w-full aspect-square h-full object-cover group-hover:scale-105 transition-transform duration-500">

                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm rounded-xl shadow-md p-2.5 min-w-[50px] text-center border border-slate-100 z-20">
                        <span class="block text-xl font-bold text-slate-900 leading-none"><?= substr($post->date, 8, 2) ?></span>
                        <span class="block text-[10px] font-bold text-blue-600 tracking-wider uppercase mt-1"><?= preg_replace('/\d+/', '', osdate()->create($post->date)); ?></span>
                    </div>
                </div>

                <div class="p-6 sm:p-8 flex flex-col justify-between flex-1">
                    <div>
                        <span class="relative z-20 text-[11px] font-bold <?= colorize_category($post->categories) ?> tracking-wider uppercase block mb-2"><?= $post->categories ?></span>

                        <h4 class="text-xl font-bold text-slate-900 leading-snug group-hover:text-blue-600 transition-colors mb-3">
                            <a href="<?= $post->url ?>" class="after:absolute after:inset-0 after:z-10">
                                <?= $post->title ?>
                            </a>
                        </h4>

                        <p class="text-slate-500 text-sm leading-relaxed line-clamp-3">
                            <?= $post->excerpt ?>
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-medium text-slate-400 pt-4 mt-4 border-t border-slate-50 relative z-20">
                        <span class="flex items-center text-slate-500">
                            <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            <?= $post->commentsCount > 0 ? $post->commentsCount . ' komentar' : 'Tidak ada komentar.' ?>
                        </span>

                        <span class="text-blue-600 group-hover:text-blue-800 flex items-center font-semibold pointer-events-none">
                            Baca Selengkapnya
                            <svg class="w-3.5 h-3.5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>


        <!-- Button Lihat Semua Cerita -->
        <!-- Show pagination if this is not homepage -->
        <?php if (isset($notHome) && $notHome): ?>
            <div class="flex justify-center pt-8 border-t border-slate-100">
                <div class="flex items-center space-x-1 font-medium text-sm text-slate-600">
                    <?= $pageLinks ?>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center pt-4">
                <a href="<?= base_url('posts') ?>" class="inline-flex items-center px-6 py-3 border border-slate-200 text-sm font-semibold rounded-xl text-slate-700 bg-white hover:bg-slate-50 hover:text-blue-600 shadow-sm transition-all duration-200 group">
                    LIHAT SEMUA CERITA
                    <svg class="w-4 h-4 ml-2 text-slate-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7-7-7m14-6l-7 7-7-7" />
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- RIGHT COLUMN: SIDEBAR -->
    <?= view('layout/sidebar') ?>

</div>