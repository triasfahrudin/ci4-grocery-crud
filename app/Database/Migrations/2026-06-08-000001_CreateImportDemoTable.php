<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateImportDemoTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'phone'      => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'company'    => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'is_active'  => ['type' => 'TINYINT', 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('contacts');

        // Seed data
        $seeds = [
            [
                'name'       => 'John Doe',
                'email'      => 'john@example.com',
                'phone'      => '08123456789',
                'company'    => 'PT Maju Jaya',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Jane Smith',
                'email'      => 'jane@example.com',
                'phone'      => '08234567890',
                'company'    => 'CV Sukses Abadi',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Bob Johnson',
                'email'      => 'bob@example.com',
                'phone'      => null,
                'company'    => 'PT Teknologi Maju',
                'is_active'  => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Alice Brown',
                'email'      => 'alice@example.com',
                'phone'      => '08345678901',
                'company'    => null,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        foreach ($seeds as $row) {
            $this->db->table('contacts')->insert($row);
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('contacts', true);
    }
}
