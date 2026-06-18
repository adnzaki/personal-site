<aside class="space-y-8">
    <!-- Search Component -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <label for="searchForm" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Cari Cerita</label>

        <form action="<?= base_url('posts') ?>" method="get" class="relative m-0">
            <input type="search" name="search" id="searchForm" value="<?= isset($_GET['search']) ? esc($_GET['search']) : '' ?>" placeholder="Cari cerita..." class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl pl-4 pr-12 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">

            <button type="submit" class="absolute right-3 top-2.5 p-1.5 text-slate-400 hover:text-blue-600 rounded-lg focus:outline-none transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>

    <!-- Trending Component (Cerita Terhangat) -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div class="flex items-center space-x-2 border-b border-slate-100 pb-3 mb-4">
            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <h4 class="text-sm font-bold text-slate-900 tracking-tight">Cerita Terhangat</h4>
        </div>

        <div class="space-y-4">
            <?php foreach ($popularPosts as $post) : ?>
                <!-- Mini Trending Item -->
                <a href="<?= $post->url ?>" class="flex items-start space-x-3 group">
                    <img src="<?= $post->media ?>" alt="<?= $post->title ?>" class="w-16 h-16 object-cover rounded-xl shrink-0 bg-slate-100">
                    <div>
                        <span class="text-[10px] font-bold <?= colorize_category($post->categories) ?> uppercase tracking-wide block"><?= $post->categories ?></span>
                        <h5 class="text-xs font-semibold text-slate-800 leading-snug group-hover:text-blue-600 transition-colors line-clamp-2 mt-0.5">
                            <?= $post->title ?>
                        </h5>
                        <span class="text-[10px] text-slate-400 block mt-1"><?= osdate()->create($post->date, 'd-M-y') ?></span>
                    </div>
                </a>
            <?php endforeach; ?>


        </div>
    </div>
</aside>