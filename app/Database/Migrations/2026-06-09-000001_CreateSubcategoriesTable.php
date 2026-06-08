<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubcategoriesTable extends Migration
{
    public function up(): void
    {
        // ─── Create subcategories table ────────────────────────
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'category_id' => ['type' => 'INT', 'unsigned' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subcategories');

        // ─── Seed subcategories (lookup by category name) ──────
        $categoryMap = [];
        $cats = $this->db->table('categories')->select('id, name')->get()->getResultArray();
        foreach ($cats as $c) {
            $categoryMap[$c['name']] = $c['id'];
        }

        $defs = [
            'Electronics' => ['Smartphones', 'Laptops', 'Accessories'],
            'Clothing'    => ["Men's Fashion", "Women's Fashion", "Kids' Fashion"],
            'Food'        => ['Beverages', 'Snacks', 'Organic'],
            'Books'       => ['Fiction', 'Non-Fiction', 'Educational'],
            'Archived'    => ['Discontinued'],
        ];

        $this->db->simpleQuery('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($defs as $catName => $subs) {
            $catId = $categoryMap[$catName] ?? null;
            if ($catId === null) continue;
            foreach ($subs as $name) {
                // Check if already exists (idempotent)
                $exists = $this->db->table('subcategories')
                    ->where('category_id', $catId)
                    ->where('name', $name)
                    ->countAllResults();
                if ($exists === 0) {
                    $this->db->table('subcategories')->insert([
                        'category_id' => $catId,
                        'name'        => $name,
                    ]);
                }
            }
        }
        $this->db->simpleQuery('SET FOREIGN_KEY_CHECKS = 1');

        // ─── Add category_id & subcategory_id to depends_on_demo ───
        $this->forge->addColumn('depends_on_demo', [
            'category_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'subcategory_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);

        // ─── Seed relation data for existing depends_on_demo records ──
        $electronicsId = $categoryMap['Electronics'] ?? null;
        if ($electronicsId !== null) {
            $this->db->table('depends_on_demo')
                ->whereIn('id', [1, 4, 5])
                ->update(['category_id' => $electronicsId]);

            // Get subcategory IDs by name
            $laptopsId = $this->db->table('subcategories')
                ->where('category_id', $electronicsId)
                ->where('name', 'Laptops')
                ->get()->getRow()->id ?? null;
            $accessoriesId = $this->db->table('subcategories')
                ->where('category_id', $electronicsId)
                ->where('name', 'Accessories')
                ->get()->getRow()->id ?? null;

            if ($laptopsId !== null) {
                $this->db->table('depends_on_demo')
                    ->where('id', 1)
                    ->update(['subcategory_id' => $laptopsId]);
            }
            if ($accessoriesId !== null) {
                $this->db->table('depends_on_demo')
                    ->whereIn('id', [4, 5])
                    ->update(['subcategory_id' => $accessoriesId]);
            }
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('subcategories', true);
        $this->forge->dropColumn('depends_on_demo', 'category_id');
        $this->forge->dropColumn('depends_on_demo', 'subcategory_id');
    }
}
