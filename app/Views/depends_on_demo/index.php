<?php
/**
 * DependsOn Demo — Halaman utama overview.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DependsOn Demo — Grocery CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?= view('layouts/navbar', [
        'brandUrl'    => '/depends-on-demo',
        'brandIcon'   => 'bi-toggle-on',
        'brandText'   => 'DependsOn Demo',
        'tabs'        => [
            'index'    => ['url' => '/depends-on-demo',          'icon' => 'bi-info-circle', 'label' => 'Overview'],
            'products' => ['url' => '/depends-on-demo/products', 'icon' => 'bi-box-seam',    'label' => 'Products'],
        ],
        'activePage'  => 'index',
    ]) ?>

    <div class="container py-3">
        <div class="row mb-4">
            <div class="col">
                <h1 class="display-5 fw-bold">
                    <i class="bi bi-toggle-on text-primary me-2"></i>Kondisi Form Dinamis
                </h1>
                <p class="text-muted lead">
                    Tampil/sembunyi atau aktif/nonaktif kolom berdasarkan nilai kolom lain (<code>dependsOn</code>).
                </p>
                <hr>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-body text-center py-4">
                        <div class="display-3 text-primary mb-3"><i class="bi bi-eye-slash"></i></div>
                        <h5>Aksi: <code>show</code></h5>
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
                        <h5>Aksi: <code>enable</code></h5>
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
                            Kolom <strong>Kategori</strong> → pilih kategori, lalu <strong>Subkategori</strong>
                            otomatis terfilter (pilihan bertingkat/berantai).
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <a href="/depends-on-demo/products" class="btn btn-lg btn-primary px-5">
                <i class="bi bi-box-seam me-2"></i>Buka Demo CRUD
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
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Semua Demo
            </a>
        </div>
    </div>
</body>
</html>
