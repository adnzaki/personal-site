<!-- ##### Sidebar Area ##### -->
<div class="col-12 col-md-4 col-lg-3">
    <div class="post-sidebar-area">

        <!-- Widget Area -->
        <div class="sidebar-widget-area">
            <form action="#" class="search-form">
                <input type="search" name="search" id="searchForm" style="padding-left: 10px;" placeholder="Cari cerita...">
                <input type="submit" value="submit">
            </form>
        </div>


        <!-- Widget Area -->
        <div class="sidebar-widget-area">

            <div class="widget-content">
                <h5 class="title">Cerita Terhangat</h5>


                <!-- Popular Posts -->
                <?php
                $ids = [];
                $popularPosts = [];
                $model = new \App\Models\PostViewModel();
                $topPosts = $model->orderBy('views', 'desc');

                if ($topPosts->countAllResults(false) > 0) {
                    $ids = $topPosts->findAll(5);
                    $ids = array_column($ids, 'post_id');
                    $popularPosts = wp()->setPerPage(5)
                        ->setOrder('include')
                        ->setIds($ids)
                        ->getPosts(1, '', '', '')['data'];
                }
                ?>
                <?php foreach ($popularPosts as $post) : ?>
                    <div class="single-blog-post d-flex align-items-center widget-post">
                        <!-- Post Thumbnail -->
                        <div class="post-thumbnail">
                            <img src="<?= $post->media ?>" alt="gambar untuk <?= $post->title ?>" class="thumbnail-square">
                        </div>
                        <!-- Post Content -->
                        <div class="post-content">
                            <a href="<?= $post->url ?>" class="post-tag"><?= $post->categories ?></a>
                            <h4><a href="<?= $post->url ?>" class="post-headline"><?= $post->title ?></a></h4>
                            <div class="post-meta">
                                <p><a href="<?= $post->url ?>"><?= osdate()->create($post->date, 'd-M-y'); ?></a></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <!-- Widget Area -->
        <div class="sidebar-widget-area">
            <h5 class="title">Tags</h5>
            <div class="widget-content">
                <ul class="tags">
                    <?php
                    if (count($tags) > 0) {
                        foreach ($tags as $tag) {
                            echo '<li><a href="' . base_url('posts/tag/' . $tag->name) . '">' . $tag->name . '</a></li>';
                        }
                    } else {
                        echo '<p>Belum ada tag</p>';
                    }
                    ?>

                </ul>
            </div>
        </div>
    </div>
</div>