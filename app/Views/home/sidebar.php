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
            <h5 class="title">Cerita Lainnya</h5>

            <div class="widget-content">

                <!-- Single Blog Post -->
                <?php foreach ($olderPosts as $post) : ?>
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
                            echo '<li><a href="' . base_url('posts?tag=' . $tag->name) . '">' . $tag->name . '</a></li>';
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