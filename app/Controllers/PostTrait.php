<?php namespace App\Controllers;

use App\Models\PostViewModel;

trait PostTrait
{
    private $model;

    public function __construct()
    {
        $this->model = new PostViewModel();
    }
    public function updateCounter($postId)
    {
        if($this->model->where('post_id', $postId)->first() === null) {
            $this->model->insert(['post_id' => $postId, 'count' => 1]);
        } else {
            $this->model->where('post_id', $postId)->set('count', 'count + 1')->update();
        }
    }

    public function popularPosts()
    {
        $ids = [];
        $topPosts = $this->model->orderBy('count', 'desc');

        if ($topPosts->countAllResults() > 0) {
            $ids = $topPosts->findAll(5);
            $ids = array_column($ids, 'post_id');
            $popularPosts = wp()->setPerPage(5)->getPosts(1, '', '', '', $ids)['data'];
        }

        return $popularPosts ?? [];
    }
}