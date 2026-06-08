<?php
/**
 * Activity Log Demo — Halaman utama overview.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log Demo — Grocery CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?= view('layouts/navbar', [
        'brandUrl'    => '/activity-log-demo',
        'brandIcon'   => 'bi-journal-text',
        'brandText'   => 'Activity Log <small class="fw-light">Demo</small>',
        'tabs'        => [
            'index'      => ['url' => '/activity-log-demo',          'icon' => 'bi-info-circle', 'label' => 'Overview'],
            'categories' => ['url' => '/activity-log-demo/categories','icon' => 'bi-bookmark',    'label' => 'CRUD Demo'],
            'logs'       => ['url' => '/activity-log-demo/logs',     'icon' => 'bi-list-check',  'label' => 'View Logs'],
        ],
        'activePage'  => 'index',
    ]) ?>

    <div class="container py-3">
        <div class="row mb-4">
            <div class="col">
                <h1 class="display-5 fw-bold">
                    <i class="bi bi-journal-text text-primary me-2"></i>Activity Log / Audit Trail
                </h1>
                <p class="text-muted lead">
                    Catat otomatis setiap operasi CRUD — siapa, apa, kapan, data sebelum &amp; sesudah.
                </p>
                <hr>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-body text-center py-4">
                        <div class="display-3 text-primary mb-3"><i class="bi bi-pencil-square"></i></div>
                        <h5>Auto Recording</h5>
                        <p class="text-muted small">
                            Setiap Insert, Update, Delete, Restore, dan Import otomatis tercatat
                            lengkap dengan data sebelum dan sesudah.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-body text-center py-4">
                        <div class="display-3 text-success mb-3"><i class="bi bi-person-badge"></i></div>
                        <h5>User Tracking</h5>
                        <p class="text-muted small">
                            Ketahui siapa yang melakukan perubahan — user ID, nama, IP address,
                            dan user agent tercatat otomatis.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-body text-center py-4">
                        <div class="display-3 text-info mb-3"><i class="bi bi-file-diff"></i></div>
                        <h5>Before / After Diff</h5>
                        <p class="text-muted small">
                            Data sebelum dan sesudah perubahan disimpan sebagai JSON,
                            bisa dibandingkan untuk audit yang akurat.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-code-slash me-2"></i>Source Code</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-dark text-light p-3 rounded mb-0" style="font-size:0.85rem;"><code>// Enable with user resolver
$crud->enableActivityLog(function () {
    return [
        'id'   => session()->get('userId'),
        'name' => session()->get('fullName'),
    ];
});

// Optional: custom field labels
$crud->setActivityLogFieldLabels([
    'name'        => 'Product Name',
    'category_id' => 'Category',
    'price'       => 'Price',
]);

// Optional: exclude sensitive fields
$crud->setActivityLogExcludeFields([
    'password',
    'token',
]);</code></pre>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-table me-2"></i>Log Features</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr><th>Feature</th><th>Description</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Insert</td><td>Mencatat data baru yang ditambahkan</td></tr>
                                <tr><td>Update</td><td>Mencatat data sebelum &amp; sesudah perubahan</td></tr>
                                <tr><td>Delete</td><td>Mencatat data yang dihapus</td></tr>
                                <tr><td>Restore</td><td>Mencatat restore dari soft delete</td></tr>
                                <tr><td>Batch</td><td>Mencatat batch delete/restore per record</td></tr>
                                <tr><td>Import</td><td>Mencatat data hasil import CSV/Excel</td></tr>
                                <tr><td>Filter</td><td>Filter by table, action, user, date range</td></tr>
                                <tr><td>Purge</td><td>Hapus otomatis log lebih dari N hari</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <a href="/activity-log-demo/categories" class="btn btn-lg btn-primary px-5 me-2">
                <i class="bi bi-play-circle me-2"></i>Try the Demo
            </a>
            <a href="/activity-log-demo/logs" class="btn btn-lg btn-outline-secondary px-5">
                <i class="bi bi-list-check me-2"></i>View Logs
            </a>
        </div>
    </div>
</body>
</html>
