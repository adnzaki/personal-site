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
        $agent = $request->getUserAgent();
        $platform = $agent->getPlatform();
        $browser = $agent->getBrowser() . ' ' . $agent->getVersion();

        // Ambil IP asli dari header Cloudflare
        $ip = $request->getServer('HTTP_CF_CONNECTING_IP');
        if (!$ip) {
            $ip = $request->getIPAddress(); // fallback ke REMOTE_ADDR
        }

        $this->insert([
            'ip_address'     => $ip,
            'user_agent'     => $browser . ' (' . $platform . ')',
            'visited_url'    => $request->getUri(),
            'referrer'       => $request->getServer('HTTP_REFERER'),
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
    }
}
