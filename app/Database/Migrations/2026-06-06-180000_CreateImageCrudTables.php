<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateImageCrudTables extends Migration
{
    public function up(): void
    {
        // Example 1: Simple gallery (id, url)
        $this->forge->addField([
            'id'  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'url' => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('example_1');

        // Example 2: With ordering (id, url, priority)
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'url'      => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'priority' => ['type' => 'INT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('example_2');

        // Example 3: With relation + ordering (id, url, category_id, priority)
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'url'         => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'category_id' => ['type' => 'INT', 'null' => true],
            'priority'    => ['type' => 'INT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('example_3');

        // Example 4: With title + ordering (id, title, url, priority)
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title'    => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'url'      => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'priority' => ['type' => 'INT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('example_4');
    }

    public function down(): void
    {
        $this->forge->dropTable('example_1', true);
        $this->forge->dropTable('example_2', true);
        $this->forge->dropTable('example_3', true);
        $this->forge->dropTable('example_4', true);
    }
}
