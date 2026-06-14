<?php

/**
 * Navbar partial — data-driven, reusable across all demo controllers.
 *
 * Data yang diharapkan:
 * - $brandUrl    (string) URL brand
 * - $brandIcon   (string) class icon Bootstrap (bi-xxx)
 * - $brandText   (string) teks brand
 * - $tabs        (array)  daftar tab: ['key' => ['url' => '...', 'icon' => '...', 'label' => '...']]
 * - $activePage  (string) key tab yang aktif
 * - $showRole    (bool)   tampilkan badge role? (default false)
 * - $showProfile (bool)   tampilkan tombol profile? (default false)
 * - $showAllDemos (bool)  tampilkan tombol "All Demos"? (default true)
 *
 * Semua parameter opsional kecuali $brandUrl, $brandIcon, $brandText.
 */
$tabs        = $tabs ?? [];
$activePage  = $activePage ?? '';
$showRole    = $showRole ?? false;
$showProfile = $showProfile ?? false;
$showAllDemos = $showAllDemos ?? true;

$fullName = session()->get('fullName') ?: session()->get('username') ?: 'Guest';
$role     = session()->get('role', 'viewer');

$badgeClass = match ($role) {
    'admin'  => 'bg-danger',
    'editor' => 'bg-warning text-dark',
    default  => 'bg-secondary',
};
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= $brandUrl ?>">
            <i class="bi <?= $brandIcon ?> me-2"></i><?= $brandText ?>
        </a>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <?php foreach ($tabs as $key => $tab): ?>
                <a href="<?= $tab['url'] ?>"
                    class="btn btn-sm <?= $key === $activePage ? 'btn-info' : 'btn-outline-light' ?>">
                    <i class="bi <?= $tab['icon'] ?> me-1"></i><?= $tab['label'] ?>
                </a>
            <?php endforeach; ?>
            <div class="vr text-light opacity-25 mx-1"></div>
            <span class="text-light small">
                <i class="bi bi-person-circle me-1"></i><?= esc($fullName) ?>
                <?php if ($showRole): ?>
                    <span class="badge <?= $badgeClass ?> ms-1"><?= ucfirst($role) ?></span>
                <?php endif; ?>
            </span>
            <?php if ($showProfile): ?>
                <a href="/auth/profile" class="btn btn-outline-light btn-sm" title="Profil">
                    <i class="bi bi-person-vcard"></i>
                </a>
            <?php endif; ?>
            <?php if ($showAllDemos): ?>
                <a href="/grocery-crud-demo" class="btn btn-outline-light btn-sm" title="Semua Demo">
                    <i class="bi bi-grid me-1"></i>Semua Demo
                </a>
            <?php endif; ?>
            <a href="/auth/logout" class="btn btn-outline-light btn-sm">
                <i class="bi bi-box-arrow-right me-1"></i>
            </a>
        </div>
    </div>
</nav>