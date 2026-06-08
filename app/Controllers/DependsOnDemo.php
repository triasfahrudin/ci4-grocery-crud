<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use GroceryCrud\GroceryCrud;

/**
 * DependsOn Demo Controller
 *
 * Demonstrates the Dynamic Form Conditions (dependsOn) feature:
 * - Show/hide fields based on another field's value
 * - Enable/disable fields based on another field's value
 *
 * Usage:
 *   1. Run migration: php spark migrate -n App
 *   2. Start server:  php spark serve
 *   3. Open browser:  http://localhost:8080/depends-on-demo
 */
class DependsOnDemo extends Controller
{
    /**
     * Render navbar sebagai string.
     */
    private function renderNavbar(string $activePage = ''): string
    {
        return view('layouts/navbar', [
            'brandUrl'   => '/depends-on-demo',
            'brandIcon'  => 'bi-toggle-on',
            'brandText'  => 'DependsOn Demo',
            'tabs'       => [
                'index'    => ['url' => '/depends-on-demo',          'icon' => 'bi-info-circle', 'label' => 'Overview'],
                'products' => ['url' => '/depends-on-demo/products', 'icon' => 'bi-box-seam',    'label' => 'Products'],
            ],
            'activePage' => $activePage,
        ]);
    }

    /**
     * Main index page — shows demo info and link to the CRUD.
     */
    public function index(): string
    {
        return view('depends_on_demo/index');
    }

    /**
     * Products CRUD with dependsOn.
     */
    public function products(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('depends_on_demo', 'Product');

        $crud->setColumns(
            'name', 'category_id', 'subcategory_id', 'price',
            'has_discount', 'discount_price',
            'requires_shipping', 'shipping_weight', 'is_active'
        );

        $crud->setFields(
            'name', 'category_id', 'subcategory_id', 'price',
            'has_discount', 'discount_price', 'discount_percent',
            'requires_shipping', 'shipping_weight', 'shipping_notes',
            'is_active'
        );

        $crud->displayAs('name', 'Product Name');
        $crud->displayAs('category_id', 'Category');
        $crud->displayAs('subcategory_id', 'Subcategory');
        $crud->displayAs('price', 'Base Price');
        $crud->displayAs('has_discount', 'Have Discount?');
        $crud->displayAs('discount_price', 'Discount Price');
        $crud->displayAs('discount_percent', 'Discount (%)');
        $crud->displayAs('requires_shipping', 'Requires Shipping?');
        $crud->displayAs('shipping_weight', 'Weight (kg)');
        $crud->displayAs('shipping_notes', 'Shipping Notes');
        $crud->displayAs('is_active', 'Active');

        $crud->setFieldType('has_discount', 'true_false');
        $crud->setFieldType('requires_shipping', 'true_false');
        $crud->setFieldType('is_active', 'true_false');
        $crud->setFieldType('discount_percent', 'integer');

        // Dependent Dropdown
        $crud->setRelation('category_id', 'categories', 'name', "status = 'active'");
        $crud->setDependentRelation('subcategory_id', 'category_id', 'subcategories', 'category_id', 'name');

        $crud->callbackColumn('has_discount', function ($value) {
            return $value == 1
                ? '<span class="badge bg-success">Yes</span>'
                : '<span class="badge bg-secondary">No</span>';
        });
        $crud->callbackColumn('requires_shipping', function ($value) {
            return $value == 1
                ? '<span class="badge bg-success">Yes</span>'
                : '<span class="badge bg-secondary">No</span>';
        });

        // Dynamic Form Conditions (dependsOn)
        $crud->dependsOn('discount_price', 'has_discount', true, 'show');
        $crud->dependsOn('discount_percent', 'has_discount', true, 'show');
        $crud->dependsOn('shipping_weight', 'requires_shipping', true, 'enable');
        $crud->dependsOn('shipping_notes', 'requires_shipping', true, 'enable');

        $crud->required('name');
        $crud->required('price');
        $crud->setRules('price', 'numeric|greater_than[0]');
        $crud->setRules('discount_price', 'numeric|greater_than[0]');
        $crud->setRules('discount_percent', 'integer|greater_than_equal_to[0]|less_than_equal_to[100]');
        $crud->setRules('shipping_weight', 'numeric|greater_than[0]');

        $crud->callbackColumn('price', function ($value, $row) {
            return 'Rp ' . number_format((float) $value, 0, ',', '.');
        });
        $crud->callbackColumn('discount_price', function ($value, $row) {
            if (empty($value)) {
                return '<span class="text-muted">—</span>';
            }
            return 'Rp ' . number_format((float) $value, 0, ',', '.');
        });
        $crud->callbackColumn('is_active', function ($value, $row) {
            return $value == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';
        });

        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);

        $crud->setImportable();
        $crud->setTheme('bootstrap5');

        return $crud->setPageHeader($this->renderNavbar('products'))->render();
    }
}
