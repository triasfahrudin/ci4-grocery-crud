<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use GroceryCrud\GroceryCrud;

/**
 * Import Demo Controller
 *
 * Demonstrates the CSV/Excel Import feature:
 * - Upload CSV or XLSX file
 * - Auto-detect column mapping to form fields
 * - Preview data before importing
 * - Bulk insert records
 *
 * Usage:
 *   1. Run migration: php spark migrate -n App
 *   2. Start server:  php spark serve
 *   3. Open browser:  http://localhost:8080/import-demo
 */
class ImportDemo extends Controller
{
    /**
     * Render navbar sebagai string.
     */
    private function renderNavbar(string $activePage = ''): string
    {
        return view('layouts/navbar', [
            'brandUrl'   => '/import-demo',
            'brandIcon'  => 'bi-upload',
            'brandText'  => 'Import Demo <small class="fw-light">CSV/Excel</small>',
            'tabs'       => [
                'index'    => ['url' => '/import-demo',            'icon' => 'bi-info-circle', 'label' => 'Overview'],
                'contacts' => ['url' => '/import-demo/contacts',   'icon' => 'bi-people',      'label' => 'Contacts'],
            ],
            'activePage' => $activePage,
        ]);
    }

    /**
     * Main index page — shows demo info and link to the CRUD.
     */
    public function index(): string
    {
        return view('import_demo/index');
    }

    /**
     * Contacts CRUD with Import enabled.
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

        $crud->setImportable();
        $crud->setTheme('bootstrap5');

        return $crud->setPageHeader($this->renderNavbar('contacts'))->render();
    }
}
