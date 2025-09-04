<?php

namespace App\Controllers;

class Home extends BaseController
{
    use PostTrait;

    public function index(): string
    {
        $latestPosts = wp()->setSinglePostUrl($this->singlePostUrl)
                        ->setPerPage(2)
                        ->getPosts(1, ['media', 'category']);
                        
        $recentPosts = wp()->setSinglePostUrl($this->singlePostUrl)
                        ->startFrom(2)
                        ->setPerPage(5)
                        ->getPosts(1, ['media', 'category', 'comment']);
        $wrapperContent = [
            'latestStatus'  => $latestPosts['status'],
            'recentStatus'  => $recentPosts['status'],
            'latestPosts'   => $latestPosts['data'],
            'posts'         => $recentPosts['data'],
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
