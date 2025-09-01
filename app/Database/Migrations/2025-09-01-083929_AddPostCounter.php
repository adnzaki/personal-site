<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPostCounter extends Migration
{
    public function up()
    {
        $field = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'post_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'views' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ]
        ];
        
        $this->forge->addField($field);
        $this->forge->addKey('id', true);
        $this->forge->addKey('post_id');
        $this->forge->createTable('wp_post_views');
    }

    public function down()
    {
        //
    }
}
