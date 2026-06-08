<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityLogsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'table_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'record_pk'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'action'     => ['type' => "ENUM('insert','update','delete','restore','import')"],
            'user_id'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'user_name'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'old_data'   => ['type' => 'JSON', 'null' => true],
            'new_data'   => ['type' => 'JSON', 'null' => true],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'user_agent' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('table_name');
        $this->forge->addKey('action');
        $this->forge->addKey('user_id');
        $this->forge->addKey('created_at');
        $this->forge->addKey(['table_name', 'record_pk']);
        $this->forge->addKey(['action', 'created_at']);
        $this->forge->createTable('activity_logs');
    }

    public function down(): void
    {
        $this->forge->dropTable('activity_logs', true);
    }
}
