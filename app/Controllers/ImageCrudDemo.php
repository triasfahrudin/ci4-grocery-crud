<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libraries\ImageCrud;
use CodeIgniter\Controller;

/**
 * Image CRUD Demo Controller
 *
 * Demonstrates usage of the Image CRUD library for CodeIgniter 4.
 *
 * Usage:
 *   1. Run migration to create sample tables
 *   2. Start server: php spark serve
 *   3. Visit: http://localhost:8080/image-crud-demo
 */
class ImageCrudDemo extends Controller
{
    /**
     * Render navbar sebagai string.
     */
    private function renderNavbar(string $activePage = ''): string
    {
        return view('layouts/navbar', [
            'brandUrl'   => '/image-crud-demo',
            'brandIcon'  => 'bi-images',
            'brandText'  => 'Image CRUD Demo',
            'tabs'       => [
                'index'    => ['url' => '/image-crud-demo',          'icon' => 'bi-info-circle', 'label' => 'Overview'],
                'simple'   => ['url' => '/image-crud-demo/simple',   'icon' => 'bi-image',       'label' => 'Simple'],
                'ordering' => ['url' => '/image-crud-demo/ordering', 'icon' => 'bi-arrows-move', 'label' => 'Ordering'],
                'relation' => ['url' => '/image-crud-demo/relation', 'icon' => 'bi-tags',        'label' => 'Relation'],
                'title'    => ['url' => '/image-crud-demo/title',    'icon' => 'bi-input-cursor','label' => 'Title'],
            ],
            'activePage' => $activePage,
        ]);
    }

    /**
     * Build a full HTML page wrapping Image CRUD output.
     */
    private function buildPage(object $crudOutput, string $activePage = ''): string
    {
        return view('image_crud_demo/gallery', [
            'crudOutput' => $crudOutput,
            'activePage' => $activePage,
        ]);
    }

    /**
     * Main index page — menu of demo options.
     */
    public function index(): string
    {
        return view('image_crud_demo/index');
    }

    /**
     * Example 1: Simple gallery (id, url).
     */
    public function simple(...$params): string
    {
        $crud = new ImageCrud();
        $crud->setTable('example_1')
             ->setUrlField('url')
             ->setImagePath(FCPATH . 'uploads');

        $output = $crud->render();
        if (is_string($output)) {
            return $output;
        }

        return $this->buildPage($output, 'simple');
    }

    /**
     * Example 2: With ordering (id, url, priority).
     */
    public function ordering(...$params): string
    {
        $crud = new ImageCrud();
        $crud->setTable('example_2')
             ->setUrlField('url')
             ->setOrderingField('priority')
             ->setImagePath(FCPATH . 'uploads');

        $output = $crud->render();
        if (is_string($output)) {
            return $output;
        }

        return $this->buildPage($output, 'ordering');
    }

    /**
     * Example 3: With relation (id, url, category_id, priority).
     */
    public function relation(...$params): string
    {
        $crud = new ImageCrud();
        $crud->setTable('example_3')
             ->setUrlField('url')
             ->setRelationField('category_id')
             ->setOrderingField('priority')
             ->setImagePath(FCPATH . 'uploads');

        $output = $crud->render();
        if (is_string($output)) {
            return $output;
        }

        return $this->buildPage($output, 'relation');
    }

    /**
     * Example 4: With title and ordering (id, title, url, priority).
     */
    public function title(...$params): string
    {
        $crud = new ImageCrud();
        $crud->setTable('example_4')
             ->setUrlField('url')
             ->setTitleField('title')
             ->setOrderingField('priority')
             ->setImagePath(FCPATH . 'uploads');

        $output = $crud->render();
        if (is_string($output)) {
            return $output;
        }

        return $this->buildPage($output, 'title');
    }
}
