<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use GroceryCrud\GroceryCrud;

/**
 * DependsOn Demo Controller
 *
 * Demonstrates the Dynamic Form Conditions (dependsOn) feature:
 * - Show/hide fields based on another field's value
 * - Enable/disable fields based on another field's value
 *
 * Usage:
 *   1. Run migration: php spark migrate -n App
 *   2. Start server:  php spark serve
 *   3. Open browser:  http://localhost:8080/depends-on-demo
 */
class DependsOnDemo extends Controller
{
    private function renderNavbar(string $activePage = ''): string
    {
        $fullName = session()->get('fullName') ?: session()->get('username') ?: 'Guest';

        $tabs = [
            'index'    => ['url' => '/depends-on-demo',         'icon' => 'bi-info-circle', 'label' => 'Overview'],
            'products' => ['url' => '/depends-on-demo/products', 'icon' => 'bi-box-seam',    'label' => 'Products'],
        ];

        $tabsHtml = '';
        foreach ($tabs as $key => $tab) {
            $activeClass = $key === $activePage ? 'btn-info' : 'btn-outline-light';
            $tabsHtml .= '<a href="' . $tab['url'] . '" class="btn btn-sm ' . $activeClass . '">'
                . '<i class="bi ' . $tab['icon'] . ' me-1"></i>' . $tab['label'] . '</a>' . "\n                    ";
        }

        return '
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold" href="/depends-on-demo">
                    <i class="bi bi-toggle-on me-2"></i>DependsOn Demo
                </a>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    ' . $tabsHtml . '
                    <div class="vr text-light opacity-25 mx-1"></div>
                    <span class="text-light small">
                        <i class="bi bi-person-circle me-1"></i>' . htmlspecialchars($fullName) . '
                    </span>
                    <a href="/grocery-crud-demo" class="btn btn-outline-light btn-sm" title="All Demos">
                        <i class="bi bi-grid me-1"></i>All Demos
                    </a>
                    <a href="/auth/logout" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>
                    </a>
                </div>
            </div>
        </nav>';
    }

    /**
     * Main index page - shows demo info and link to the CRUD.
     */
    public function index(): string
    {
        ob_start(); ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>DependsOn Demo - Grocery CRUD</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        </head>
        <body>
            <?= $this->renderNavbar('index') ?>
            <div class="container py-3">
                <div class="row mb-4">
                    <div class="col">
                        <h1 class="display-5 fw-bold">
                            <i class="bi bi-toggle-on text-primary me-2"></i>Dynamic Form Conditions
                        </h1>
                        <p class="text-muted lead">
                            Show/hide atau enable/disable field berdasarkan nilai field lain (<code>dependsOn</code>).
                        </p>
                        <hr>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-primary">
                            <div class="card-body text-center py-4">
                                <div class="display-3 text-primary mb-3"><i class="bi bi-eye-slash"></i></div>
                                <h5>Action: <code>show</code></h5>
                                <p class="card-text text-muted small">
                                    Field <strong>discount_price</strong> &amp; <strong>discount_percent</strong>
                                    hanya tampil saat switch <code>has_discount</code> ON.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-success">
                            <div class="card-body text-center py-4">
                                <div class="display-3 text-success mb-3"><i class="bi bi-unlock"></i></div>
                                <h5>Action: <code>enable</code></h5>
                                <p class="card-text text-muted small">
                                    Field <strong>shipping_weight</strong> &amp; <strong>shipping_notes</strong>
                                    hanya aktif saat switch <code>requires_shipping</code> ON.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-warning">
                            <div class="card-body text-center py-4">
                                <div class="display-3 text-warning mb-3"><i class="bi bi-link-45deg"></i></div>
                                <h5>Dependent Dropdown</h5>
                                <p class="card-text text-muted small">
                                    Field <strong>Category</strong> → pilih kategori, lalu <strong>Subcategory</strong>
                                    otomatis terfilter (chained/cascading select).
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <a href="/depends-on-demo/products" class="btn btn-lg btn-primary px-5">
                        <i class="bi bi-box-seam me-2"></i>Open Demo CRUD
                    </a>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-code-slash me-2"></i>Source Code</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-dark text-light p-3 rounded mb-0"><code>// Sembunyikan discount_price jika has_discount tidak dicentang
                        $crud->dependsOn('discount_price', 'has_discount', true, 'show');

                        // Sembunyikan discount_percent jika has_discount tidak dicentang
                        $crud->dependsOn('discount_percent', 'has_discount', true, 'show');

                        // Nonaktifkan shipping_weight jika requires_shipping tidak dicentang
                        $crud->dependsOn('shipping_weight', 'requires_shipping', true, 'enable');

                        // Nonaktifkan shipping_notes jika requires_shipping tidak dicentang
                        $crud->dependsOn('shipping_notes', 'requires_shipping', true, 'enable');

                        // Dependent Dropdown: Category → Subcategory
                        $crud->setRelation('category_id', 'categories', 'name');
                        $crud->setDependentRelation('subcategory_id', 'category_id', 'subcategories', 'category_id', 'name');</code></pre>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="/grocery-crud-demo" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to All Demos
                    </a>
                </div>
            </div>
        </body>
        </html>
        <?php return ob_get_clean();
    }

    /**
     * Products CRUD with dependsOn.
     *
     * Demonstrates Dynamic Form Conditions:
     * - discount_price & discount_percent: show/hide by has_discount
     * - shipping_weight & shipping_notes: enable/disable by requires_shipping
     */
    public function products(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('depends_on_demo', 'Product');

        // ─── Columns ─────────────────────────────────────────
        $crud->setColumns(
            'name',
            'category_id',
            'subcategory_id',
            'price',
            'has_discount',
            'discount_price',
            'requires_shipping',
            'shipping_weight',
            'is_active'
        );

        // ─── Form Fields ─────────────────────────────────────
        $crud->setFields(
            'name',
            'category_id',
            'subcategory_id',
            'price',
            'has_discount',
            'discount_price',
            'discount_percent',
            'requires_shipping',
            'shipping_weight',
            'shipping_notes',
            'is_active'
        );

        // ─── Labels ──────────────────────────────────────────
        $crud->displayAs('name', 'Product Name');
        $crud->displayAs('category_id', 'Category');
        $crud->displayAs('subcategory_id', 'Subcategory');
        $crud->displayAs('price', 'Base Price');
        $crud->displayAs('has_discount', 'Have Discount?');
        $crud->displayAs('discount_price', 'Discount Price');
        $crud->displayAs('discount_percent', 'Discount (%)');
        $crud->displayAs('requires_shipping', 'Requires Shipping?');
        $crud->displayAs('shipping_weight', 'Weight (kg)');
        $crud->displayAs('shipping_notes', 'Shipping Notes');
        $crud->displayAs('is_active', 'Active');

        // ─── Field Types ─────────────────────────────────────
        $crud->setFieldType('has_discount', 'true_false');
        $crud->setFieldType('requires_shipping', 'true_false');
        $crud->setFieldType('is_active', 'true_false');
        $crud->setFieldType('discount_percent', 'integer');

        // ─── Dependent Dropdown (Cascading Chained Select) ───
        // Category dropdown (parent)
        $crud->setRelation('category_id', 'categories', 'name', "status = 'active'");
        // Subcategory dropdown (child) — filtered by selected category
        $crud->setDependentRelation(
            'subcategory_id',
            'category_id',
            'subcategories',
            'category_id',
            'name'
        );

        // ─── Column Display ──────────────────────────────────
        $crud->callbackColumn('has_discount', function ($value) {
            return $value == 1
                ? '<span class="badge bg-success">Yes</span>'
                : '<span class="badge bg-secondary">No</span>';
        });
        $crud->callbackColumn('requires_shipping', function ($value) {
            return $value == 1
                ? '<span class="badge bg-success">Yes</span>'
                : '<span class="badge bg-secondary">No</span>';
        });

        // ─── Dynamic Form Conditions (dependsOn) ─────────────
        //
        // ACTION 'show': Sembunyikan field jika kondisi tidak terpenuhi
        $crud->dependsOn('discount_price', 'has_discount', true, 'show');
        $crud->dependsOn('discount_percent', 'has_discount', true, 'show');
        //
        // ACTION 'enable': Nonaktifkan field jika kondisi tidak terpenuhi
        $crud->dependsOn('shipping_weight', 'requires_shipping', true, 'enable');
        $crud->dependsOn('shipping_notes', 'requires_shipping', true, 'enable');

        // ─── Validation ──────────────────────────────────────
        $crud->required('name');
        $crud->required('price');
        $crud->setRules('price', 'numeric|greater_than[0]');
        $crud->setRules('discount_price', 'numeric|greater_than[0]');
        $crud->setRules('discount_percent', 'integer|greater_than_equal_to[0]|less_than_equal_to[100]');
        $crud->setRules('shipping_weight', 'numeric|greater_than[0]');

        // ─── Column Callbacks ────────────────────────────────
        $crud->callbackColumn('price', function ($value, $row) {
            return 'Rp ' . number_format((float) $value, 0, ',', '.');
        });
        $crud->callbackColumn('discount_price', function ($value, $row) {
            if (empty($value)) {
                return '<span class="text-muted">—</span>';
            }
            return 'Rp ' . number_format((float) $value, 0, ',', '.');
        });
        $crud->callbackColumn('is_active', function ($value, $row) {
            return $value == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';
        });

        // ─── Column Filters ──────────────────────────────────
        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);

        // ─── Import CSV/Excel ────────────────────────────────
        $crud->setImportable();
        // Note: XLSX import requires `composer require phpoffice/phpspreadsheet`
        // CSV import works out of the box

        // ─── Theme ──────────────────────────────────────────
        $crud->setTheme('bootstrap5');

        // ─── Render ──────────────────────────────────────────
        return $crud->setPageHeader($this->renderNavbar('products'))->render();
    }
}
