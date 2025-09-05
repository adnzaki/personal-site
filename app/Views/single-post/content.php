<!-- ##### Single Blog Area Start ##### -->
<?php if(! empty($post)): ?>
<div class="single-blog-wrapper section-padding-0-100">

    <!-- Single Blog Area  -->
    <div class="single-blog-area blog-style-2 mb-50">
        <div class="single-blog-thumbnail">
            <img src="<?= $post->singlePostImage ?>" alt="">
            <div class="post-tag-content">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="post-date">
                                <a href="#">
                                    <?= substr($post->date, 8, 2) ?> <span><?= preg_replace('/\d+/', '', osdate()->create($post->date)); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- ##### Post Content Area ##### -->
            <div class="col-12 col-lg-9">
                <!-- Single Blog Area  -->
                <div class="single-blog-area blog-style-2 mb-50">
                    <!-- Blog Content -->
                    <div class="single-blog-content">
                        <div class="line"></div>
                        <a href="#" class="post-tag"><?= $post->categories ?></a>
                        <h4><a href="#" class="post-headline mb-0"><?= $post->title ?></a></h4>
                        <div class="post-meta mb-50">
                            <p>By <a href="#"><?= $post->author ?></a></p>
                            <p><?= $post->commentsCount > 0 ? $post->commentsCount . ' komentar' : 'Tidak ada komentar.' ?></p>
                        </div>
                        <?= $post->content ?>
                        <?php if ($post->tags) : ?>
                            <p>
                                <i>
                                    Tag:
                                    <b>
                                        <?php foreach ($post->tags as $tag) : ?>
                                            <a href="<?= base_url('posts/tag/' . $tag->name) ?>">#<?= $tag->name ?></a>
                                        <?php endforeach; ?>
                                    </b>
                                </i>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- About Author -->
                <div class="blog-post-author mt-100 d-flex">
                    <div class="author-thumbnail">
                        <img src="<?= $post->authorImage ?>" alt="">
                    </div>
                    <div class="author-info">
                        <div class="line"></div>
                        <span class="author-role">Penulis</span>
                        <h4><a href="#" class="author-name"><?= $post->author ?></a></h4>
                        <p><?= $post->authorBio ?></p>
                    </div>
                </div>

                <?php
                /**
                 * Render comments recursively
                 *
                 * @param array|object $comments
                 */
                function renderComments($comments)
                {
                    if (empty($comments)) return;

                    foreach ($comments as $comment) : ?>
                        <!-- Single Comment Area -->
                        <li class="single_comment_area" data-id="<?= $comment->id ?>">
                            <!-- Comment Content -->
                            <div class="comment-content d-flex">
                                <!-- Comment Author -->
                                <div class="comment-author">
                                    <img src="<?= esc($comment->author_avatar_urls->{'48'} ?? 'https://www.gravatar.com/avatar/?d=mm&s=48'); ?>"
                                        alt="<?= esc($comment->author_name); ?>">
                                </div>
                                <!-- Comment Meta -->
                                <div class="comment-meta">
                                    <a href="#" class="post-date"><?= osdate()->create($comment->date, 'd-M-y'); ?></a>
                                    <p><a href="#" class="post-author"><?= esc($comment->author_name); ?></a></p>
                                    <p><?= esc(strip_tags($comment->content->rendered)); ?></p>
                                    <a href="#reply-to-<?= $comment->id ?>" class="comment-reply">Reply</a>
                                </div>
                            </div>

                            <?php if (!empty($comment->replies)) : ?>
                                <ol class="children">
                                    <?php renderComments($comment->replies); ?>
                                </ol>
                            <?php endif; ?>
                        </li>
                <?php endforeach;
                }
                ?>

                <!-- Comment Area -->
                <div class="comment_area clearfix mt-70">
                    <h5 class="title">Komentar</h5>

                    <ol>
                        <?php renderComments($comments); ?>
                    </ol>
                </div>


                <!-- Default Comment Form -->
                <div id="comment-form-wrapper" class="post-a-comment-area mt-70">
                    <h5 id="comment-form-title">Tinggalkan balasan</h5>
                    <form action="<?= base_url('add-comment') ?>" method="post" id="comment-form">
                        <input type="hidden" name="parent_id" id="parent_id" value="0">
                        <input type="hidden" name="post_id" id="post_id" value="<?= $post->id ?>">

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="group">
                                    <input type="text" name="name" id="name" required>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Nama</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="group">
                                    <input type="email" name="email" id="email" required>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group">
                                    <textarea name="message" id="message" required></textarea>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label>Komentar</label>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-end">
                                <button type="submit" class="btn original-btn">Kirim</button>
                                <button type="button" id="cancel-reply" class="btn btn-sm btn-light d-none text-danger ms-2 px-3">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            <?= view('layout/sidebar') ?>


        </div>
    </div>
</div>
<!-- ##### Single Blog Area End ##### -->
<?php else: ?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2>Post tidak ditemukan</h2>
        </div>
    </div>
</div>

<?php endif; ?>
