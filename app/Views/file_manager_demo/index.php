<?php
/**
 * File Manager Demo — Halaman utama overview.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager Demo — Grocery CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?= view('layouts/navbar', [
        'brandUrl'    => '/file-manager-demo',
        'brandIcon'   => 'bi-folder2-open',
        'brandText'   => 'File Manager Demo',
        'tabs'        => [
            'index'    => ['url' => '/file-manager-demo',            'icon' => 'bi-info-circle', 'label' => 'Overview'],
            'contacts' => ['url' => '/file-manager-demo/contacts',   'icon' => 'bi-people',      'label' => 'Contacts'],
            'products' => ['url' => '/file-manager-demo/products',   'icon' => 'bi-box-seam',     'label' => 'Products'],
        ],
        'activePage'  => 'index',
    ]) ?>

    <div class="container py-3">
        <div class="row mb-4">
            <div class="col">
                <h1 class="display-5 fw-bold">
                    <i class="bi bi-folder2-open text-primary me-2"></i>File Manager
                </h1>
                <p class="text-muted lead">
                    Kelola file dan folder langsung dari antarmuka CRUD — upload, buat folder, rename, hapus, pindahkan, salin, dan cari file.
                </p>
                <hr>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-primary text-center py-4">
                    <div class="display-3 text-primary mb-3">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <h5>Upload &amp; Kelola</h5>
                    <p class="text-muted small px-3">
                        Upload file dengan mudah. Buat folder, rename, hapus — semua dari toolbar.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-success text-center py-4">
                    <div class="display-3 text-success mb-3">
                        <i class="bi bi-arrows-move"></i>
                    </div>
                    <h5>Pindah &amp; Salin</h5>
                    <p class="text-muted small px-3">
                        Pindahkan atau salin file antar folder. Cocok untuk organisasi file dinamis.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-info text-center py-4">
                    <div class="display-3 text-info mb-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5>Pencarian Cepat</h5>
                    <p class="text-muted small px-3">
                        Cari file berdasarkan nama di seluruh direktori dengan hasil instan.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <a href="/file-manager-demo/contacts" class="btn btn-lg btn-primary px-5">
                <i class="bi bi-people me-2"></i>Buka CRUD Kontak + File Manager
            </a>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-code-slash me-2"></i>Source Code</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-dark text-light p-3 rounded mb-0" style="font-size:0.8rem;"><code>// Enable File Manager
$crud->setFileManager([
    'basePath'     => FCPATH . 'uploads',
    'baseUrl'      => base_url('uploads'),
    'allowedTypes' => 'jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx|csv|zip|txt|md',
    'maxSize'      => 10240, // 10MB
]);
                        </code></pre>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Konfigurasi</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered mb-0 small">
                            <thead>
                                <tr>
                                    <th>Parameter</th>
                                    <th>Default</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>basePath</code></td>
                                    <td><code>FCPATH . 'uploads/'</code></td>
                                    <td>Path absolut direktori file</td>
                                </tr>
                                <tr>
                                    <td><code>baseUrl</code></td>
                                    <td><code>base_url('uploads')</code></td>
                                    <td>URL publik untuk akses file</td>
                                </tr>
                                <tr>
                                    <td><code>allowedTypes</code></td>
                                    <td><code>'*'</code></td>
                                    <td>Tipe file diizinkan (pipe-separated)</td>
                                </tr>
                                <tr>
                                    <td><code>maxSize</code></td>
                                    <td><code>10240</code></td>
                                    <td>Ukuran maksimum file (KB)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-list-columns-reverse me-2"></i>Fitur Lengkap</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center gap-2">
                                        <i class="bi bi-check2-circle text-success"></i>
                                        Sidebar folder tree navigasi
                                    </li>
                                    <li class="list-group-item d-flex align-items-center gap-2">
                                        <i class="bi bi-check2-circle text-success"></i>
                                        Upload multiple files sekaligus
                                    </li>
                                    <li class="list-group-item d-flex align-items-center gap-2">
                                        <i class="bi bi-check2-circle text-success"></i>
                                        Buat folder baru
                                    </li>
                                    <li class="list-group-item d-flex align-items-center gap-2">
                                        <i class="bi bi-check2-circle text-success"></i>
                                        Rename file/folder
                                    </li>
                                    <li class="list-group-item d-flex align-items-center gap-2">
                                        <i class="bi bi-check2-circle text-success"></i>
                                        Hapus file/folder (dengan konfirmasi)
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center gap-2">
                                        <i class="bi bi-check2-circle text-success"></i>
                                        Move &amp; Copy antar folder
                                    </li>
                                    <li class="list-group-item d-flex align-items-center gap-2">
                                        <i class="bi bi-check2-circle text-success"></i>
                                        Pencarian file berdasarkan nama
                                    </li>
                                    <li class="list-group-item d-flex align-items-center gap-2">
                                        <i class="bi bi-check2-circle text-success"></i>
                                        Breadcrumb navigasi
                                    </li>
                                    <li class="list-group-item d-flex align-items-center gap-2">
                                        <i class="bi bi-check2-circle text-success"></i>
                                        Preview gambar di modal
                                    </li>
                                    <li class="list-group-item d-flex align-items-center gap-2">
                                        <i class="bi bi-check2-circle text-success"></i>
                                        Download file langsung
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
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
