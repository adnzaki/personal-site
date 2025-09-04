<?php namespace App\Models;

use CodeIgniter\Model;

class PostViewModel extends Model
{
    protected $table = 'wp_post_views';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'post_id', 'views'];
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}