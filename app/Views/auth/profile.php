<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Grocery CRUD Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/grocery-crud-demo">
                <i class="bi bi-grid me-2"></i>Grocery CRUD RBAC Demo
            </a>
            <div class="d-flex align-items-center gap-2">
                <span class="text-light small me-2">
                    <i class="bi bi-person-circle me-1"></i>
                    <?= esc(session()->get('fullName') ?: session()->get('username')) ?>
                    <span class="badge bg-<?= session()->get('role') === 'admin' ? 'danger' : (session()->get('role') === 'editor' ? 'warning text-dark' : 'secondary') ?> ms-1">
                        <?= ucfirst(session()->get('role')) ?>
                    </span>
                </span>
                <a href="/auth/logout" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-person-vcard me-2"></i>User Profile</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered mb-0">
                            <tr>
                                <th class="bg-light" style="width:140px">Username</th>
                                <td><?= esc(session()->get('username')) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Full Name</th>
                                <td><?= esc(session()->get('fullName') ?: '-') ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Email</th>
                                <td><?= esc(session()->get('email')) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Role</th>
                                <td>
                                    <span class="badge bg-<?= session()->get('role') === 'admin' ? 'danger' : (session()->get('role') === 'editor' ? 'warning text-dark' : 'secondary') ?>">
                                        <?= ucfirst(session()->get('role')) ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer bg-white text-end">
                        <a href="/grocery-crud-demo" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Demo
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-shield-check me-2"></i>Permission Info</h5>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-0">
                            Your role is <strong><?= ucfirst(session()->get('role')) ?></strong>.
                            <?php if (session()->get('role') === 'admin'): ?>
                                You have full access: add, edit, delete, view, and export.
                            <?php elseif (session()->get('role') === 'editor'): ?>
                                You can add, edit, view, and export, but cannot delete records.
                            <?php else: ?>
                                You can only view and export records. Add, edit, and delete are disabled.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
