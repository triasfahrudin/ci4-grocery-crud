<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use GroceryCrud\GroceryCrud;

/**
 * Import Demo Controller
 *
 * Demonstrates the CSV/Excel Import feature:
 * - Upload CSV or XLSX file
 * - Auto-detect column mapping to form fields
 * - Preview data before importing
 * - Bulk insert records
 *
 * Usage:
 *   1. Run migration: php spark migrate -n App
 *   2. Start server:  php spark serve
 *   3. Open browser:  http://localhost:8080/import-demo
 */
class ImportDemo extends Controller
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
            <title>Import Demo - Grocery CRUD</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        </head>
        <body>
            <div class="container py-5">
                <div class="row mb-4">
                    <div class="col">
                        <h1 class="display-5 fw-bold">
                            <i class="bi bi-upload text-primary me-2"></i>CSV/Excel Import
                        </h1>
                        <p class="text-muted lead">
                            Upload CSV atau Excel (.xlsx) — auto-detect column mapping, preview, lalu import data.
                        </p>
                        <hr>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-primary text-center py-4">
                            <div class="display-3 text-primary mb-3">
                                <i class="bi bi-filetype-csv"></i>
                            </div>
                            <h5>Step 1: Upload</h5>
                            <p class="text-muted small px-3">
                                Pilih file CSV atau Excel (.xlsx). File diproses di backend untuk ekstrak headers &amp; preview.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-success text-center py-4">
                            <div class="display-3 text-success mb-3">
                                <i class="bi bi-diagram-2"></i>
                            </div>
                            <h5>Step 2: Mapping</h5>
                            <p class="text-muted small px-3">
                                Cocokkan kolom file dengan field form. Auto-detect mapping berdasarkan kemiripan nama.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-info text-center py-4">
                            <div class="display-3 text-info mb-3">
                                <i class="bi bi-check2-circle"></i>
                            </div>
                            <h5>Step 3: Import</h5>
                            <p class="text-muted small px-3">
                                Preview data baris pertama, konfirmasi, lalu import. Error per-row dilaporkan.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <a href="/import-demo/contacts" class="btn btn-lg btn-primary px-5">
                        <i class="bi bi-people me-2"></i>Open Contacts CRUD
                    </a>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-code-slash me-2"></i>Source Code</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-dark text-light p-3 rounded mb-0"><code>// Enable import (default: true, called explicitly for demo)
                        $crud->setImportable();
                        //
                        // Optional: disable import for specific CRUD
                        // $crud->setImportable(false);
                        //
                        // CSV import works out of the box.
                        // XLSX requires: composer require phpoffice/phpspreadsheet</code></pre>
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
     * Contacts CRUD with Import enabled.
     *
     * Demonstrates CSV/Excel Import feature:
     * - setImportable() enables the Import button in toolbar
     * - Upload CSV → auto-map columns → preview → import
     */
    public function contacts(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('contacts', 'Contact');

        // ─── Columns ─────────────────────────────────────────
        $crud->setColumns('name', 'email', 'phone', 'company', 'is_active', 'created_at');

        // ─── Form Fields ─────────────────────────────────────
        $crud->setFields('name', 'email', 'phone', 'company', 'is_active');

        // ─── Labels ──────────────────────────────────────────
        $crud->displayAs('name', 'Full Name');
        $crud->displayAs('email', 'Email Address');
        $crud->displayAs('phone', 'Phone Number');
        $crud->displayAs('company', 'Company');
        $crud->displayAs('is_active', 'Active');

        // ─── Field Types ─────────────────────────────────────
        $crud->setFieldType('is_active', 'true_false');

        // ─── Validation ──────────────────────────────────────
        $crud->required('name');
        $crud->required('email');
        $crud->unique('email');
        $crud->setRules('email', 'valid_email');

        // ─── Column Display ──────────────────────────────────
        $crud->callbackColumn('is_active', function ($value) {
            return $value == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';
        });

        // ─── Column Filters ──────────────────────────────────
        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('email', 'text');
        $crud->setColumnFilter('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);

        // ─── Import CSV/Excel ────────────────────────────────
        $crud->setImportable();
        // Note: XLSX import requires `composer require phpoffice/phpspreadsheet`
        // CSV import works out of the box

        // ─── Theme ──────────────────────────────────────────
        $crud->setTheme('bootstrap5');

        // ─── Render ──────────────────────────────────────────
        return $crud->render();
    }
}
