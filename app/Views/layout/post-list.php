<div class="container">
    <div class="row">
        <div class="col-12 col-lg-9">

            <!-- Single Blog Area  -->
            <?php $delay = 2;
            foreach ($posts as $post) : ?>
                <div class="single-blog-area blog-style-2 mb-50 wow fadeInUp" data-wow-delay="0.<?= $delay ?>s" data-wow-duration="1000ms">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6">
                            <div class="single-blog-thumbnail">
                                <a href="<?= $post->url ?>">
                                    <img src="<?= $post->media ?>" class="blog-img-landscape" alt="Gambar untuk <?= $post->title ?>">
                                </a>
                                <div class="post-date">
                                    <a href="<?= $post->url ?>"><?= substr($post->date, 8, 2) ?>
                                        <span><?= preg_replace('/\d+/', '', osdate()->create($post->date)); ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <!-- Blog Content -->
                            <div class="single-blog-content">
                                <div class="line"></div>
                                <a href="<?= $post->url ?>" class="post-tag"><?= $post->categories ?></a>
                                <h4><a href="<?= $post->url ?>" class="post-headline"><?= $post->title ?></a></h4>
                                <p><?= $post->excerpt ?></p>
                                <div class="post-meta">
                                    <!-- <p>Oleh <a href="<?//= $post->url ?>"><?//= $post->author ?></a></p> -->
                                    <p><?= $post->commentsCount > 0 ? $post->commentsCount . ' komentar' : 'Tidak ada komentar.' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $delay++;
            endforeach; ?>

            <!-- Show pagination if this is not homepage -->
            <?php if (isset($notHome) && $notHome): ?>
                <div class="col-lg-12 text-center">
                    <div class="space30"></div>
                    <?= $pageLinks ?>
                </div>
            <?php else: ?>
                <!-- Load more -->
                <div class="load-more-btn mt-100 wow fadeInUp" data-wow-delay="0.7s" data-wow-duration="1000ms">
                    <a href="<?= base_url('posts') ?>" class="btn original-btn">Lihat Semua Cerita</a>
                </div>
            <?php endif; ?>
        </div>

        <?= view('layout/sidebar') ?>
    </div>
</div>