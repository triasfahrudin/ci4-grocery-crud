<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSampleTables extends Migration
{
    public function up(): void
    {
        // ======== Categories ========
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'description' => ['type' => 'TEXT', 'null' => true],
            'status'      => ['type' => "ENUM('active','inactive')", 'default' => 'active'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('categories');

        // Seed categories
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and accessories', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Clothing',    'description' => 'Apparel and fashion items',         'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Food',        'description' => 'Food and beverage products',         'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Books',       'description' => 'Books and publications',             'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Archived',    'description' => 'Discontinued items',                 'status' => 'inactive', 'created_at' => date('Y-m-d H:i:s')],
        ];
        foreach ($categories as $cat) {
            $this->db->table('categories')->insert($cat);
        }

        // ======== Products ========
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'category_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 200],
            'description' => ['type' => 'TEXT', 'null' => true],
            'price'       => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'stock'       => ['type' => 'INT', 'default' => 0],
            'is_active'   => ['type' => 'TINYINT', 'default' => 1],
            'image'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('products');

        // Seed products
        $products = [
            ['category_id' => 1, 'name' => 'Smartphone X',    'description' => 'Latest smartphone with AI features',   'price' => 5999000, 'stock' => 50, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 1, 'name' => 'Laptop Pro',      'description' => 'High-performance laptop for work',      'price' => 15999000, 'stock' => 20, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 1, 'name' => 'Wireless Earbuds','description' => 'Bluetooth 5.3 earbuds with noise cancel','price' => 899000, 'stock' => 100, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 2, 'name' => 'Cotton T-Shirt',  'description' => 'Comfortable 100% cotton t-shirt',        'price' => 149000, 'stock' => 200, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 2, 'name' => 'Denim Jacket',    'description' => 'Classic denim jacket',                   'price' => 499000, 'stock' => 30, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 3, 'name' => 'Organic Coffee',  'description' => 'Premium organic Arabica coffee beans',   'price' => 85000, 'stock' => 500, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 3, 'name' => 'Green Tea',       'description' => 'Japanese matcha green tea',              'price' => 120000, 'stock' => 150, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 4, 'name' => 'PHP Handbook',    'description' => 'Complete guide to PHP programming',      'price' => 250000, 'stock' => 75, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 4, 'name' => 'Data Science 101','description' => 'Introduction to data science',           'price' => 320000, 'stock' => 40, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 1, 'name' => 'Tablet Mini',     'description' => 'Compact tablet for entertainment',       'price' => 3499000, 'stock' => 0, 'is_active' => 0, 'created_at' => date('Y-m-d H:i:s')],
        ];
        foreach ($products as $product) {
            $this->db->table('products')->insert($product);
        }

        // ======== Tags ========
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'color'      => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#6c757d'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tags');

        // Seed tags
        $tags = [
            ['name' => 'New Arrival',  'color' => '#0d6efd', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Best Seller',  'color' => '#198754', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Sale',         'color' => '#dc3545', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Premium',      'color' => '#ffc107', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Eco Friendly', 'color' => '#20c997', 'created_at' => date('Y-m-d H:i:s')],
        ];
        foreach ($tags as $tag) {
            $this->db->table('tags')->insert($tag);
        }

        // ======== Product Tags (junction) ========
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'product_id' => ['type' => 'INT', 'unsigned' => true],
            'tag_id'     => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tag_id', 'tags', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_tags');

        // Seed product_tags
        $productTags = [
            ['product_id' => 1, 'tag_id' => 1], // Smartphone X -> New Arrival
            ['product_id' => 1, 'tag_id' => 4], // Smartphone X -> Premium
            ['product_id' => 2, 'tag_id' => 4], // Laptop Pro -> Premium
            ['product_id' => 2, 'tag_id' => 2], // Laptop Pro -> Best Seller
            ['product_id' => 3, 'tag_id' => 2], // Wireless Earbuds -> Best Seller
            ['product_id' => 6, 'tag_id' => 5], // Organic Coffee -> Eco Friendly
            ['product_id' => 6, 'tag_id' => 2], // Organic Coffee -> Best Seller
            ['product_id' => 8, 'tag_id' => 2], // PHP Handbook -> Best Seller
        ];
        foreach ($productTags as $pt) {
            $this->db->table('product_tags')->insert($pt);
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('product_tags', true);
        $this->forge->dropTable('products', true);
        $this->forge->dropTable('tags', true);
        $this->forge->dropTable('categories', true);
    }
}
