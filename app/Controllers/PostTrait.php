<?php namespace App\Controllers;

use App\Models\PostViewModel;

trait PostTrait
{
    private $model;

    private $singlePostUrl = 'read';

    private $openGraphMeta = [];

    public function __construct()
    {
        $this->model = new PostViewModel();
        $this->openGraphMeta = [
            'title'         => 'Bit & Bait',
            'description'   => 'Merangkai logika dan rasa dalam tiap baris kode',
            'image'         => base_url('img/core-img/Bit-Bait-LogoFull.png'),
            'url'           => base_url(),
        ];
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

    public function getPopularPosts()
    {
        $ids = [];
        $popularPosts = [];
        $topPosts = $this->model->orderBy('views', 'desc');

        if ($topPosts->countAllResults(false) > 0) {
            $ids = $topPosts->findAll(5);
            $ids = array_column($ids, 'post_id');
            $popularPosts = wp()->setPerPage(5)
                ->setSinglePostUrl('read')
                ->setOrder('include')
                ->setIds($ids)
                ->getPosts(1, ['media', 'category'])['data'];
        }

        return $popularPosts;
    }
}