<?php
/**
 * Import Demo — Halaman utama overview.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Demo — Grocery CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?= view('layouts/navbar', [
        'brandUrl'    => '/import-demo',
        'brandIcon'   => 'bi-upload',
        'brandText'   => 'Import Demo <small class="fw-light">CSV/Excel</small>',
        'tabs'        => [
            'index'    => ['url' => '/import-demo',            'icon' => 'bi-info-circle', 'label' => 'Overview'],
            'contacts' => ['url' => '/import-demo/contacts',   'icon' => 'bi-people',      'label' => 'Contacts'],
        ],
        'activePage'  => 'index',
    ]) ?>

    <div class="container py-3">
        <div class="row mb-4">
            <div class="col">
                <h1 class="display-5 fw-bold">
                    <i class="bi bi-upload text-primary me-2"></i>CSV/Excel Import
                </h1>
                <p class="text-muted lead">
                    Unggah CSV atau Excel (.xlsx) — deteksi otomatis pemetaan kolom, pratinjau, lalu impor data.
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
                    <h5>Langkah 1: Upload</h5>
                    <p class="text-muted small px-3">
                        Pilih file CSV atau Excel (.xlsx). File diproses di backend untuk ekstrak header &amp; pratinjau.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-success text-center py-4">
                    <div class="display-3 text-success mb-3">
                        <i class="bi bi-diagram-2"></i>
                    </div>
                    <h5>Langkah 2: Pemetaan</h5>
                    <p class="text-muted small px-3">
                        Cocokkan kolom file dengan kolom form. Deteksi otomatis pemetaan berdasarkan kemiripan nama.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-info text-center py-4">
                    <div class="display-3 text-info mb-3">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <h5>Langkah 3: Import</h5>
                    <p class="text-muted small px-3">
                        Pratinjau data baris pertama, konfirmasi, lalu impor. Error per-baris dilaporkan.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <a href="/import-demo/contacts" class="btn btn-lg btn-primary px-5">
                <i class="bi bi-people me-2"></i>Buka CRUD Kontak
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
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Semua Demo
            </a>
        </div>
    </div>
</body>
</html>
