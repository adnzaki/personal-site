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
}