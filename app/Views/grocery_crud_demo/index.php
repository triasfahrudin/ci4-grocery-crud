<?php
/**
 * Grocery CRUD Demo — Halaman utama overview.
 *
 * Data dari controller:
 * - $role       (string) role user
 * - $roleBadge  (string) HTML badge role
 * - $permNotes  (string) HTML deskripsi permission
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grocery CRUD Demo — RBAC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?= view('layouts/navbar', [
        'brandUrl'    => '/grocery-crud-demo',
        'brandIcon'   => 'bi-grid',
        'brandText'   => 'Grocery CRUD <small class="fw-light">RBAC Demo</small>',
        'tabs'        => [
            'index'      => ['url' => '/grocery-crud-demo',          'icon' => 'bi-grid',         'label' => 'Overview'],
            'products'   => ['url' => '/grocery-crud-demo/products', 'icon' => 'bi-box-seam',     'label' => 'Products'],
            'categories' => ['url' => '/grocery-crud-demo/categories','icon' => 'bi-bookmark',     'label' => 'Categories'],
            'tags'       => ['url' => '/grocery-crud-demo/tags',     'icon' => 'bi-tags',         'label' => 'Tags'],
            'variants'   => ['url' => '/grocery-crud-demo/variants', 'icon' => 'bi-diagram-2',    'label' => 'Variants'],
        ],
        'activePage'  => 'index',
        'showRole'    => true,
        'showProfile' => true,
        'showAllDemos' => false,
    ]) ?>

    <div class="container py-3">
        <div class="row mb-4">
            <div class="col">
                <h1 class="display-5 fw-bold">Grocery CRUD Demo</h1>
                <p class="text-muted">CodeIgniter 4 — Full-featured CRUD Library with <span class="fw-bold">Role-Based Access Control</span></p>
                <div class="alert alert-info py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-check fs-5"></i>
                    <span>Your role: <?= $roleBadge ?> — <?= $permNotes ?></span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-box-seam me-2 text-primary"></i>Products</h5>
                        <p class="card-text text-muted small">Basic CRUD with relations, validation, callbacks, upload, and NtoN tags.</p>
                        <a href="/grocery-crud-demo/products" class="btn btn-primary">Open</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-upload me-2 text-success"></i>Import Demo</h5>
                        <p class="card-text text-muted small">CSV/Excel Import: upload file, auto-mapping kolom, preview, lalu import data.</p>
                        <a href="/import-demo" class="btn btn-success">Open</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-tags me-2 text-success"></i>Categories</h5>
                        <p class="card-text text-muted small">Simple CRUD with enum fields and search.</p>
                        <a href="/grocery-crud-demo/categories" class="btn btn-success">Open</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-toggle-on me-2 text-info"></i>Depends On</h5>
                        <p class="card-text text-muted small">Dynamic Form Conditions: show/hide &amp; enable/disable field berdasarkan nilai field lain.</p>
                        <a href="/depends-on-demo" class="btn btn-info text-white">Open</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-bookmark me-2 text-warning"></i>Tags</h5>
                        <p class="card-text text-muted small">Minimal CRUD with color picker field.</p>
                        <a href="/grocery-crud-demo/tags" class="btn btn-warning text-white">Open</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-diagram-2 me-2 text-info"></i>Variants</h5>
                        <p class="card-text text-muted small">Sub-Grid demo with expandable nested variant table.</p>
                        <a href="/grocery-crud-demo/variants" class="btn btn-info text-white">Open</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-danger">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-journal-text me-2 text-danger"></i>Activity Log</h5>
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
                                <tr><td><strong>Calendar View</strong></td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                <tr><td><strong>Field Groups / Tabs</strong></td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                <tr><td><strong>Relation Popover</strong></td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                <tr><td><strong>Record Locking</strong></td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                <tr><td><strong>Export Selected Columns</strong></td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                <tr><td><strong>Activity Log Viewer</strong></td><td>-</td><td>✓</td><td>-</td><td>-</td></tr>
                                <tr><td><strong>Dependent Dropdown</strong></td><td>-</td><td>-</td><td>-</td><td>-</td></tr>
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
