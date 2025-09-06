<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

class SiteVisitorModel extends Model
{
    protected $table         = 'site_visitors';
    protected $primaryKey    = 'id';

    protected $allowedFields = [
        'ip_address',
        'user_agent',
        'visited_url',
        'referrer',
        'created_at',
    ];

    public function insertVisitor()
    {
        $request = Services::request();
        $this->insert([
            'ip_address'     => $request->getIPAddress(),
            'user_agent'     => $request->getUserAgent(),
            'visited_url'    => $request->getUri(),
            'referrer'       => $request->getServer('HTTP_REFERER'),
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
    }
}
