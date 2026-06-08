<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use GroceryCrud\GroceryCrud;

/**
 * Activity Log / Audit Trail Demo Controller.
 *
 * Demonstrates the Activity Log feature of Grocery CRUD:
 * - enableActivityLog() with user resolver
 * - setActivityLogFieldLabels() for human-readable field names
 * - setActivityLogExcludeFields() to exclude sensitive data
 * - View logs with action badges, old/new data diff
 *
 * Usage:
 *   1. Run migration: php spark migrate -n App
 *   2. Start server:  php spark serve
 *   3. Open browser:  http://localhost:8080/activity-log-demo
 */
class ActivityLogDemo extends Controller
{
    private function renderNavbar(string $activePage = ''): string
    {
        $fullName = session()->get('fullName') ?: session()->get('username') ?: 'Guest';

        return '
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold" href="/activity-log-demo">
                    <i class="bi bi-journal-text me-2"></i>Activity Log <small class="fw-light">Demo</small>
                </a>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="/activity-log-demo" class="btn btn-sm ' . ($activePage === 'index' ? 'btn-info' : 'btn-outline-light') . '">
                        <i class="bi bi-info-circle me-1"></i>Overview
                    </a>
                    <a href="/activity-log-demo/categories" class="btn btn-sm ' . ($activePage === 'categories' ? 'btn-info' : 'btn-outline-light') . '">
                        <i class="bi bi-bookmark me-1"></i>CRUD Demo
                    </a>
                    <a href="/activity-log-demo/logs" class="btn btn-sm ' . ($activePage === 'logs' ? 'btn-info' : 'btn-outline-light') . '">
                        <i class="bi bi-list-check me-1"></i>View Logs
                    </a>
                    <div class="vr text-light opacity-25 mx-1"></div>
                    <span class="text-light small">
                        <i class="bi bi-person-circle me-1"></i>' . htmlspecialchars($fullName) . '
                    </span>
                    <a href="/grocery-crud-demo" class="btn btn-outline-light btn-sm" title="Back to Main Demo">
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
     * Main overview page — explains Activity Log / Audit Trail feature.
     */
    public function index(): string
    {
        ob_start(); ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Activity Log Demo - Grocery CRUD</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        </head>
        <body>
            <?= $this->renderNavbar('index') ?>
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
        <?php return ob_get_clean();
    }

    /**
     * Categories CRUD with Activity Log enabled.
     */
    public function categories(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('categories', 'Category');

        $crud->setColumns('name', 'description', 'status', 'created_at');
        $crud->setFields('name', 'description', 'status');

        $crud->displayAs('name', 'Category Name');
        $crud->displayAs('description', 'Description');
        $crud->displayAs('created_at', 'Created');

        $crud->required('name');
        $crud->unique('name');

        $crud->setFieldType('status', 'dropdown', [
            'active'   => 'Active',
            'inactive' => 'Inactive',
        ]);

        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('status', 'dropdown', [
            'active'   => 'Active',
            'inactive' => 'Inactive',
        ]);

        $crud->setBatchAction('delete_selected', 'Delete Selected');
        $crud->setBatchAction('restore_selected', 'Restore Selected');

        $crud->orderBy('name', 'ASC');

        // ═══════════════════════════════════════
        // ACTIVITY LOG — the feature being demonstrated
        // ═══════════════════════════════════════
        $crud->enableActivityLog(function () {
            return [
                'id'   => session()->get('userId'),
                'name' => session()->get('fullName') ?: session()->get('username'),
            ];
        });

        $crud->setActivityLogFieldLabels([
            'name'        => 'Category Name',
            'description' => 'Description',
            'status'      => 'Status',
            'created_at'  => 'Created Date',
        ]);

        $crud->setTheme('bootstrap5');

        // ======== Activity Log Viewer UI (built-in) ========
        $crud->enableActivityLogViewer();

        return $crud->setPageHeader($this->renderNavbar('categories'))->render();
    }

    /**
     * View Activity Logs page.
     */
    public function logs(): string
    {
        $request      = service('request');
        $filterTable  = $request->getGet('table');
        $filterAction = $request->getGet('action');
        $search       = $request->getGet('search');
        $page         = max(1, (int) ($request->getGet('page') ?? 1));
        $perPage      = 25;

        $db      = db_connect();
        $builder = $db->table('activity_logs');

        if (!empty($filterTable)) {
            $builder->where('table_name', $filterTable);
        }
        if (!empty($filterAction)) {
            $builder->where('action', $filterAction);
        }
        if (!empty($search)) {
            $builder->groupStart()
                ->like('user_name', $search)
                ->orLike('record_pk', $search)
                ->orLike('table_name', $search)
                ->groupEnd();
        }

        $total      = $builder->countAllResults(false);
        $logs       = $builder->orderBy('created_at', 'DESC')
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()
            ->getResultArray();
        $totalPages = max(1, (int) ceil($total / $perPage));

        $tables = $db->table('activity_logs')
            ->distinct()
            ->select('table_name')
            ->orderBy('table_name', 'ASC')
            ->get()
            ->getResultArray();

        $actionBadges = [
            'insert'  => '<span class="badge bg-success">INSERT</span>',
            'update'  => '<span class="badge bg-primary">UPDATE</span>',
            'delete'  => '<span class="badge bg-danger">DELETE</span>',
            'restore' => '<span class="badge bg-warning text-dark">RESTORE</span>',
            'import'  => '<span class="badge bg-info text-dark">IMPORT</span>',
        ];

        // Build filter dropdown options
        $optTable = '<option value="">All Tables</option>';
        foreach ($tables as $t) {
            $sel = $t['table_name'] === $filterTable ? ' selected' : '';
            $optTable .= '<option value="' . htmlspecialchars($t['table_name']) . '"' . $sel . '>'
                . htmlspecialchars($t['table_name']) . '</option>';
        }

        $actions  = ['insert', 'update', 'delete', 'restore', 'import'];
        $optAction = '<option value="">All Actions</option>';
        foreach ($actions as $a) {
            $sel = $a === $filterAction ? ' selected' : '';
            $optAction .= '<option value="' . $a . '"' . $sel . '>' . ucfirst($a) . '</option>';
        }

        $qParams = [];
        if (!empty($filterTable))  $qParams['table']  = $filterTable;
        if (!empty($filterAction)) $qParams['action']  = $filterAction;
        if (!empty($search))       $qParams['search']  = $search;
        $baseUrl = '/activity-log-demo/logs?' . http_build_query($qParams);
        if (!empty($qParams)) $baseUrl .= '&';

        $searchEsc = htmlspecialchars((string) $search);

        ob_start(); ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Activity Logs - Grocery CRUD</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        </head>
        <body>
            <?= $this->renderNavbar('logs') ?>

            <div class="container py-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold mb-0">
                        <i class="bi bi-list-check text-primary me-2"></i>Activity Logs
                        <span class="badge bg-secondary fs-6 align-middle"><?= $total ?> entries</span>
                    </h3>
                    <div>
                        <a href="/activity-log-demo/logs" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
                    </div>
                </div>

                <form method="get" action="/activity-log-demo/logs" class="row g-2 mb-3">
                    <div class="col-md-3">
                        <select name="table" class="form-select form-select-sm"><?= $optTable ?></select>
                    </div>
                    <div class="col-md-3">
                        <select name="action" class="form-select form-select-sm"><?= $optAction ?></select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search user, table, record..." value="<?= $searchEsc ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:150px;">Timestamp</th>
                                <th style="width:90px;">Action</th>
                                <th style="width:120px;">Table</th>
                                <th style="width:80px;">Record</th>
                                <th>User</th>
                                <th style="width:130px;">IP Address</th>
                                <th style="width:50px;">Diff</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($logs)): ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No activity logs found. Try performing some CRUD operations first.
                            </td></tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log):
                                $badge   = $actionBadges[$log['action']] ?? $log['action'];
                                $oldData = !empty($log['old_data']) ? json_decode($log['old_data'], true) : null;
                                $newData = !empty($log['new_data']) ? json_decode($log['new_data'], true) : null;
                                $oldJson = $oldData ? htmlspecialchars(json_encode($oldData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) : '';
                                $newJson = $newData ? htmlspecialchars(json_encode($newData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) : '';
                                $hasDiff = !empty($oldJson) || !empty($newJson);
                                $did     = 'detail-' . $log['id'];
                            ?>
                            <tr>
                                <td class="text-nowrap small"><?= htmlspecialchars(date('d M Y H:i:s', strtotime($log['created_at']))) ?></td>
                                <td><?= $badge ?></td>
                                <td><code><?= htmlspecialchars($log['table_name']) ?></code></td>
                                <td><code><?= htmlspecialchars((string) $log['record_pk']) ?></code></td>
                                <td><?= htmlspecialchars($log['user_name'] ?? '-') ?></td>
                                <td class="small text-muted"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></td>
                                <td>
                                <?php if ($hasDiff): ?>
                                    <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $did ?>" aria-expanded="false">
                                        <i class="bi bi-file-diff"></i>
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                                </td>
                            </tr>
                            <?php if ($hasDiff): ?>
                            <tr class="collapse" id="<?= $did ?>">
                                <td colspan="8" class="bg-light p-3">
                                    <div class="row g-3">
                                    <?php if (!empty($oldJson)): ?>
                                        <div class="col-md-6">
                                            <h6 class="text-danger mb-2"><i class="bi bi-arrow-left-circle me-1"></i>Old Data</h6>
                                            <pre class="bg-dark text-light p-2 rounded small mb-0" style="max-height:300px;overflow:auto;font-size:0.75rem;"><?= $oldJson ?></pre>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($newJson)): ?>
                                        <div class="col-md-6">
                                            <h6 class="text-success mb-2"><i class="bi bi-arrow-right-circle me-1"></i>New Data</h6>
                                            <pre class="bg-dark text-light p-2 rounded small mb-0" style="max-height:300px;overflow:auto;font-size:0.75rem;"><?= $newJson ?></pre>
                                        </div>
                                    <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                <nav><ul class="pagination pagination-sm justify-content-center">
                    <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
                        <a class="page-link" href="<?= $baseUrl ?>page=<?= $page - 1 ?>">&laquo;</a>
                    </li>
                    <?php
                    $startPage = max(1, $page - 2);
                    $endPage   = min($totalPages, $page + 2);
                    if ($startPage > 1): ?>
                        <li class="page-item"><a class="page-link" href="<?= $baseUrl ?>page=1">1</a></li>
                        <?php if ($startPage > 2): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                    <?php endif; ?>
                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item<?= $i === $page ? ' active' : '' ?>">
                            <a class="page-link" href="<?= $baseUrl ?>page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                        <li class="page-item"><a class="page-link" href="<?= $baseUrl ?>page=<?= $totalPages ?>"><?= $totalPages ?></a></li>
                    <?php endif; ?>
                    <li class="page-item<?= $page >= $totalPages ? ' disabled' : '' ?>">
                        <a class="page-link" href="<?= $baseUrl ?>page=<?= $page + 1 ?>">&raquo;</a>
                    </li>
                </ul></nav>
                <?php endif; ?>

                <div class="text-muted small mt-2">
                    Showing page <?= $page ?> of <?= $totalPages ?> (<?= $total ?> total records)
                </div>

                <div class="mt-3">
                    <a href="/activity-log-demo" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Overview
                    </a>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php return ob_get_clean();
    }
}
