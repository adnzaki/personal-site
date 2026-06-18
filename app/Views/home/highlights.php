<section class="mb-16">
    <div class="flex items-center space-x-2 mb-6">
        <span class="w-3 h-3 bg-blue-600 rounded-full"></span>
        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">Cerita Terbaru</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <a href="<?= $latestPosts[0]->url ?>" class="lg:col-span-2 relative group overflow-hidden rounded-2xl bg-slate-400 shadow-xl aspect-square sm:aspect-video lg:aspect-auto lg:h-[475px] w-full block">

            <img src="<?= $latestPosts[0]->media ?>" alt="Gambar untuk <?= $latestPosts[0]->title ?>" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:scale-105 transition-transform duration-700 ease-out z-0">

            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent z-10"></div>

            <div class="absolute inset-0 p-5 sm:p-8 lg:p-10 flex flex-col justify-end z-20">

                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-semibold bg-blue-500 text-white w-max mb-2 sm:mb-4">
                    <?= $latestPosts[0]->categories ?>
                </span>

                <h3 class="text-base sm:text-2xl lg:text-4xl font-bold text-white leading-snug sm:leading-tight mb-2 sm:mb-4 group-hover:text-blue-200 transition-colors">
                    <?= $latestPosts[0]->title ?>
                </h3>

                <p class="text-slate-200 text-xs sm:text-sm font-normal max-w-2xl mb-4 opacity-90 line-clamp-2 sm:line-clamp-3 leading-relaxed">
                    <?= $latestPosts[0]->excerpt ?>
                </p>

                <div class="flex items-center text-[11px] sm:text-sm text-slate-300 space-x-4 font-medium pt-2 sm:pt-0 border-t border-slate-800/40 sm:border-t-0">
                    <span class="flex items-center shrink-0">
                        <svg class="w-3.5 h-3.5 mr-1 sm:w-4 sm:h-4 sm:mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <?= osdate()->create($latestPosts[0]->date) ?>
                    </span>
                    <span class="flex items-center shrink-0">
                        <svg class="w-3.5 h-3.5 mr-1 sm:w-4 sm:h-4 sm:mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        <?= $latestPosts[0]->commentsCount ?> Komentar
                    </span>
                </div>
            </div>
        </a>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6">
            <?php $index = 1;
            foreach (array_slice($latestPosts, 1, 3) as $post) : ?>

                <a href="<?= $post->url ?>" class="rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between relative group hover:shadow-md transition-all duration-300 min-h-[200px] overflow-hidden bg-slate-900 block">

                    <?php if (!empty($post->singlePostImage)): ?>
                        <img src="<?= $post->singlePostImage ?>" alt="<?= esc($post->title) ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 z-0 opacity-40">
                    <?php endif; ?>

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/80 to-transparent z-10"></div>

                    <div class="relative z-20">
                        <span class="text-[11px] font-bold tracking-wider <?= colorize_category($post->categories) ?> uppercase block mb-2">
                            <?= $post->categories ?>
                        </span>

                        <h4 class="text-base font-bold text-white leading-snug group-hover:text-blue-300 transition-colors line-clamp-2">
                            <?= $post->title ?>
                        </h4>

                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            <?= $post->excerpt ?>
                        </p>
                    </div>

                    <div class="text-[11px] text-slate-400 font-medium mt-6 pt-3 border-t border-slate-700/50 flex justify-between relative z-20">
                        <span><?= osdate()->create($post->date) ?></span>
                        <span><?= $post->commentsCount ?> Komentar</span>
                    </div>

                </a>

            <?php $index++;
            endforeach; ?>
        </div>
    </div>
</section>