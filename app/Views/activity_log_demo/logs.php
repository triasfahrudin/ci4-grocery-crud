<?php
/**
 * Activity Log Demo — Halaman daftar log.
 *
 * Data dari controller:
 * - $logs        (array)  daftar record log
 * - $total       (int)    total entries
 * - $totalPages  (int)    total halaman
 * - $page        (int)    halaman saat ini
 * - $baseUrl     (string) base URL untuk pagination
 * - $optTable    (string) HTML <option> untuk filter tabel
 * - $optAction   (string) HTML <option> untuk filter aksi
 * - $searchEsc   (string) nilai search yang sudah di-escape
 * - $filterTable (string) nilai filter tabel
 * - $filterAction(string) nilai filter aksi
 * - $actionBadges(array)  mapping aksi → HTML badge
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs — Grocery CRUD</title>
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
        'activePage'  => 'logs',
    ]) ?>

    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">
                <i class="bi bi-list-check text-primary me-2"></i>Log Aktivitas
                <span class="badge bg-secondary fs-6 align-middle"><?= $total ?> entri</span>
            </h3>
            <div>
                <a href="/activity-log-demo/logs" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i>Atur Ulang
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
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari pengguna, tabel, record..." value="<?= $searchEsc ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i>Saring
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width:150px;">Waktu</th>
                        <th style="width:90px;">Aksi</th>
                        <th style="width:120px;">Tabel</th>
                        <th style="width:80px;">Record</th>
                        <th>Pengguna</th>
                        <th style="width:130px;">Alamat IP</th>
                        <th style="width:50px;">Diff</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($logs)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada log aktivitas. Coba lakukan operasi CRUD terlebih dahulu.
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($logs as $log):
                        $badge   = $actionBadges[$log['action']] ?? $log['action'];
                        $oldData = !empty($log['old_data']) ? json_decode($log['old_data'], true) : null;
                        $newData = !empty($log['new_data']) ? json_decode($log['new_data'], true) : null;
                        $oldJson = $oldData ? esc(json_encode($oldData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) : '';
                        $newJson = $newData ? esc(json_encode($newData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) : '';
                        $hasDiff = !empty($oldJson) || !empty($newJson);
                        $did     = 'detail-' . $log['id'];
                    ?>
                    <tr>
                        <td class="text-nowrap small"><?= esc(date('d M Y H:i:s', strtotime($log['created_at']))) ?></td>
                        <td><?= $badge ?></td>
                        <td><code><?= esc($log['table_name']) ?></code></td>
                        <td><code><?= esc((string) $log['record_pk']) ?></code></td>
                        <td><?= esc($log['user_name'] ?? '-') ?></td>
                        <td class="small text-muted"><?= esc($log['ip_address'] ?? '-') ?></td>
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
                                    <h6 class="text-danger mb-2"><i class="bi bi-arrow-left-circle me-1"></i>Data Lama</h6>
                                    <pre class="bg-dark text-light p-2 rounded small mb-0" style="max-height:300px;overflow:auto;font-size:0.75rem;"><?= $oldJson ?></pre>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($newJson)): ?>
                                <div class="col-md-6">
                                    <h6 class="text-success mb-2"><i class="bi bi-arrow-right-circle me-1"></i>Data Baru</h6>
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
            Menampilkan halaman <?= $page ?> dari <?= $totalPages ?> (<?= $total ?> total record)
        </div>

        <div class="mt-3">
            <a href="/activity-log-demo" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
