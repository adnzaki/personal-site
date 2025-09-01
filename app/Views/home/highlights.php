<!-- ##### Blog Wrapper Start ##### -->
<div class="blog-wrapper section-padding-100 clearfix">
    <div class="container">
        <div class="row align-items-end">
            <!-- Single Blog Area -->
            <div class="col-12 col-lg-4">
                <div class="single-blog-area clearfix mb-100">
                    <!-- Blog Content -->
                    <div class="single-blog-content">
                        <div class="line"></div>
                        <a href="#" class="post-tag"><?= $latestPosts[0]->categories ?></a>
                        <h4><a href="#" class="post-headline"><?= $latestPosts[0]->title ?></a></h4>
                        <p><?= $latestPosts[0]->excerpt ?></p>
                        <!-- <p>Curabitur venenatis efficitur lorem sed tempor. Integer aliquet tempor cursus. Nullam vestibulum convallis risus vel condimentum. Nullam auctor lorem in libero luctus, vel volutpat quam tincidunt. Morbi sodales, dolor id ultricies dictum</p> -->
                        <a href="<?= base_url('posts') ?>" class="btn original-btn">Lihat Semua Cerita</a>

                    </div>
                </div>
            </div>
            <!-- Single Blog Area -->
            <?php $index = 0; foreach (array_slice($latestPosts, 0, 2) as $post) : ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="single-catagory-area clearfix mb-100">
                        
                        <img src="<?= $post->media ?>" alt="<?= $post->title ?>">
                        <!-- Catagory Title -->
                        <div class="catagory-title">
                            <a href="<?= $post->url ?>"><?= $index > 0 ? $post->title : 'baca selengkapnya' ?></a>
                        </div>
                    </div>
                </div>
            <?php $index++; endforeach; ?>

        </div>
    </div>

</div>
<!-- ##### Blog Wrapper End ##### -->