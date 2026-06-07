<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'username'   => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'password'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'full_name'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'role'       => ['type' => "ENUM('admin','editor','viewer')", 'default' => 'viewer'],
            'is_active'  => ['type' => 'TINYINT', 'default' => 1],
            'last_login' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');

        // Seed default users
        $users = [
            [
                'username'   => 'admin',
                'email'      => 'admin@example.com',
                'password'   => password_hash('admin123', PASSWORD_BCRYPT),
                'full_name'  => 'Administrator',
                'role'       => 'admin',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'editor',
                'email'      => 'editor@example.com',
                'password'   => password_hash('editor123', PASSWORD_BCRYPT),
                'full_name'  => 'Content Editor',
                'role'       => 'editor',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'viewer',
                'email'      => 'viewer@example.com',
                'password'   => password_hash('viewer123', PASSWORD_BCRYPT),
                'full_name'  => 'Read Only User',
                'role'       => 'viewer',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        foreach ($users as $user) {
            $this->db->table('users')->insert($user);
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('users');
    }
}
