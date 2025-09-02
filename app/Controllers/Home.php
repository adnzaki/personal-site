<?php

namespace App\Controllers;

class Home extends BaseController
{
    use PostTrait;

    public function index(): string
    {
        $allPosts = wp()->setSinglePostUrl($this->singlePostUrl)->setPerPage(12)->getPosts(1);
        $latestPosts = array_slice($allPosts['data'], 0, 2);
        $recentPosts = array_slice($allPosts['data'], 2, 5);
        $wrapperContent = [
            'status'        => $allPosts['status'],
            'latestPosts'   => $latestPosts,
            'posts'         => $recentPosts,
            'tags'          => wp()->getTags(),
        ];

        $content = [
            'highlights'    => view('home/highlights', $wrapperContent),
            'recent'        => view('layout/post-list', $wrapperContent),
        ];

        $data = [
            'title'     => 'Bit & Bait - Merangkai logika dan rasa dalam tiap baris kode',
            'content'   => implode('', $content),
        ];

        return view('layout/main', $data);
    }
}
