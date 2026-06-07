<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePermissionsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'role'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'table_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'can_view'   => ['type' => 'TINYINT', 'default' => 0],
            'can_add'    => ['type' => 'TINYINT', 'default' => 0],
            'can_edit'   => ['type' => 'TINYINT', 'default' => 0],
            'can_delete' => ['type' => 'TINYINT', 'default' => 0],
            'can_export' => ['type' => 'TINYINT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['role', 'table_name']);
        $this->forge->createTable('permissions');

        // Seed default permissions
        $tables = ['products', 'categories', 'tags', 'product_variants'];
        $rolePermissions = [
            'admin'  => ['view' => 1, 'add' => 1, 'edit' => 1, 'delete' => 1, 'export' => 1],
            'editor' => ['view' => 1, 'add' => 1, 'edit' => 1, 'delete' => 0, 'export' => 1],
            'viewer' => ['view' => 1, 'add' => 0, 'edit' => 0, 'delete' => 0, 'export' => 1],
        ];

        $now = date('Y-m-d H:i:s');
        foreach ($rolePermissions as $role => $perms) {
            foreach ($tables as $table) {
                $this->db->table('permissions')->insert([
                    'role'       => $role,
                    'table_name' => $table,
                    'can_view'   => $perms['view'],
                    'can_add'    => $perms['add'],
                    'can_edit'   => $perms['edit'],
                    'can_delete' => $perms['delete'],
                    'can_export' => $perms['export'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('permissions');
    }
}
