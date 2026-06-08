<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use GroceryCrud\GroceryCrud;

/**
 * REST API Demo Controller
 *
 * Demonstrates the headless REST API mode that returns clean JSON
 * (no HTML) for SPA, mobile app, or external integration.
 *
 * Routes (defined in Config/Routes.php):
 *   GET    /api/contacts             → list contacts (paginated JSON)
 *   GET    /api/contacts?id=123      → read single contact
 *   POST   /api/contacts             → create contact (JSON body or form data)
 *   POST   /api/contacts?id=123&gc_action=edit  → update contact
 *   DELETE /api/contacts?id=123      → delete contact
 *
 * All routes map to ApiDemo::contacts() which handles every HTTP method.
 */
class ApiDemo extends Controller
{
    /**
     * Contacts REST API endpoint.
     *
     * Demonstrates setApiMode() with clean JSON responses.
     * Accepts all HTTP methods — action is auto-detected.
     */
    public function contacts($id = null): ResponseInterface
    {
        $crud = new GroceryCrud();

        // Pass URL segment ID as query param so render() picks it up
        if ($id !== null) {
            $_GET['id'] = $id;
        }

        // ======== REST API Mode ========
        $crud->setApiMode();

        // ======== Table ========
        $crud->setTable('contacts', 'Contact');

        // ======== Columns & Fields ========
        $crud->setColumns('name', 'email', 'phone', 'company', 'is_active', 'created_at');
        $crud->setFields('name', 'email', 'phone', 'company', 'is_active');

        // ======== Labels ========
        $crud->displayAs('name', 'Full Name');
        $crud->displayAs('email', 'Email Address');
        $crud->displayAs('phone', 'Phone Number');
        $crud->displayAs('is_active', 'Active');

        // ======== Field types ========
        $crud->setFieldType('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);

        // ======== Validation ========
        $crud->required('name');
        $crud->required('email');
        $crud->unique('email');
        $crud->setRules('email', 'valid_email', 'Email Address');

        // ======== Callbacks ========
        $crud->callbackBeforeInsert(function ($data) {
            $data['created_at'] = date('Y-m-d H:i:s');
            return $data;
        });

        return $crud->render();
    }
}
