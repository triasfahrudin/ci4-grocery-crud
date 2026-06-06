<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductVariantsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'product_id' => ['type' => 'INT', 'unsigned' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'price'      => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true],
            'stock'      => ['type' => 'INT', 'null' => true],
            'sku'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_variants');

        // Seed variants
        $variants = [
            // Smartphone X (product_id=1)
            ['product_id' => 1, 'name' => '128GB Black',  'price' => 5999000,  'stock' => 30, 'sku' => 'SM-X-128-BLK', 'created_at' => date('Y-m-d H:i:s')],
            ['product_id' => 1, 'name' => '256GB White',  'price' => 6499000,  'stock' => 20, 'sku' => 'SM-X-256-WHT', 'created_at' => date('Y-m-d H:i:s')],
            ['product_id' => 1, 'name' => '512GB Silver', 'price' => 7499000,  'stock' => 10, 'sku' => 'SM-X-512-SLV', 'created_at' => date('Y-m-d H:i:s')],
            // Laptop Pro (product_id=2)
            ['product_id' => 2, 'name' => '16GB/512GB',   'price' => 15999000, 'stock' => 15, 'sku' => 'LP-16-512',    'created_at' => date('Y-m-d H:i:s')],
            ['product_id' => 2, 'name' => '32GB/1TB',     'price' => 19999000, 'stock' => 8,  'sku' => 'LP-32-1TB',   'created_at' => date('Y-m-d H:i:s')],
            // Cotton T-Shirt (product_id=4)
            ['product_id' => 4, 'name' => 'Size S',       'price' => 139000,   'stock' => 80, 'sku' => 'CTS-S',       'created_at' => date('Y-m-d H:i:s')],
            ['product_id' => 4, 'name' => 'Size M',       'price' => 149000,   'stock' => 60, 'sku' => 'CTS-M',       'created_at' => date('Y-m-d H:i:s')],
            ['product_id' => 4, 'name' => 'Size L',       'price' => 159000,   'stock' => 40, 'sku' => 'CTS-L',       'created_at' => date('Y-m-d H:i:s')],
            // Organic Coffee (product_id=6)
            ['product_id' => 6, 'name' => '250g Pack',    'price' => 85000,    'stock' => 200,'sku' => 'OC-250',      'created_at' => date('Y-m-d H:i:s')],
            ['product_id' => 6, 'name' => '500g Pack',    'price' => 155000,   'stock' => 100,'sku' => 'OC-500',      'created_at' => date('Y-m-d H:i:s')],
            ['product_id' => 6, 'name' => '1kg Pack',     'price' => 280000,   'stock' => 50, 'sku' => 'OC-1KG',      'created_at' => date('Y-m-d H:i:s')],
        ];

        foreach ($variants as $variant) {
            $this->db->table('product_variants')->insert($variant);
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('product_variants', true);
    }
}
