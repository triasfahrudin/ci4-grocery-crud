<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDependsOnDemoTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'              => ['type' => 'VARCHAR', 'constraint' => 200],
            'price'             => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'has_discount'      => ['type' => 'TINYINT', 'default' => 0],
            'discount_price'    => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true],
            'discount_percent'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'requires_shipping' => ['type' => 'TINYINT', 'default' => 1],
            'shipping_weight'   => ['type' => 'DECIMAL', 'constraint' => '8,2', 'null' => true],
            'shipping_notes'    => ['type' => 'TEXT', 'null' => true],
            'is_active'         => ['type' => 'TINYINT', 'default' => 1],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('depends_on_demo');

        // Seed data
        $seeds = [
            [
                'name'              => 'Laptop ASUS ROG',
                'price'             => 15000000,
                'has_discount'      => 1,
                'discount_price'    => 12999000,
                'discount_percent'  => 15,
                'requires_shipping' => 1,
                'shipping_weight'   => 2.50,
                'shipping_notes'    => 'Handle with care, contains battery',
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => 'Mouse Logitech MX',
                'price'             => 850000,
                'has_discount'      => 0,
                'discount_price'    => null,
                'discount_percent'  => null,
                'requires_shipping' => 1,
                'shipping_weight'   => 0.25,
                'shipping_notes'    => null,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => 'Keyboard Mechanical',
                'price'             => 1200000,
                'has_discount'      => 1,
                'discount_price'    => 999000,
                'discount_percent'  => 20,
                'requires_shipping' => 1,
                'shipping_weight'   => 1.10,
                'shipping_notes'    => null,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => 'Software License - Antivirus',
                'price'             => 350000,
                'has_discount'      => 0,
                'discount_price'    => null,
                'discount_percent'  => null,
                'requires_shipping' => 0,
                'shipping_weight'   => null,
                'shipping_notes'    => null,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => 'USB-C Hub 7-in-1',
                'price'             => 450000,
                'has_discount'      => 1,
                'discount_price'    => 375000,
                'discount_percent'  => null,
                'requires_shipping' => 1,
                'shipping_weight'   => 0.15,
                'shipping_notes'    => null,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => 'Ebook - PHP Modern',
                'price'             => 150000,
                'has_discount'      => 1,
                'discount_price'    => 99000,
                'discount_percent'  => 34,
                'requires_shipping' => 0,
                'shipping_weight'   => null,
                'shipping_notes'    => 'Digital product - no shipping needed',
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => 'Monitor 27 inch 4K',
                'price'             => 4500000,
                'has_discount'      => 0,
                'discount_price'    => null,
                'discount_percent'  => null,
                'requires_shipping' => 1,
                'shipping_weight'   => 5.00,
                'shipping_notes'    => 'Fragile item, double box packaging',
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'name'              => 'Cloud Storage 1TB Annual',
                'price'             => 600000,
                'has_discount'      => 1,
                'discount_price'    => 499000,
                'discount_percent'  => null,
                'requires_shipping' => 0,
                'shipping_weight'   => null,
                'shipping_notes'    => null,
                'is_active'         => 1,
                'created_at'        => date('Y-m-d H:i:s'),
            ],
        ];
        foreach ($seeds as $row) {
            $this->db->table('depends_on_demo')->insert($row);
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('depends_on_demo', true);
    }
}
