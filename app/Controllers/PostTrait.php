<?php namespace App\Controllers;

use App\Models\PostViewModel;

trait PostTrait
{
    private $model;

    private $singlePostUrl = 'read';

    public function __construct()
    {
        $this->model = new PostViewModel();
    }
    public function updateCounter($postId)
    {
        if($this->model->where('post_id', $postId)->first() === null) {
            $this->model->insert(['post_id' => $postId, 'views' => 1]);
        } else {
            $detail = $this->model->where('post_id', $postId)->first();
            $this->model->where('post_id', $postId)->set('views', $detail['views'] + 1)->update();
        }
    }

    public function popularPosts()
    {
        $ids = [];
        $topPosts = $this->model->orderBy('views', 'desc');

        if ($topPosts->countAllResults(false) > 0) {
            $ids = $topPosts->findAll(5);
            $ids = array_column($ids, 'post_id');
            $popularPosts = wp()->setPerPage(5)
                            ->setOrder('include')
                            ->setIds($ids)
                            ->getPosts(1, '', '', '')['data'];
        }

        return $popularPosts ?? [];
    }
}