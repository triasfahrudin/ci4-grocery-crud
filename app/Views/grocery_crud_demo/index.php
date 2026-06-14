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
                <p class="text-muted">CodeIgniter 4 — Library CRUD Lengkap dengan <span class="fw-bold">Kontrol Akses Berbasis Peran (RBAC)</span></p>
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
                        <h5 class="card-title"><i class="bi bi-box-seam me-2 text-primary"></i>Produk</h5>
                        <p class="card-text text-muted small">CRUD dasar dengan relasi, validasi, callback, upload, dan tag N-to-N.</p>
                        <a href="/grocery-crud-demo/products" class="btn btn-primary">Buka</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-upload me-2 text-success"></i>Import Demo</h5>
                        <p class="card-text text-muted small">Impor CSV/Excel: unggah file, auto-mapping kolom, pratinjau, lalu impor data.</p>
                        <a href="/import-demo" class="btn btn-success">Buka</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-tags me-2 text-success"></i>Kategori</h5>
                        <p class="card-text text-muted small">CRUD sederhana dengan field enum dan pencarian.</p>
                        <a href="/grocery-crud-demo/categories" class="btn btn-success">Buka</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-toggle-on me-2 text-info"></i>Depends On</h5>
                        <p class="card-text text-muted small">Kondisi Form Dinamis: tampil/sembunyi &amp; aktif/nonaktif field berdasarkan nilai field lain.</p>
                        <a href="/depends-on-demo" class="btn btn-info text-white">Buka</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-bookmark me-2 text-warning"></i>Tags</h5>
                        <p class="card-text text-muted small">CRUD minimal dengan field color picker.</p>
                        <a href="/grocery-crud-demo/tags" class="btn btn-warning text-white">Buka</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-diagram-2 me-2 text-info"></i>Variants</h5>
                        <p class="card-text text-muted small">Demo Sub-Grid dengan tabel varian bertingkat yang bisa diperluas.</p>
                        <a href="/grocery-crud-demo/variants" class="btn btn-info text-white">Buka</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-danger">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-journal-text me-2 text-danger"></i>Activity Log</h5>
                        <p class="card-text text-muted small">Demo Audit Trail — merekam otomatis Insert, Update, Delete, Restore dengan perbandingan data lama/baru.</p>
                        <a href="/activity-log-demo" class="btn btn-danger">Buka</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-warning">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-folder2-open me-2 text-warning"></i>File Manager</h5>
                        <p class="card-text text-muted small">Demo File Manager — upload file, buat folder, rename, delete, copy, move, dan cari file langsung dari toolbar CRUD.</p>
                        <a href="/grocery-crud-demo/products" class="btn btn-warning text-white">Buka</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-copy me-2 text-info"></i>Clone / Duplicate</h5>
                        <p class="card-text text-muted small">Demo Duplikasi Record — salin record hanya dengan satu klik. Tombol <i class="bi bi-copy"></i> di samping Edit.</p>
                        <a href="/clone-demo" class="btn btn-info text-white">Buka</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======== Theme Demos ======== -->
        <h2 class="h3 mt-5 mb-3 fw-bold">Demo Tema</h2>
        <p class="text-muted mb-4">Lihat CRUD yang sama ditampilkan dengan tema berbeda.</p>
        <div class="row g-4">
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-body text-center">
                        <div class="display-6 mb-2 text-primary">B5</div>
                        <h5 class="card-title">Bootstrap 5</h5>
                        <p class="card-text text-muted small">Tema Bootstrap 5 default dengan desain bersih dan modern.</p>
                        <a href="/grocery-crud-demo/theme-demo/bootstrap5" class="btn btn-primary w-100">Buka Demo</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-body text-center">
                        <div class="display-6 mb-2 text-info">AL</div>
                        <h5 class="card-title">AdminLTE 4</h5>
                        <p class="card-text text-muted small">Tema dashboard admin dengan sidebar dan mode gelap.</p>
                        <a href="/grocery-crud-demo/theme-demo/adminlte4" class="btn btn-info w-100 text-white">Buka Demo</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-body text-center">
                        <div class="display-6 mb-2 text-success">TW</div>
                        <h5 class="card-title">Tailwind CSS</h5>
                        <p class="card-text text-muted small">Framework CSS utility-first dengan tampilan modern.</p>
                        <a href="/grocery-crud-demo/theme-demo/tailwind" class="btn btn-success w-100">Buka Demo</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-danger">
                    <div class="card-body text-center">
                        <div class="display-6 mb-2 text-danger">MZ</div>
                        <h5 class="card-title">Materialize</h5>
                        <p class="card-text text-muted small">Framework CSS Material Design dengan animasi halus.</p>
                        <a href="/grocery-crud-demo/theme-demo/materialize" class="btn btn-danger w-100">Buka Demo</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Fitur yang Didemonstrasikan</h5>
                        <table class="table table-sm table-bordered mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Fitur</th>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Tag</th>
                                    <th>Varian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>CRUD Dasar</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                <tr><td>Relasi Belongs_to</td><td>✓</td><td>-</td><td>-</td><td>✓</td></tr>
                                <tr><td>Relasi N-to-N</td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                <tr><td>Callback</td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                <tr><td>Validasi</td><td>✓</td><td>✓</td><td>-</td><td>✓</td></tr>
                                <tr><td>Upload File</td><td>✓</td><td>-</td><td>-</td><td>✓</td></tr>
                                <tr><td>Aksi Kustom</td><td>-</td><td>✓</td><td>-</td><td>-</td></tr>
                                <tr><td>Ekspor</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                <tr><td>Pencarian</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                <tr><td>Override Tipe Field</td><td>✓</td><td>-</td><td>✓</td><td>✓</td></tr>
                                <tr><td>Filter Kolom</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                <tr><td>Aksi Batch</td><td>✓</td><td>✓</td><td>-</td><td>✓</td></tr>
                                <tr><td>Urut Berdasarkan Header</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                <tr><td>Penampil Gambar</td><td>✓</td><td>-</td><td>-</td><td>✓</td></tr>
                                <tr><td>Field Repeater</td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                <tr><td>Sub-Grid</td><td>-</td><td>-</td><td>-</td><td>✓</td></tr>
                                <tr><td>Soft Delete</td><td>✓</td><td>-</td><td>-</td><td>✓</td></tr>
                                <tr><td>Tema AdminLTE 4</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                <tr><td>RBAC (Kontrol Akses Berbasis Peran)</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                <tr><td>Activity Log / Audit Trail</td><td>✓</td><td>✓</td><td>-</td><td>-</td></tr>
                                <tr><td><strong>Tampilan Kalender</strong></td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                <tr><td><strong>Grup Field / Tab</strong></td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                <tr><td><strong>Popover Relasi</strong></td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                <tr><td><strong>Penguncian Record</strong></td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                                <tr><td><strong>Ekspor Kolom Tertentu</strong></td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
                                <tr><td><strong>Penampil Activity Log</strong></td><td>-</td><td>✓</td><td>-</td><td>-</td></tr>
                                <tr><td><strong>Dropdown Bertingkat</strong></td><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                                <tr><td><strong>File Manager</strong></td><td>✓</td><td>-</td><td>-</td><td>-</td></tr>
                            </tbody>
                        </table>
                        <p class="text-muted small mt-2 mb-0">
                            <strong>4 Tema Tersedia:</strong>
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
