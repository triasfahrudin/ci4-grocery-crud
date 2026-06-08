<?php
/**
 * Image CRUD — Halaman wrapping untuk output ImageCrud library.
 *
 * Data dari controller:
 * - $crudOutput  (object) object hasil render ImageCrud (->output, ->cssFiles, ->jsFiles)
 * - $activePage  (string) tab aktif di navbar
 */
$cssLinks = '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">' . "\n";
$cssLinks .= '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">' . "\n";
foreach ($crudOutput->cssFiles as $cssFile) {
    $cssLinks .= '<link rel="stylesheet" href="' . $cssFile . '">' . "\n";
}

$jsLinks  = '<script src="https://code.jquery.com/jquery-1.8.2.min.js"></script>' . "\n";
$jsLinks .= '<script src="https://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>' . "\n";
$jsLinks .= '<script src="' . base_url('assets/image_crud/js/jquery.colorbox-min.js') . '"></script>' . "\n";
$jsLinks .= '<script src="' . base_url('assets/image_crud/js/jquery.fineuploader-3.5.0.min.js') . '"></script>' . "\n";
foreach ($crudOutput->jsFiles as $jsFile) {
    $jsLinks .= '<script src="' . $jsFile . '"></script>' . "\n";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image CRUD Gallery</title>
    <?= $cssLinks ?>
    <?= $jsLinks ?>
</head>
<body>
    <?= view('layouts/navbar', [
        'brandUrl'    => '/image-crud-demo',
        'brandIcon'   => 'bi-images',
        'brandText'   => 'Image CRUD Demo',
        'tabs'        => [
            'index'    => ['url' => '/image-crud-demo',          'icon' => 'bi-info-circle', 'label' => 'Overview'],
            'simple'   => ['url' => '/image-crud-demo/simple',   'icon' => 'bi-image',       'label' => 'Simple'],
            'ordering' => ['url' => '/image-crud-demo/ordering', 'icon' => 'bi-arrows-move', 'label' => 'Ordering'],
            'relation' => ['url' => '/image-crud-demo/relation', 'icon' => 'bi-tags',        'label' => 'Relation'],
            'title'    => ['url' => '/image-crud-demo/title',    'icon' => 'bi-input-cursor','label' => 'Title'],
        ],
        'activePage'  => $activePage,
    ]) ?>
    <div style="padding:20px;">
        <?= $crudOutput->output ?>
    </div>
</body>
</html>
