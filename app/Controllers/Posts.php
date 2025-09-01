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

        $wp = wp()->setPerPage($perPage)->setSinglePostUrl('read');

        $totalPost = wp()->call('posts', true)['total'];
        $posts = $wp->getPosts($page, $search, $taxonomy, $filter);


        $pageContent = [
            'status'        => $posts['status'],
            'posts'         => $posts['data'],
            'pageLinks'     => $pager->makeLinks($page, $perPage, $totalPost, 'site_pager'),
            'getPage'       => $page,
            'count'         => $pager->getPageCount(),
            'tags'          => wp()->getTags(),
            'popularPosts'  => $this->popularPosts(),
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
        $getPost    = wp()->readPost($slug);
        $content    = $getPost['contents'];
        $id         = $getPost['id'];
        $this->updateCounter($id);
    }
}