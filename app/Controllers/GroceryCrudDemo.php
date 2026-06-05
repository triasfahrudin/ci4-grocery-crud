<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use GroceryCrud\GroceryCrud;

/**
 * Grocery CRUD Demo Controller.
 *
 * Demonstrates various features of the Grocery CRUD library:
 * - Basic CRUD
 * - Relations (belongs_to, n_to_n)
 * - Callbacks
 * - Validation
 * - File upload
 * - Custom actions
 * - Export
 * - Column Filters (text, dropdown, relation)
 * - Batch Actions (delete selected)
 * - Sort by column headers
 * - Image viewer (click thumbnail)
 * - Repeater Fields (Nova-style repeatable groups)
 * - AdminLTE 4 Theme
 * - Indonesian language
 *
 * Usage:
 *   1. Run migration: php spark migrate -n App
 *   2. Start server:   php spark serve
 *   3. Open browser:   http://localhost:8080/grocery-crud-demo
 */
class GroceryCrudDemo extends Controller
{
    /**
     * Main index - shows a menu of demo options.
     */
    public function index(): string
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Grocery CRUD Demo</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container py-5">
                <div class="row mb-4">
                    <div class="col">
                        <h1 class="display-5 fw-bold">Grocery CRUD Demo</h1>
                        <p class="text-muted">CodeIgniter 4 - Full-featured CRUD Library</p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-box-seam me-2 text-primary"></i>Products
                                </h5>
                                <p class="card-text text-muted small">Basic CRUD with relations, validation, callbacks, upload, and NtoN tags.</p>
                                <a href="/grocery-crud-demo/products" class="btn btn-primary">Open</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-tags me-2 text-success"></i>Categories
                                </h5>
                                <p class="card-text text-muted small">Simple CRUD with enum fields and search.</p>
                                <a href="/grocery-crud-demo/categories" class="btn btn-success">Open</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-bookmark me-2 text-warning"></i>Tags
                                </h5>
                                <p class="card-text text-muted small">Minimal CRUD with color picker field.</p>
                                <a href="/grocery-crud-demo/tags" class="btn btn-warning text-white">Open</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5>Features Demonstrated</h5>
                                <table class="table table-sm table-bordered mt-3">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Feature</th>
                                            <th>Products</th>
                                            <th>Categories</th>
                                            <th>Tags</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Basic CRUD</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>Belongs_to Relation</td><td>✓</td><td>-</td><td>-</td></tr>
                                        <tr><td>N-to-N Relation</td><td>✓</td><td>-</td><td>-</td></tr>
                                        <tr><td>Callbacks</td><td>✓</td><td>-</td><td>-</td></tr>
                                        <tr><td>Validation</td><td>✓</td><td>✓</td><td>-</td></tr>
                                        <tr><td>File Upload</td><td>✓</td><td>-</td><td>-</td></tr>
                                        <tr><td>Custom Actions</td><td>-</td><td>✓</td><td>-</td></tr>
                                        <tr><td>Export</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>Search</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>Field Type Override</td><td>✓</td><td>-</td><td>✓</td></tr>
                                        <tr><td>Column Filters</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>Batch Actions</td><td>✓</td><td>✓</td><td>-</td></tr>
                                        <tr><td>Sort by Headers</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>Image Viewer</td><td>✓</td><td>-</td><td>-</td></tr>
                                        <tr><td>Repeater Fields</td><td>✓</td><td>-</td><td>-</td></tr>
                                        <tr><td>AdminLTE 4 Theme</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        </body>
        </html>
HTML;
    }

    /**
     * Products CRUD - Full featured demo.
     *
     * Demonstrates:
     * - setTable, setColumns, setFields, displayAs
     * - setRelation (belongs_to to categories)
     * - setRelationNtoN (many-to-many with tags)
     * - setUpload (image upload)
     * - setRules, required, unique (validation)
     * - callbackBeforeInsert, callbackAfterUpdate
     * - callbackColumn (custom column rendering)
     * - orderBy, setPerPage
     * - setLanguage (Indonesian)
     * - Export
     */
    public function products(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('products', 'Products');

        // ======== Filter by category (from URL query param) ========
        $categoryId = $this->request->getGet('category_id');
        if ($categoryId !== null && $categoryId !== '') {
            $crud->where('category_id', $categoryId);

            // Set subject with filter indicator
            $crud->setSubject('Products (filtered by category #' . $categoryId . ')');
        }

        // ======== Columns to display in table ========
        $crud->setColumns('name', 'category_id', 'price', 'stock', 'is_active', 'image', 'created_at');

        // ======== Fields in add/edit forms ========
        $crud->setFields('name', 'category_id', 'description', 'price', 'stock', 'is_active', 'image', 'specs', 'tags');

        // ======== Display labels ========
        $crud->displayAs('name', 'Product Name');
        $crud->displayAs('category_id', 'Category');
        $crud->displayAs('is_active', 'Active');
        $crud->displayAs('stock', 'Stock Quantity');
        $crud->displayAs('price', 'Price (Rp)');
        $crud->displayAs('created_at', 'Created Date');

        // ======== Active field as combobox dropdown ========
        $crud->setFieldType('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);

        // ======== Relation to categories (belongs_to) ========
        $crud->setRelation('category_id', 'categories', 'name', "status = 'active'", 'name ASC');

        // ======== N-to-N relation with tags ========
        $crud->setRelationNtoN(
            'tags',           // field name in form
            'product_tags',   // junction table
            'product_id',     // FK in junction pointing to products
            'tag_id',         // FK in junction pointing to tags
            'tags',           // target table
            'name'            // title field in target
        );

        // ======== File upload ========
        $crud->setUpload('image', [
            'allowedTypes' => 'jpg|jpeg|png|gif|webp',
            'maxSize'      => 1024, // KB
            'encryptFileName' => true,
        ]);

        // ======== Validation ========
        $crud->required('name');
        $crud->required('price');
        $crud->required('stock');
        $crud->unique('name');
        $crud->setRules('price', 'numeric|greater_than[0]', 'Price');
        $crud->setRules('stock', 'integer|greater_than_equal_to[0]', 'Stock');

        // ======== Column callbacks ========
        // Format price as currency
        $crud->callbackColumn('price', function ($value, $row) {
            return 'Rp ' . number_format((float) $value, 0, ',', '.');
        });

        // Format active status as badge
        $crud->callbackColumn('is_active', function ($value, $row) {
            if ($value == 1) {
                return '<span class="badge bg-success">Active</span>';
            }
            return '<span class="badge bg-secondary">Inactive</span>';
        });

        // Show image thumbnail
        $crud->callbackColumn('image', function ($value, $row) {
            if (empty($value)) {
                return '<span class="text-muted">—</span>';
            }
            return '<img src="/uploads/image/' . $value . '" class="gc-thumb" alt="">';
        });

        // ======== Callbacks ========
        // Set timestamps before insert
        $crud->callbackBeforeInsert(function ($data) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            return $data;
        });

        // Update timestamps before update
        $crud->callbackBeforeUpdate(function ($data) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            return $data;
        });

        // Log after insert (example)
        $crud->callbackAfterInsert(function ($data) {
            log_message('info', 'New product added: ' . ($data['data']['name'] ?? 'unknown'));
            return true;
        });

        // ======== Column Filters ========
        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);
        $crud->setColumnFilterRelation('category_id', 'categories', 'name', 'id', "status = 'active'", 'name ASC');

        // ======== Batch Actions ========
        $crud->setBatchAction('delete_selected', 'Delete Selected');

        // ======== Repeater (Nova-style repeatable groups) ========
        // JSON preset — stores data as JSON in the `specs` column
        $crud->setRepeater('specs', 'Product Specs', [
            ['name' => 'key', 'label' => 'Specification', 'type' => 'text', 'rules' => 'required|max_length[100]'],
            ['name' => 'value', 'label' => 'Value', 'type' => 'text', 'rules' => 'required|max_length[255]'],
        ], 'json');

        // ======== Theme ========
        $crud->setTheme('adminlte4');

        // ======== Render ========
        return $crud->render();
    }

    /**
     * Categories CRUD - Simple demo with enum and custom actions.
     *
     * Demonstrates:
     * - enum field handling
     * - custom actions
     * - WHERE filtering
     * - setReadOnly
     */
    public function categories(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('categories', 'Product Categories');

        $crud->setColumns('name', 'description', 'status', 'created_at');
        $crud->setFields('name', 'description', 'status');

        $crud->displayAs('name', 'Category Name');
        $crud->displayAs('created_at', 'Created');

        // Validation
        $crud->required('name');
        $crud->unique('name');

        // Read-only field
        $crud->setReadOnly('status');

        // Custom action - view products in this category
        $crud->addAction(
            'View Products',
            'bi-eye',
            '/grocery-crud-demo/products?category_id={id}'
        );

        // Column filters
        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('status', 'dropdown', ['active' => 'Active', 'inactive' => 'Inactive']);

        // Batch Actions
        $crud->setBatchAction('delete_selected', 'Hapus yang Dipilih');

        // Order
        $crud->orderBy('name', 'ASC');

        // Set language
        $crud->setLanguage('indonesian');

        return $crud->render();
    }

    /**
     * Tags CRUD - Minimal demo with custom field type.
     *
     * Demonstrates:
     * - setFieldType override
     * - minimal configuration
     * - custom subject
     */
    public function tags(): ResponseInterface|string
    {
        $crud = new GroceryCrud();

        // Set table with custom subject
        $crud->setTable('tags', 'Product Tags');

        // Columns
        $crud->setColumns('name', 'color', 'created_at');

        // Fields in form
        $crud->setFields('name', 'color');

        // Override auto-detected field type to use color picker
        $crud->setFieldType('color', 'color');

        // Labels
        $crud->displayAs('color', 'Tag Color');
        $crud->displayAs('name', 'Tag Name');
        $crud->displayAs('created_at', 'Created');

        // Validation
        $crud->required('name');
        $crud->unique('name');

        // Add a custom "Preview" action
        $crud->addAction(
            'Preview',
            'bi-eye',
            '#',
            'btn-preview'
        );

        // Callback to show color swatch in table
        $crud->callbackColumn('color', function ($value, $row) {
            return '<span class="badge" style="background-color: ' . htmlspecialchars($value) . '; color: #fff;">'
                . htmlspecialchars($value) . '</span>';
        });

        // Column filters
        $crud->setColumnFilter('name', 'text');

        // Set language
        $crud->setLanguage('indonesian');

        $crud->orderBy('name', 'ASC');

        return $crud->render();
    }
}
