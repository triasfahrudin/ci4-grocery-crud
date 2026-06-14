<?php
/**
 * Clone/Duplicate Demo — Halaman overview.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clone/Duplicate Demo — Grocery CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?= view('layouts/navbar', [
        'brandUrl'   => '/clone-demo',
        'brandIcon'  => 'bi-copy',
        'brandText'  => 'Clone/Duplicate Demo',
        'tabs'       => [
            'index'    => ['url' => '/clone-demo',            'icon' => 'bi-info-circle', 'label' => 'Overview'],
            'products' => ['url' => '/clone-demo/products',    'icon' => 'bi-box-seam',    'label' => 'Products CRUD'],
        ],
        'activePage' => 'index',
    ]) ?>

    <div class="container py-3">
        <div class="row mb-4">
            <div class="col">
                <h1 class="display-5 fw-bold">
                    <i class="bi bi-copy me-2 text-info"></i>Clone / Duplicate Demo
                </h1>
                <p class="text-muted">
                    Demonstrasi fitur <strong>Duplikasi Record (Clone)</strong> pada Grocery CRUD.
                    Duplikasi record hanya dengan satu klik dari kolom aksi.
                </p>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-box-seam me-2 text-primary"></i>Products CRUD
                        </h5>
                        <p class="card-text text-muted small">
                            CRUD produk dengan fitur <strong>Clone/Duplicate</strong> diaktifkan.
                            Tombol <span class="badge bg-info"><i class="bi bi-copy"></i> Duplikat</span>
                            muncul di setiap baris di samping tombol Edit.
                        </p>
                        <ul class="small text-muted">
                            <li>Klik tombol <i class="bi bi-copy"></i> untuk menduplikasi record</li>
                            <li>Field <code>name</code> dikecualikan (unique constraint)</li>
                            <li>Field <code>created_at</code>, <code>updated_at</code> dikecualikan</li>
                            <li>Semua field lain disalin ke record baru</li>
                        </ul>
                        <a href="/clone-demo/products" class="btn btn-info text-white">
                            <i class="bi bi-box-seam"></i> Buka Products CRUD
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-code-slash me-2 text-success"></i>Cara Penggunaan
                        </h5>
                        <p class="card-text text-muted small">
                            Cukup panggil <code>$crud->setClone()</code> sebelum <code>render()</code>:
                        </p>
                        <div class="bg-dark text-light p-3 rounded small" style="font-family: monospace;">
                            <span class="text-info">$crud</span> = <span class="text-warning">new</span> GroceryCrud();<br>
                            <span class="text-info">$crud</span>->setTable(<span class="text-success">'products'</span>);<br><br>
                            <span class="text-muted">// Aktifkan duplikasi (semua field disalin)</span><br>
                            <span class="text-info">$crud</span>-><span class="text-primary">setClone</span>();<br><br>
                            <span class="text-muted">// Atau kecualikan field tertentu:</span><br>
                            <span class="text-info">$crud</span>-><span class="text-primary">setClone</span>(<span class="text-warning">true</span>, [<span class="text-success">'slug'</span>, <span class="text-success">'created_at'</span>]);<br><br>
                            <span class="text-warning">echo</span> <span class="text-info">$crud</span>->render();
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Detail Implementasi</h5>
                        <table class="table table-sm table-bordered mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 200px">Komponen</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>GroceryCrud::setClone()</code></td>
                                    <td>Public API untuk mengaktifkan fitur. Parameter: <code>$enabled</code> (bool), <code>$excludeFields</code> (array)</td>
                                </tr>
                                <tr>
                                    <td><code>CrudModel::clone()</code></td>
                                    <td>Mengambil raw row, menghapus PK + excluded fields, insert sebagai record baru</td>
                                </tr>
                                <tr>
                                    <td>Callback</td>
                                    <td>Support <code>beforeClone</code> dan <code>afterClone</code> callback</td>
                                </tr>
                                <tr>
                                    <td>Activity Log</td>
                                    <td>Tercatat sebagai aksi <strong>Created</strong> (insert) dengan data dari record asli</td>
                                </tr>
                                <tr>
                                    <td>Permission</td>
                                    <td>Clone memerlukan izin <strong>add</strong> (sama seperti insert)</td>
                                </tr>
                                <tr>
                                    <td>API Mode</td>
                                    <td>Endpoint: <code>POST /clone-demo/products?gc_action=clone&id=X</code></td>
                                </tr>
                                <tr>
                                    <td>Ikon</td>
                                    <td><code>bi-copy</code> (Bootstrap Icons), warna info/cyan, di samping tombol Edit</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="alert alert-info mt-3 mb-0 py-2 small">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Tips:</strong> Jangan lupa kecualikan field yang memiliki <code>UNIQUE</code> constraint
                            (seperti <code>name</code>, <code>slug</code>, <code>sku</code>) agar clone tidak gagal.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>
</html>
