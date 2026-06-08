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
 * - Soft Delete
 * - Sub-Grid (expandable nested related records)
 *
 * Usage:
 *   1. Run migration: php spark migrate -n App
 *   2. Start server:   php spark serve
 *   3. Open browser:   http://localhost:8080/grocery-crud-demo
 */
class GroceryCrudDemo extends Controller
{
    /**
     * Apply RBAC permissions from the database to a GroceryCrud instance.
     *
     * Reads the current user's role from session, fetches permissions
     * from the `permissions` table, and applies them via setPermission().
     *
     * @param \GroceryCrud\GroceryCrud $crud
     * @param string $tableName Table name to look up permissions for
     */
    private function applyRbac(\GroceryCrud\GroceryCrud $crud, string $tableName): void
    {
        $role = session()->get('role', 'viewer');

        // Load permissions from database
        $permModel = model('App\Models\PermissionModel');
        $allowedActions = $permModel->getAllowedActions($role, $tableName);

        if (!empty($allowedActions)) {
            // Set the permission callback so GroceryCrud knows which role
            $crud->setPermissionCallback(function () use ($role) {
                return $role;
            });

            // Define which actions are allowed for this role & table
            $crud->setPermission($role, $allowedActions);
        }
    }

    /**
     * Get the navbar HTML with user info and logout button.
     */
    private function renderNavbar(): string
    {
        $role = session()->get('role', 'viewer');
        $fullName = session()->get('fullName') ?: session()->get('username');
        $badgeClass = match ($role) {
            'admin'  => 'bg-danger',
            'editor' => 'bg-warning text-dark',
            default  => 'bg-secondary',
        };

        return '
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold" href="/grocery-crud-demo">
                    <i class="bi bi-grid me-2"></i>Grocery CRUD <small class="fw-light">RBAC Demo</small>
                </a>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-light small">
                        <i class="bi bi-person-circle me-1"></i>'
                        . htmlspecialchars($fullName) .
                        ' <span class="badge ' . $badgeClass . ' ms-1">' . ucfirst($role) . '</span>
                    </span>
                    <a href="/auth/profile" class="btn btn-outline-light btn-sm" title="Profile">
                        <i class="bi bi-person-vcard"></i>
                    </a>
                    <a href="/auth/logout" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </a>
                </div>
            </div>
        </nav>';
    }

    /**
     * Main index - shows a menu of demo options.
     */
    public function index(): string
    {
        $role = session()->get('role', 'viewer');
        $roleBadge = match ($role) {
            'admin'  => '<span class="badge bg-danger">Admin</span>',
            'editor' => '<span class="badge bg-warning text-dark">Editor</span>',
            default  => '<span class="badge bg-secondary">Viewer</span>',
        };

        $permNotes = match ($role) {
            'admin'  => '<span class="text-danger fw-semibold">Full access:</span> add, edit, delete, view, export',
            'editor' => '<span class="text-warning fw-semibold">Limited access:</span> add, edit, view, export (no delete)',
            default  => '<span class="text-secondary fw-semibold">Read-only:</span> view and export only',
        };

        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Grocery CRUD Demo - RBAC</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            {$this->renderNavbar()}
            <div class="container py-3">
                <div class="row mb-4">
                    <div class="col">
                        <h1 class="display-5 fw-bold">Grocery CRUD Demo</h1>
                        <p class="text-muted">CodeIgniter 4 - Full-featured CRUD Library with <span class="fw-bold">Role-Based Access Control</span></p>
                        <div class="alert alert-info py-2 d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check fs-5"></i>
                            <span>Your role: {$roleBadge} — {$permNotes}</span>
                        </div>
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
                        <div class="card h-100 shadow-sm border-success">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-upload me-2 text-success"></i>Import Demo
                                </h5>
                                <p class="card-text text-muted small">CSV/Excel Import: upload file, auto-mapping kolom, preview, lalu import data.</p>
                                <a href="/import-demo" class="btn btn-success">Open</a>
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
                        <div class="card h-100 shadow-sm border-info">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-toggle-on me-2 text-info"></i>Depends On
                                </h5>
                                <p class="card-text text-muted small">Dynamic Form Conditions: show/hide &amp; enable/disable field berdasarkan nilai field lain.</p>
                                <a href="/depends-on-demo" class="btn btn-info text-white">Open</a>
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
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-diagram-2 me-2 text-info"></i>Variants
                                </h5>
                                <p class="card-text text-muted small">Sub-Grid demo with expandable nested variant table.</p>
                                <a href="/grocery-crud-demo/variants" class="btn btn-info text-white">Open</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-danger">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-journal-text me-2 text-danger"></i>Activity Log
                                </h5>
                                <p class="card-text text-muted small">Audit Trail demo — auto-record Insert, Update, Delete, Restore with old/new data diff.</p>
                                <a href="/activity-log-demo" class="btn btn-danger">Open</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ======== Theme Demos ======== -->
                <h2 class="h3 mt-5 mb-3 fw-bold">Theme Demos</h2>
                <p class="text-muted mb-4">See the same CRUD rendered with different themes.</p>
                <div class="row g-4">
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm border-primary">
                            <div class="card-body text-center">
                                <div class="display-6 mb-2 text-primary">B5</div>
                                <h5 class="card-title">Bootstrap 5</h5>
                                <p class="card-text text-muted small">Default Bootstrap 5 theme with clean, modern design.</p>
                                <a href="/grocery-crud-demo/theme-demo/bootstrap5" class="btn btn-primary w-100">Open Demo</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm border-info">
                            <div class="card-body text-center">
                                <div class="display-6 mb-2 text-info">AL</div>
                                <h5 class="card-title">AdminLTE 4</h5>
                                <p class="card-text text-muted small">Admin dashboard theme with sidebar and dark mode.</p>
                                <a href="/grocery-crud-demo/theme-demo/adminlte4" class="btn btn-info w-100 text-white">Open Demo</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm border-success">
                            <div class="card-body text-center">
                                <div class="display-6 mb-2 text-success">TW</div>
                                <h5 class="card-title">Tailwind CSS</h5>
                                <p class="card-text text-muted small">Utility-first CSS framework with modern look.</p>
                                <a href="/grocery-crud-demo/theme-demo/tailwind" class="btn btn-success w-100">Open Demo</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm border-danger">
                            <div class="card-body text-center">
                                <div class="display-6 mb-2 text-danger">MZ</div>
                                <h5 class="card-title">Materialize</h5>
                                <p class="card-text text-muted small">Material Design CSS framework with smooth animations.</p>
                                <a href="/grocery-crud-demo/theme-demo/materialize" class="btn btn-danger w-100">Open Demo</a>
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
                                            <th>Variants</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Basic CRUD</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>Belongs_to Relation</td><td>✓</td><td>-</td><td>-</td><td>✓</td></tr>
                                        <tr><td>N-to-N Relation</td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                        <tr><td>Callbacks</td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                        <tr><td>Validation</td><td>✓</td><td>✓</td><td>-</td><td>✓</td></tr>
                                        <tr><td>File Upload</td><td>✓</td><td>-</td><td>-</td><td>✓</td></tr>
                                        <tr><td>Custom Actions</td><td>-</td><td>✓</td><td>-</td><td>-</td></tr>
                                        <tr><td>Export</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>Search</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>Field Type Override</td><td>✓</td><td>-</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>Column Filters</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>Batch Actions</td><td>✓</td><td>✓</td><td>-</td><td>✓</td></tr>
                                        <tr><td>Sort by Headers</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>Image Viewer</td><td>✓</td><td>-</td><td>-</td><td>✓</td></tr>
                                        <tr><td>Repeater Fields</td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                        <tr><td>Sub-Grid</td><td>-</td><td>-</td><td>-</td><td>✓</td></tr>
                                        <tr><td>Soft Delete</td><td>✓</td><td>-</td><td>-</td><td>✓</td></tr>
                                        <tr><td>AdminLTE 4 Theme</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>RBAC (Role-Based Access)</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                        <tr><td>Activity Log / Audit Trail</td><td>✓</td><td>✓</td><td>-</td><td>-</td></tr>
                                    </tbody>
                                </table>
                                <p class="text-muted small mt-2 mb-0">
                                    <strong>4 Themes Available:</strong>
                                    <a href="/grocery-crud-demo/theme-demo/bootstrap5" class="text-decoration-none">Bootstrap 5</a> ·
                                    <a href="/grocery-crud-demo/theme-demo/adminlte4" class="text-decoration-none">AdminLTE 4</a> ·
                                    <a href="/grocery-crud-demo/theme-demo/tailwind" class="text-decoration-none">Tailwind CSS</a> ·
                                    <a href="/grocery-crud-demo/theme-demo/materialize" class="text-decoration-none">Materialize</a>
                                </p>
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

        // ======== Description as WYSIWYG richtext editor ========
        $crud->setFieldType('description', 'richtext');

        // ======== Relation to categories (belongs_to) ========
        $crud->setRelation('category_id', 'categories', 'name', "status = 'active'", 'name ASC');

        // ======== Inline Editing ========
        // Enable double-click to edit on the table
        $crud->setInlineEditing(true);
        $crud->setInlineEditColumns(['name', 'price', 'stock', 'is_active', 'category_id']);

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
        $crud->setBatchAction('restore_selected', 'Restore Selected');

        // ======== Repeater (Nova-style repeatable groups) ========
        // JSON preset — stores data as JSON in the `specs` column
        $crud->setRepeater('specs', 'Product Specs', [
            ['name' => 'key', 'label' => 'Specification', 'type' => 'text', 'rules' => 'required|max_length[100]'],
            ['name' => 'value', 'label' => 'Value', 'type' => 'text', 'rules' => 'required|max_length[255]'],
        ], 'json');

        // ======== Theme ========
        $crud->setTheme('bootstrap5');

        // ======== Soft Delete ========
        $crud->setSoftDelete();

        // ======== RBAC ========
        $this->applyRbac($crud, 'products');

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

        // RBAC
        $this->applyRbac($crud, 'categories');

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

        // RBAC
        $this->applyRbac($crud, 'tags');

        return $crud->render();
    }

    /**
     * Theme Demo - Renders a CRUD with the specified theme.
     *
     * @param string $theme Theme name (bootstrap5, adminlte4, tailwind, materialize)
     *
     * @return ResponseInterface|string
     */
    public function themeDemo(string $theme): ResponseInterface|string
    {
        $allowedThemes = ['bootstrap5', 'adminlte4', 'tailwind', 'materialize'];
        if (!in_array($theme, $allowedThemes)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $crud = new GroceryCrud();
        $crud->setTable('products', 'Products (' . ucfirst($theme) . ' Theme)');

        // ======== Columns ========
        $crud->setColumns('name', 'category_id', 'price', 'stock', 'is_active', 'created_at');
        $crud->setFields('name', 'category_id', 'description', 'price', 'stock', 'is_active');

        // ======== Display labels ========
        $crud->displayAs('name', 'Product Name');
        $crud->displayAs('category_id', 'Category');
        $crud->displayAs('is_active', 'Active');
        $crud->displayAs('price', 'Price (Rp)');

        // ======== Active field as dropdown ========
        $crud->setFieldType('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);

        // ======== Relation to categories (belongs_to) ========
        $crud->setRelation('category_id', 'categories', 'name', "status = 'active'", 'name ASC');

        // ======== Validation ========
        $crud->required('name');
        $crud->required('price');
        $crud->unique('name');

        // ======== Column callbacks ========
        $crud->callbackColumn('price', function ($value, $row) {
            return 'Rp ' . number_format((float) $value, 0, ',', '.');
        });
        $crud->callbackColumn('is_active', function ($value, $row) {
            if ($value == 1) {
                return '<span class="badge bg-success">Active</span>';
            }
            return '<span class="badge bg-secondary">Inactive</span>';
        });

        // ======== Column Filters ========
        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);
        $crud->setColumnFilterRelation('category_id', 'categories', 'name', 'id', "status = 'active'", 'name ASC');

        // ======== Batch Actions ========
        $crud->setBatchAction('delete_selected', 'Delete Selected');
        $crud->setBatchAction('restore_selected', 'Restore Selected');

        // ======== Sub-Grid: product variants ========
        $crud->setSubGrid(
            'variants',
            'product_variants',
            'product_id',
            ['name', 'price', 'stock', 'sku'],
            ['name' => 'Variant', 'price' => 'Price', 'stock' => 'Stock', 'sku' => 'SKU']
        );

        // ======== Soft Delete ========
        $crud->setSoftDelete();

        // ======== RBAC ========
        $this->applyRbac($crud, 'products');

        // ======== Theme ========
        $crud->setTheme($theme);

        return $crud->render();
    }

    /**
     * Product Variants CRUD - Sub-Grid demo.
     *
     * Shows products with expandable sub-grid of their variants.
     * Demonstrates:
     * - setSubGrid (nested related records in expandable rows)
     * - setRelation (category_id)
     * - setUpload (image)
     * - Soft Delete
     */
    public function variants(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('products', 'Products with Variants');

        // ======== Columns ========
        $crud->setColumns('name', 'category_id', 'price', 'stock', 'is_active', 'image');
        $crud->setFields('name', 'category_id', 'description', 'price', 'stock', 'is_active', 'image');

        // ======== Display labels ========
        $crud->displayAs('name', 'Product Name');
        $crud->displayAs('category_id', 'Category');
        $crud->displayAs('is_active', 'Active');
        $crud->displayAs('stock', 'Stock Quantity');

        // ======== Active field as dropdown ========
        $crud->setFieldType('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);

        // ======== Relation to categories ========
        $crud->setRelation('category_id', 'categories', 'name', "status = 'active'", 'name ASC');

        // ======== File upload ========
        $crud->setUpload('image', [
            'allowedTypes' => 'jpg|jpeg|png|gif|webp',
            'maxSize'      => 1024,
            'encryptFileName' => true,
        ]);

        // ======== Validation ========
        $crud->required('name');
        $crud->unique('name');

        // ======== Sub-Grid: product variants ========
        $crud->setSubGrid(
            'variants',          // field identifier
            'product_variants',   // related table
            'product_id',         // FK in related table
            ['name', 'price', 'stock', 'sku'], // columns to show
            ['name' => 'Variant', 'price' => 'Price', 'stock' => 'Stock', 'sku' => 'SKU'] // labels
        );

        // ======== Sub-Grid: product tags (NtoN) ========
        $crud->setSubGrid(
            'tags',
            'product_tags',
            'product_id',
            ['tag_id'],
            ['tag_id' => 'Tag'],
            ['tag_id' => ['tags', 'name', 'tag_id', 'id']] // resolve tag_id → tag name
        );

        // ======== Image viewer ========
        $crud->callbackColumn('image', function ($value, $row) {
            if (empty($value)) {
                return '<span class="text-muted">—</span>';
            }
            return '<img src="/uploads/image/' . $value . '" class="gc-thumb" alt="">';
        });

        // ======== Active status badge ========
        $crud->callbackColumn('is_active', function ($value, $row) {
            if ($value == '1') {
                return '<span class="badge bg-success">Active</span>';
            }
            return '<span class="badge bg-secondary">Inactive</span>';
        });

        // ======== Theme ========
        $crud->setTheme('bootstrap5');

        // ======== Soft Delete ========
        $crud->setSoftDelete();

        // RBAC
        $this->applyRbac($crud, 'product_variants');

        return $crud->render();
    }
}
