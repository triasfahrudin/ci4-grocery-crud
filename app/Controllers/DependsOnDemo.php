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
    /**
     * Main index page - shows demo info and link to the CRUD.
     */
    public function index(): string
    {
        return <<<'HTML'
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
            <div class="container py-5">
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
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-primary">
                            <div class="card-body text-center py-5">
                                <div class="display-1 text-primary mb-3">
                                    <i class="bi bi-eye-slash"></i>
                                </div>
                                <h3 class="card-title">Action: <code>show</code></h3>
                                <p class="card-text text-muted">
                                    Field <strong>discount_price</strong> &amp; <strong>discount_percent</strong>
                                    hanya tampil saat switch <code>has_discount</code> ON.
                                </p>
                                <p class="small text-muted">
                                    Saat OFF: field hilang total + <code>disabled</code> agar nilainya tidak terkirim.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-success">
                            <div class="card-body text-center py-5">
                                <div class="display-1 text-success mb-3">
                                    <i class="bi bi-unlock"></i>
                                </div>
                                <h3 class="card-title">Action: <code>enable</code></h3>
                                <p class="card-text text-muted">
                                    Field <strong>shipping_weight</strong> &amp; <strong>shipping_notes</strong>
                                    hanya aktif saat switch <code>requires_shipping</code> ON.
                                </p>
                                <p class="small text-muted">
                                    Saat OFF: field tetap terlihat tapi <code>disabled</code> (tidak bisa diisi).
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
                        $crud->dependsOn('discount_price', 'has_discount', true);

                        // Sembunyikan discount_percent jika has_discount tidak dicentang
                        $crud->dependsOn('discount_percent', 'has_discount', true);

                        // Nonaktifkan shipping_weight jika requires_shipping tidak dicentang
                        $crud->dependsOn('shipping_weight', 'requires_shipping', true, 'enable');

                        // Nonaktifkan shipping_notes jika requires_shipping tidak dicentang
                        $crud->dependsOn('shipping_notes', 'requires_shipping', true, 'enable');</code></pre>
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
        HTML;
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

        // ─── Theme ──────────────────────────────────────────
        $crud->setTheme('bootstrap5');

        // ─── Render ──────────────────────────────────────────
        return $crud->render();
    }
}
