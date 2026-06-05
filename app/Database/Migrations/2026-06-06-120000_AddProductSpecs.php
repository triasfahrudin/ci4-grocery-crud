<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProductSpecs extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('products', [
            'specs' => ['type' => 'TEXT', 'null' => true, 'after' => 'image'],
        ]);

        // Seed sample specs for existing products
        $specsData = [
            1 => '[{"key":"Processor","value":"Snapdragon 8 Gen 3"},{"key":"RAM","value":"12GB"},{"key":"Storage","value":"256GB"}]',
            2 => '[{"key":"Processor","value":"Intel Core i9-13900H"},{"key":"RAM","value":"32GB"},{"key":"Storage","value":"1TB SSD"}]',
            3 => '[{"key":"Battery Life","value":"8 hours"},{"key":"Bluetooth","value":"5.3"},{"key":"Noise Cancel","value":"Yes"}]',
            6 => '[{"key":"Origin","value":"Indonesia"},{"key":"Roast Level","value":"Medium"},{"key":"Weight","value":"250g"}]',
            8 => '[{"key":"Pages","value":"450"},{"key":"Language","value":"English"},{"key":"Level","value":"Intermediate"}]',
        ];

        foreach ($specsData as $id => $specs) {
            $this->db->table('products')
                ->where('id', $id)
                ->update(['specs' => $specs]);
        }
    }

    public function down(): void
    {
        $this->forge->dropColumn('products', 'specs');
    }
}
