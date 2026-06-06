<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSoftDeleteToProducts extends Migration
{
    public function up(): void
    {
        // Add soft delete column to products table
        $fields = [
            'deleted_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
                'after'   => 'updated_at',
            ],
        ];
        $this->forge->addColumn('products', $fields);

        // Add index for soft delete queries
        $this->forge->addKey('deleted_at');
    }

    public function down(): void
    {
        $this->forge->dropColumn('products', 'deleted_at');
    }
}
