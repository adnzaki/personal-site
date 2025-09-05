<?php

namespace App\Controllers;

class Posts extends BaseController
{
    use PostTrait;

    public function index($taxonomy = '', $filter = '')
    {
        $pager = \Config\Services::pager();
        $page = (int) ($this->request->getGet('page') ?? 1);
        $search = $this->request->getGet('search');
        $perPage = 10;

        $wp = wp()->setPerPage($perPage)->setSinglePostUrl($this->singlePostUrl);

        // this is not correct
        // fix this!
        $taxonomyFilter = [];
        if($taxonomy === 'category') {
            $categories = wp()->getCategories($filter);
            $taxonomyFilter = ['categories' => $categories[0]->id];
        } elseif($taxonomy === 'tag') {
            $tags = wp()->getTags($filter);
            $taxonomyFilter = ['tags' => $tags[0]->id];
        }

        $totalPost = wp()->getTotalPost(array_merge(['search' => $search], $taxonomyFilter));
        $include = ['media', 'category', 'comment'];
        $posts = $wp->getPosts($page, $include, $search, $taxonomy, $filter);


        $pageContent = [
            'status'        => $posts['status'],
            'posts'         => $posts['data'],
            'pageLinks'     => $pager->makeLinks($page, $perPage, $totalPost, 'site_pager'),
            'getPage'       => $page,
            'count'         => $pager->getPageCount(),
            'tags'          => wp()->getTags(),
            'notHome'       => true,
        ];

        $data = [
            'title'         => 'Bit & Bait - Semua Post',
            'content'       => view('layout/post-list', $pageContent),
        ];

        return view('layout/main', $data);
    }

    public function read($slug)
    {
        $post = wp()->readPost($slug);
        if(! empty($post)) {
            $this->updateCounter($post->id);
        }

        $pageContent = [
            'post'          => $post,
            'tags'          => wp()->getTags(),
            'comments'      => wp()->getCommentsWithReplies($post->id),
        ];

        $data = [
            'title'         => $post->title ?? 'Post tidak ditemukan',
            'content'       => view('single-post/content', $pageContent),
        ];

        return view('layout/main', $data);
    }

    public function addComment()
    {
        $postId     = (int) $this->request->getPost('post_id');
        $parentId   = (int) $this->request->getPost('parent_id');
        $authorName = $this->request->getPost('name');
        $authorEmail = $this->request->getPost('email');
        $message    = $this->request->getPost('message');

        $authorData = [
            'author_name'  => $authorName,
            'author_email' => $authorEmail,
        ];

        if(! filter_disallowed_words($message)) {
            return redirect()->back()->with('error', 'Komentar anda mengandung kata-kata yang tidak diizinkan.');
        }

        $result = wp()->addComment($postId, $message, $authorData, $parentId);

        if (isset($result->id)) {
            return redirect()->back()->with('success', 'Komentar anda berhasil ditambahkan dan menunggu persetujuan admin.');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan komentar. Penyebab: ' . json_encode($result));
        }
    }



    public function testRead($slug)
    {
        $getPost    = wp()->readPost($slug);
        $content    = $getPost['contents'];
        $post       = $content['post'];
        $this->updateCounter($post->id);

        $pageContent = [
            'post'          => $post,
            'tags'          => wp()->getTags(),
            'comments'      => wp()->getCommentsWithReplies($post->id),
        ];

        $data = [
            'title'         => $post->title,
            'content'       => $pageContent,
        ];

        return $this->response->setJSON($data);
    }
}
