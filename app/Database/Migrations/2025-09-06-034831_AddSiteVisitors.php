<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSiteVisitors extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'ip_address'     => ['type' => 'VARCHAR', 'constraint' => 45], // IPv6 support
            'user_agent'     => ['type' => 'TEXT'],
            'visited_url'    => ['type' => 'TEXT'],
            'referrer'       => ['type' => 'TEXT', 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('site_visitors');
    }

    public function down()
    {
        $this->forge->dropTable('site_visitors');
    }
}
