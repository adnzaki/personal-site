<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $allPosts = wp()->setPerPage(12)->getPosts(1);
        $latestPosts = array_slice($allPosts['data'], 0, 2);
        $recentPosts = array_slice($allPosts['data'], 2, 5);
        $olderPosts = array_slice($allPosts['data'], 7, 5);
        $wrapperContent = [
            'status'        => $allPosts['status'],
            'latestPosts'   => $latestPosts,
            'recentPosts'   => $recentPosts,
            'olderPosts'    => $olderPosts,
            'tags'          => wp()->getTags(),
        ];

        $content = [
            'hero'          => view('home/hero'),
            'highlights'    => view('home/highlights', $wrapperContent),
            'recent'        => view('home/recent', $wrapperContent),
        ];

        $data = [
            'title'     => 'Bit & Bait - Merangkai logika dan rasa dalam tiap baris kode',
            'content'   => implode('', $content),
        ];

        return view('layout/main', $data);
    }
}
