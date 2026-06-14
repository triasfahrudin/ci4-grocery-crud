<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use GroceryCrud\GroceryCrud;

/**
 * Clone/Duplicate Demo Controller.
 *
 * Demonstrates the Clone/Duplicate Record feature:
 * - Duplicate a record with one click from the action column
 * - Exclude specific fields from cloning (slug, SKU, timestamps)
 * - Clone preserves all field values except primary key & excluded fields
 *
 * Usage:
 *   1. Run migration: php spark migrate -n App
 *   2. Start server:  php spark serve
 *   3. Open browser:  http://localhost:8080/clone-demo
 */
class CloneDemo extends Controller
{
    /**
     * Render navbar sebagai string.
     */
    private function renderNavbar(string $activePage = ''): string
    {
        return view('layouts/navbar', [
            'brandUrl'   => '/clone-demo',
            'brandIcon'  => 'bi-copy',
            'brandText'  => 'Clone/Duplicate Demo',
            'tabs'       => [
                'index'    => ['url' => '/clone-demo',            'icon' => 'bi-info-circle', 'label' => 'Overview'],
                'products' => ['url' => '/clone-demo/products',   'icon' => 'bi-box-seam',    'label' => 'Products CRUD'],
            ],
            'activePage' => $activePage,
        ]);
    }

    /**
     * Main index page — shows demo info and link to the CRUD.
     */
    public function index(): string
    {
        return view('clone_demo/index');
    }

    /**
     * Products CRUD with Clone/Duplicate enabled.
     *
     * Demonstrates setClone() with excluded fields.
     * The "Duplicate" button appears in each row's action column
     * next to the Edit button with a copy icon.
     */
    public function products(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('products', 'Products (Clone Demo)');

        $crud->setColumns('name', 'category_id', 'price', 'stock', 'is_active', 'created_at');
        $crud->setFields('name', 'category_id', 'description', 'price', 'stock', 'is_active');

        $crud->displayAs('name', 'Product Name');
        $crud->displayAs('category_id', 'Category');
        $crud->displayAs('is_active', 'Active');
        $crud->displayAs('stock', 'Stock Quantity');
        $crud->displayAs('price', 'Price (Rp)');
        $crud->displayAs('created_at', 'Created Date');

        $crud->setFieldType('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);

        $crud->setRelation('category_id', 'categories', 'name', "status = 'active'", 'name ASC');

        $crud->required('name');
        $crud->required('price');
        $crud->unique('name');

        $crud->callbackColumn('price', function ($value, $row) {
            return 'Rp ' . number_format((float) $value, 0, ',', '.');
        });

        $crud->callbackColumn('is_active', function ($value, $row) {
            if ($value == 1) {
                return '<span class="badge bg-success">Active</span>';
            }
            return '<span class="badge bg-secondary">Inactive</span>';
        });

        // 🔥 FITUR CLONE/DUPLICATE — ini yang didemokan
        // Kecualikan 'name' dari cloning karena unique — user harus mengubah nama setelah duplikasi
        // Kecualikan 'created_at', 'updated_at' karena otomatis di-handle oleh callback
        $crud->setClone(true, ['name', 'created_at', 'updated_at']);

        // Callback timestamp untuk insert/update (diperlukan karena created_at/updated_at dikecualikan dari clone)
        $crud->callbackBeforeInsert(function ($data) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            return $data;
        });
        $crud->callbackBeforeUpdate(function ($data) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            return $data;
        });

        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);
        $crud->setColumnFilterRelation('category_id', 'categories', 'name', 'id', "status = 'active'", 'name ASC');

        $crud->setBatchAction('delete_selected', 'Delete Selected');
        $crud->setSoftDelete();
        $crud->setTheme('bootstrap5');

        return $crud->setPageHeader($this->renderNavbar('products'))->render();
    }
}
