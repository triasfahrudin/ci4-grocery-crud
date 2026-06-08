<?php
/**
 * Image CRUD Demo — Halaman utama overview.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image CRUD Demo — CodeIgniter 4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f8f9fa; }
        .demo-card { transition: transform 0.2s; }
        .demo-card:hover { transform: translateY(-2px); }
    </style>
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
        'activePage'  => 'index',
    ]) ?>

    <div class="container py-3">
        <div class="row mb-4">
            <div class="col">
                <h1 class="display-5 fw-bold">Image CRUD Demo</h1>
                <p class="text-muted">CodeIgniter 4 — Instant Photo Gallery CRUD</p>
                <hr>
                <p class="lead">
                    Image CRUD is an automatic multiple image uploader for CodeIgniter 4,
                    using the same philosophy as Grocery CRUD library. Just a few lines of code
                    and you have a full photo gallery CRUD.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm demo-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-image me-2 text-primary"></i>Simple Gallery
                        </h5>
                        <p class="card-text text-muted small">Basic image gallery with upload, delete, and lightbox.</p>
                        <p class="small text-muted">Table: <code>example_1</code> (id, url)</p>
                        <a href="/image-crud-demo/simple" class="btn btn-primary">Open</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm demo-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-arrows-move me-2 text-success"></i>With Ordering
                        </h5>
                        <p class="card-text text-muted small">Gallery with drag-and-drop reordering.</p>
                        <p class="small text-muted">Table: <code>example_2</code> (id, url, priority)</p>
                        <a href="/image-crud-demo/ordering" class="btn btn-success">Open</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm demo-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-tags me-2 text-warning"></i>With Relation
                        </h5>
                        <p class="card-text text-muted small">Gallery filtered by category relation.</p>
                        <p class="small text-muted">Table: <code>example_3</code> (id, url, category_id, priority)</p>
                        <a href="/image-crud-demo/relation" class="btn btn-warning text-white">Open</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm demo-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-input-cursor me-2 text-info"></i>With Title
                        </h5>
                        <p class="card-text text-muted small">Gallery with editable titles for each image.</p>
                        <p class="small text-muted">Table: <code>example_4</code> (id, title, url, priority)</p>
                        <a href="/image-crud-demo/title" class="btn btn-info text-white">Open</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">How to Use</h5>
                    </div>
                    <div class="card-body">
                        <p>In your controller:</p>
                        <pre class="bg-dark text-light p-3 rounded"><code>use App\Libraries\ImageCrud;

public function gallery()
{
    $crud = new ImageCrud();
    $crud->setTable('photos')
         ->setUrlField('url')
         ->setImagePath(FCPATH . 'uploads')
         ->setTitleField('title')
         ->setOrderingField('priority');

    return $this->buildPage($crud->render());
}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
