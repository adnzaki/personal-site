<?php namespace App\Models;

use CodeIgniter\Model;

class WpOptionsModel extends Model
{
    protected $table = 'wp_options';
    protected $primaryKey = 'option_id';
    protected $allowedFields = ['option_name', 'option_value'];
    protected $returnType = 'object';

    public function getDisallowedKeys()
    {
        return $this->where('option_name', 'disallowed_keys')->first();
    }
}