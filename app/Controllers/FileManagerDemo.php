<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use GroceryCrud\GroceryCrud;

/**
 * File Manager Demo Controller
 *
 * Demonstrates the File Manager feature:
 * - Upload files via drag-and-drop or file picker
 * - Create folders
 * - Rename, delete, move, copy files/folders
 * - Browse folder tree
 * - Search files
 *
 * Usage:
 *   1. Run migration: php spark migrate -n App
 *   2. Start server:  php spark serve
 *   3. Open browser:  http://localhost:8080/file-manager-demo
 */
class FileManagerDemo extends Controller
{
    /**
     * Render navbar sebagai string.
     */
    private function renderNavbar(string $activePage = ''): string
    {
        return view('layouts/navbar', [
            'brandUrl'   => '/file-manager-demo',
            'brandIcon'  => 'bi-folder2-open',
            'brandText'  => 'File Manager Demo',
            'tabs'       => [
                'index'    => ['url' => '/file-manager-demo',            'icon' => 'bi-info-circle', 'label' => 'Overview'],
                'contacts' => ['url' => '/file-manager-demo/contacts',   'icon' => 'bi-people',      'label' => 'Contacts'],
                'products' => ['url' => '/file-manager-demo/products',   'icon' => 'bi-box-seam',     'label' => 'Products'],
            ],
            'activePage' => $activePage,
        ]);
    }

    /**
     * Main index page — shows demo info and link to the CRUD.
     */
    public function index(): string
    {
        return view('file_manager_demo/index');
    }

    /**
     * Contacts CRUD with File Manager enabled.
     */
    public function contacts(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('contacts', 'Contact');

        $crud->setColumns('name', 'email', 'phone', 'company', 'is_active', 'created_at');
        $crud->setFields('name', 'email', 'phone', 'company', 'is_active');

        $crud->displayAs('name', 'Full Name');
        $crud->displayAs('email', 'Email Address');
        $crud->displayAs('phone', 'Phone Number');
        $crud->displayAs('company', 'Company');
        $crud->displayAs('is_active', 'Active');

        $crud->setFieldType('is_active', 'true_false');

        $crud->required('name');
        $crud->required('email');
        $crud->unique('email');
        $crud->setRules('email', 'valid_email');

        $crud->callbackColumn('is_active', function ($value) {
            return $value == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';
        });

        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('email', 'text');
        $crud->setColumnFilter('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);

        // ==== File Manager ====
        $crud->setFileManager([
            'basePath' => FCPATH . 'uploads',
            'baseUrl'  => base_url('uploads'),
            'allowedTypes' => 'jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx|csv|zip|txt|md',
            'maxSize'  => 10240, // 10MB
        ]);

        $crud->setTheme('bootstrap5');

        return $crud->setPageHeader($this->renderNavbar('contacts'))->render();
    }

    /**
     * Products CRUD with File Manager + Export + Import.
     */
    public function products(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('products', 'Product');

        $crud->setColumns('name', 'category_id', 'price', 'stock', 'is_active', 'created_at');
        $crud->setFields('name', 'category_id', 'price', 'stock', 'is_active', 'description');

        $crud->displayAs('name', 'Product Name');
        $crud->displayAs('category_id', 'Category');
        $crud->displayAs('price', 'Price (Rp)');
        $crud->displayAs('stock', 'Stock');
        $crud->displayAs('is_active', 'Active');
        $crud->displayAs('description', 'Description');

        $crud->setRelation('category_id', 'categories', 'name');

        $crud->setFieldType('price', 'numeric');
        $crud->setFieldType('stock', 'numeric');
        $crud->setFieldType('is_active', 'true_false');

        $crud->required('name');
        $crud->required('price');
        $crud->setRules('price', 'numeric');

        $crud->callbackColumn('price', function ($value) {
            return 'Rp ' . number_format((float) $value, 0, ',', '.');
        });

        $crud->callbackColumn('is_active', function ($value) {
            return $value == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';
        });

        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('category_id', 'dropdown');
        $crud->setColumnFilterRelation('category_id', 'categories', 'name');
        $crud->setColumnFilter('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);

        // ==== File Manager ====
        $crud->setFileManager([
            'basePath' => FCPATH . 'uploads',
            'baseUrl'  => base_url('uploads'),
            'allowedTypes' => 'jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx|csv|zip|txt|md',
            'maxSize'  => 10240,
        ]);

        $crud->setTheme('bootstrap5');

        return $crud->setPageHeader($this->renderNavbar('products'))->render();
    }
}
