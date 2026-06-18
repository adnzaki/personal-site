<?php if (! empty($post)): ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start mb-16">

        <div class="lg:col-span-2 space-y-6">

            <div class="w-full bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

                <?php if (!empty($post->singlePostImage)): ?>
                    <div class="w-full overflow-hidden aspect-[16/9] md:aspect-[21/9] bg-slate-100">
                        <img src="<?= $post->singlePostImage ?>" alt="<?= esc($post->title) ?>" class="w-full h-full object-cover">
                    </div>
                <?php endif; ?>

                <div class="p-6 sm:p-8 lg:p-10">

                    <div class="flex flex-wrap items-center text-xs sm:text-sm font-medium text-slate-400 gap-y-2 gap-x-4 mb-4 border-b border-slate-50 pb-4">
                        <span class="flex items-center text-slate-500">
                            <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <?= osdate()->create($post->date, 'd-MM-y') ?>
                        </span>

                        <span class="flex items-center text-slate-500">
                            <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <?= esc($post->author) ?>
                        </span>

                        <span class="flex items-center text-blue-600 font-semibold uppercase tracking-wider text-[11px]">
                            <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20n4n-4n4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <?= esc($post->categories) ?>
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 leading-tight mb-6">
                        <?= $post->title ?>
                    </h1>

                    <article class="prose prose-slate max-w-none prose-headings:font-bold prose-headings:text-slate-900 prose-p:leading-relaxed prose-p:text-slate-600 prose-a:text-blue-600 hover:prose-a:text-blue-800 mb-8">
                        <?= $post->content ?>
                    </article>

                    <?php if ($post->tags) : ?>
                        <div class="flex flex-wrap items-center gap-2 pt-6 border-t border-slate-100">
                            <span class="text-xs font-semibold text-slate-400 uppercase mr-1">Tags:</span>
                            <?php foreach ($post->tags as $tag) : ?>
                                <a href="<?= base_url('posts/tag/' . $tag->name) ?>" class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-slate-50 text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-colors border border-slate-100">
                                    #<?= esc($tag->name) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-4 sm:gap-6">
                <img class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover shadow-sm bg-white border-2 border-white ring-4 ring-slate-100 shrink-0" src="<?= $post->authorImage ?>" alt="<?= esc($post->author) ?>">
                <div class="flex-1">
                    <span class="text-[10px] font-bold tracking-widest text-slate-400 uppercase block mb-1">Penulis Artikel</span>
                    <h4 class="text-lg font-bold text-slate-900 mb-2"><?= esc($post->author) ?></h4>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-2xl"><?= esc($post->authorBio) ?></p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 lg:p-10">
                <h3 class="text-lg sm:text-xl font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Komentar (<?= $post->commentsCount ?>)
                </h3>

                <ol class="space-y-6">
                    <?php
                    if (!function_exists('renderTailwindComments')) {
                        function renderTailwindComments($comments)
                        {
                            if (empty($comments)) return;
                            foreach ($comments as $comment) : ?>
                                <li class="group/comment" data-id="<?= $comment->id ?>">
                                    <div class="flex gap-4 items-start">
                                        <img class="w-10 h-10 sm:w-11 sm:h-11 rounded-full object-cover bg-slate-100 border border-slate-100 shrink-0"
                                            src="<?= esc($comment->author_avatar_urls->{'48'} ?? 'https://www.gravatar.com/avatar/?d=mm&s=48'); ?>"
                                            alt="<?= esc($comment->author_name); ?>">

                                        <div class="flex-1 bg-slate-50 rounded-2xl p-4 border border-slate-100/60 relative">
                                            <div class="flex flex-wrap items-baseline justify-between gap-1 mb-2">
                                                <h5 class="text-sm font-bold text-slate-900"><?= esc($comment->author_name); ?></h5>
                                                <span class="text-[11px] text-slate-400 font-medium"><?= osdate()->create($comment->date, 'd M Y'); ?></span>
                                            </div>
                                            <p class="text-sm text-slate-600 leading-relaxed"><?= esc(strip_tags($comment->content->rendered)); ?></p>

                                            <div class="mt-2 flex justify-end">
                                                <a href="#reply-to-<?= $comment->id ?>" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center">
                                                    Balas
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (!empty($comment->replies)) : ?>
                                        <ol class="pl-6 sm:pl-12 mt-4 space-y-4 border-l-2 border-slate-100 ml-5 sm:ml-5.5">
                                            <?php renderTailwindComments($comment->replies); ?>
                                        </ol>
                                    <?php endif; ?>
                                </li>
                    <?php endforeach;
                        }
                    }
                    renderTailwindComments($comments);
                    ?>
                </ol>

                <div id="comment-form-wrapper" class="mt-10 pt-8 border-t border-slate-100">
                    <h4 id="comment-form-title" class="text-base sm:text-lg font-bold text-slate-900 mb-6">Tinggalkan Balasan</h4>
                    <form action="<?= base_url('add-comment') ?>" method="post" id="comment-form" class="space-y-4">
                        <input type="hidden" name="parent_id" id="parent_id" value="0">
                        <input type="hidden" name="post_id" id="post_id" value="<?= $post->id ?>">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nama Anda</label>
                                <input type="text" name="name" id="name" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                                <input type="email" name="email" id="email" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Isi Komentar</label>
                            <textarea name="message" id="message" rows="4" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all resize-none"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" id="cancel-reply" class="hidden px-4 py-2.5 text-sm font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors">Batal</button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-colors">Kirim Komentar</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <?= view('layout/sidebar') ?>

        

    </div>
<?php else: ?>
    <div class="w-full bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center my-10">
        <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h2 class="text-xl font-bold text-slate-800 mb-2">Artikel Tidak Ditemukan</h2>
        <p class="text-sm text-slate-500">Maaf, artikel yang Anda cari telah dipindahkan atau tidak lagi tersedia.</p>
    </div>
<?php endif; ?>