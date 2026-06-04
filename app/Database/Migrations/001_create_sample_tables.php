<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Sample migration for Grocery CRUD demo.
 *
 * Creates sample tables: categories, products, tags, product_tags.
 */
class CreateSampleTables extends Migration
{
    public function up(): void
    {
        // ======== Categories (for belongs_to demo) ========
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'description' => ['type' => 'TEXT', 'null' => true],
            'status'      => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('categories', true);

        // Seed categories
        $this->db->table('categories')->insertBatch([
            ['name' => 'Electronics', 'description' => 'Electronic devices and accessories', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Clothing', 'description' => 'Apparel and fashion items', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Books', 'description' => 'Books and publications', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Home & Garden', 'description' => 'Home improvement and garden supplies', 'status' => 'inactive', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Sports', 'description' => 'Sports equipment and gear', 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
        ]);

        // ======== Tags (for NtoN demo) ========
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 50],
            'color'       => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#6c757d'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tags', true);

        // Seed tags
        $this->db->table('tags')->insertBatch([
            ['name' => 'New', 'color' => '#0d6efd', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Sale', 'color' => '#198754', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Featured', 'color' => '#ffc107', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Popular', 'color' => '#dc3545', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Limited', 'color' => '#fd7e14', 'created_at' => date('Y-m-d H:i:s')],
        ]);

        // ======== Products (main table) ========
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'category_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 200],
            'description' => ['type' => 'TEXT', 'null' => true],
            'price'       => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0.00],
            'stock'       => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'is_active'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'image'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('products', true);

        // Seed products
        $products = [
            ['category_id' => 1, 'name' => 'Smartphone XYZ', 'description' => 'Latest smartphone with amazing features', 'price' => 5999000, 'stock' => 50, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 1, 'name' => 'Laptop Pro 16', 'description' => 'High-performance laptop for professionals', 'price' => 15999000, 'stock' => 25, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 1, 'name' => 'Wireless Earbuds', 'description' => 'Noise-canceling wireless earbuds', 'price' => 1299000, 'stock' => 100, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 2, 'name' => 'Cotton T-Shirt', 'description' => 'Premium cotton t-shirt, comfortable fit', 'price' => 149000, 'stock' => 200, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 2, 'name' => 'Denim Jacket', 'description' => 'Classic denim jacket, all-season', 'price' => 499000, 'stock' => 75, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 3, 'name' => 'Programming 101', 'description' => 'Learn programming from scratch', 'price' => 99000, 'stock' => 500, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 3, 'name' => 'Data Science Handbook', 'description' => 'Comprehensive guide to data science', 'price' => 149000, 'stock' => 300, 'is_active' => 0, 'created_at' => date('Y-m-d H:i:s')],
            ['category_id' => 5, 'name' => 'Running Shoes', 'description' => 'Lightweight running shoes', 'price' => 799000, 'stock' => 150, 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('products')->insertBatch($products);

        // ======== Product Tags (junction table for NtoN) ========
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tag_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tag_id', 'tags', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['product_id', 'tag_id']);
        $this->forge->createTable('product_tags', true);

        // Seed product tags
        $this->db->table('product_tags')->insertBatch([
            ['product_id' => 1, 'tag_id' => 1],
            ['product_id' => 1, 'tag_id' => 4],
            ['product_id' => 2, 'tag_id' => 1],
            ['product_id' => 2, 'tag_id' => 3],
            ['product_id' => 3, 'tag_id' => 4],
            ['product_id' => 4, 'tag_id' => 2],
            ['product_id' => 5, 'tag_id' => 3],
            ['product_id' => 6, 'tag_id' => 1],
            ['product_id' => 8, 'tag_id' => 4],
            ['product_id' => 8, 'tag_id' => 2],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropTable('product_tags', true);
        $this->forge->dropTable('products', true);
        $this->forge->dropTable('tags', true);
        $this->forge->dropTable('categories', true);
    }
}
