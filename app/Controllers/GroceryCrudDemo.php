<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use GroceryCrud\GroceryCrud;

/**
 * Grocery CRUD Demo Controller.
 *
 * Demonstrates various features of the Grocery CRUD library:
 * - Basic CRUD
 * - Relations (belongs_to, n_to_n)
 * - Callbacks
 * - Validation
 * - File upload
 * - Custom actions
 * - Export
 * - Column Filters (text, dropdown, relation)
 * - Batch Actions (delete selected)
 * - Sort by column headers
 * - Image viewer (click thumbnail)
 * - Repeater Fields (Nova-style repeatable groups)
 * - File Manager (upload, create folder, rename, delete, move, copy, search)
 * - AdminLTE 4 Theme
 * - Indonesian language
 * - Soft Delete
 * - Sub-Grid (expandable nested related records)
 *
 * Usage:
 *   1. Run migration: php spark migrate -n App
 *   2. Start server:   php spark serve
 *   3. Open browser:   http://localhost:8080/grocery-crud-demo
 */
class GroceryCrudDemo extends Controller
{
    /**
     * Apply RBAC permissions from the database to a GroceryCrud instance.
     *
     * Reads the current user's role from session, fetches permissions
     * from the `permissions` table, and applies them via setPermission().
     */
    private function applyRbac(\GroceryCrud\GroceryCrud $crud, string $tableName): void
    {
        $role = session()->get('role', 'viewer');

        $permModel = model('App\Models\PermissionModel');
        $allowedActions = $permModel->getAllowedActions($role, $tableName);

        if (!empty($allowedActions)) {
            $crud->setPermissionCallback(function () use ($role) {
                return $role;
            });

            $crud->setPermission($role, $allowedActions);
        }
    }

    /**
     * Render navbar sebagai string (untuk disisipkan via setPageHeader).
     */
    private function renderNavbar(string $activePage = ''): string
    {
        return view('layouts/navbar', [
            'brandUrl'     => '/grocery-crud-demo',
            'brandIcon'    => 'bi-grid',
            'brandText'    => 'Grocery CRUD <small class="fw-light">RBAC Demo</small>',
            'tabs'         => [
                'index'      => ['url' => '/grocery-crud-demo',          'icon' => 'bi-grid',         'label' => 'Overview'],
                'products'   => ['url' => '/grocery-crud-demo/products', 'icon' => 'bi-box-seam',     'label' => 'Products'],
                'categories' => ['url' => '/grocery-crud-demo/categories','icon' => 'bi-bookmark',     'label' => 'Categories'],
                'tags'       => ['url' => '/grocery-crud-demo/tags',     'icon' => 'bi-tags',         'label' => 'Tags'],
                'variants'   => ['url' => '/grocery-crud-demo/variants', 'icon' => 'bi-diagram-2',    'label' => 'Variants'],
                'file-manager' => ['url' => '/grocery-crud-demo/products','icon' => 'bi-folder2-open',  'label' => 'File Manager'],
            ],
            'activePage'   => $activePage,
            'showRole'     => true,
            'showProfile'  => true,
            'showAllDemos' => false,
        ]);
    }

    /**
     * Main index — shows a menu of demo options.
     */
    public function index(): string
    {
        $role = session()->get('role', 'viewer');

        $roleBadge = match ($role) {
            'admin'  => '<span class="badge bg-danger">Admin</span>',
            'editor' => '<span class="badge bg-warning text-dark">Editor</span>',
            default  => '<span class="badge bg-secondary">Viewer</span>',
        };

        $permNotes = match ($role) {
            'admin'  => '<span class="text-danger fw-semibold">Akses penuh:</span> tambah, edit, hapus, lihat, ekspor',
            'editor' => '<span class="text-warning fw-semibold">Akses terbatas:</span> tambah, edit, lihat, ekspor (tanpa hapus)',
            default  => '<span class="text-secondary fw-semibold">Hanya baca:</span> lihat dan ekspor saja',
        };

        return view('grocery_crud_demo/index', [
            'roleBadge' => $roleBadge,
            'permNotes' => $permNotes,
        ]);
    }

    /**
     * Products CRUD — Full featured demo.
     */
    public function products(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('products', 'Products');

        $categoryId = $this->request->getGet('category_id');
        if ($categoryId !== null && $categoryId !== '') {
            $crud->where('category_id', $categoryId);
            $crud->setSubject('Products (filtered by category #' . $categoryId . ')');
        }

        $crud->setColumns('name', 'category_id', 'price', 'stock', 'is_active', 'image', 'created_at');
        $crud->setFields('name', 'category_id', 'description', 'price', 'stock', 'is_active', 'image', 'specs', 'tags');

        $crud->displayAs('name', 'Product Name');
        $crud->displayAs('category_id', 'Category');
        $crud->displayAs('is_active', 'Active');
        $crud->displayAs('stock', 'Stock Quantity');
        $crud->displayAs('price', 'Price (Rp)');
        $crud->displayAs('created_at', 'Created Date');

        $crud->setFieldType('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);
        $crud->setFieldType('description', 'richtext');

        $crud->setRelation('category_id', 'categories', 'name', "status = 'active'", 'name ASC');
        $crud->setRelationPopover('category_id', ['name', 'description', 'status']);

        $crud->setFieldGroup('Basic Info', ['name', 'category_id', 'price', 'stock']);
        $crud->setFieldGroup('Description & Media', ['description', 'image']);
        $crud->setFieldGroup('Tags & Specifications', ['tags', 'specs']);
        $crud->setFieldGroup('Status', ['is_active'], 'section');

        $crud->setInlineEditing(true);
        $crud->setInlineEditColumns(['name', 'price', 'stock', 'is_active', 'category_id']);

        $crud->setRelationNtoN('tags', 'product_tags', 'product_id', 'tag_id', 'tags', 'name');

        $crud->setUpload('image', [
            'allowedTypes' => 'jpg|jpeg|png|gif|webp',
            'maxSize'      => 1024,
            'encryptFileName' => true,
        ]);

        $crud->required('name');
        $crud->required('price');
        $crud->required('stock');
        $crud->unique('name');
        $crud->setRules('price', 'numeric|greater_than[0]', 'Price');
        $crud->setRules('stock', 'integer|greater_than_equal_to[0]', 'Stock');

        $crud->callbackColumn('price', function ($value, $row) {
            return 'Rp ' . number_format((float) $value, 0, ',', '.');
        });

        $crud->callbackColumn('is_active', function ($value, $row) {
            if ($value == 1) {
                return '<span class="badge bg-success">Active</span>';
            }
            return '<span class="badge bg-secondary">Inactive</span>';
        });

        $crud->callbackColumn('image', function ($value, $row) {
            if (empty($value)) {
                return '<span class="text-muted">—</span>';
            }
            return '<img src="/uploads/image/' . $value . '" class="gc-thumb" alt="">';
        });

        $crud->callbackBeforeInsert(function ($data) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            return $data;
        });

        $crud->callbackBeforeUpdate(function ($data) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            return $data;
        });

        $crud->callbackAfterInsert(function ($data) {
            log_message('info', 'New product added: ' . ($data['data']['name'] ?? 'unknown'));
            return true;
        });

        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);
        $crud->setColumnFilterRelation('category_id', 'categories', 'name', 'id', "status = 'active'", 'name ASC');

        $crud->setBatchAction('delete_selected', 'Delete Selected');
        $crud->setBatchAction('restore_selected', 'Restore Selected');

        $crud->setRepeater('specs', 'Product Specs', [
            ['name' => 'key', 'label' => 'Specification', 'type' => 'text', 'rules' => 'required|max_length[100]'],
            ['name' => 'value', 'label' => 'Value', 'type' => 'text', 'rules' => 'required|max_length[255]'],
        ], 'json');

        $crud->setTheme('bootstrap5');
        $crud->setSoftDelete();
        $crud->setCalendarView('created_at', 'name');

        $crud->enableRecordLocking(5);
        $crud->setLockUserCallback(function () {
            return [
                'id'   => (string) session()->get('userId'),
                'name' => session()->get('fullName') ?: session()->get('username'),
            ];
        });

        // ==== File Manager ====
        $crud->setFileManager([
            'basePath' => FCPATH . 'uploads',
            'baseUrl'  => base_url('uploads'),
            'allowedTypes' => 'jpg|jpeg|png|gif|webp|pdf|doc|docx|xls|xlsx|csv|zip|txt|md',
            'maxSize'  => 10240,
        ]);

        $this->applyRbac($crud, 'products');

        return $crud->setPageHeader($this->renderNavbar('products'))->render();
    }

    /**
     * Categories CRUD — Simple demo with enum and custom actions.
     */
    public function categories(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('categories', 'Product Categories');

        $crud->setColumns('name', 'description', 'status', 'created_at');
        $crud->setFields('name', 'description', 'status');

        $crud->displayAs('name', 'Category Name');
        $crud->displayAs('created_at', 'Created');

        $crud->required('name');
        $crud->unique('name');

        $crud->setReadOnly('status');

        $crud->addAction('View Products', 'bi-eye', '/grocery-crud-demo/products?category_id={id}');

        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('status', 'dropdown', ['active' => 'Active', 'inactive' => 'Inactive']);

        $crud->setBatchAction('delete_selected', 'Hapus yang Dipilih');

        $crud->orderBy('name', 'ASC');
        $crud->setLanguage('indonesian');

        $this->applyRbac($crud, 'categories');

        return $crud->setPageHeader($this->renderNavbar('categories'))->render();
    }

    /**
     * Tags CRUD — Minimal demo with custom field type.
     */
    public function tags(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('tags', 'Product Tags');

        $crud->setColumns('name', 'color', 'created_at');
        $crud->setFields('name', 'color');

        $crud->setFieldType('color', 'color');

        $crud->displayAs('color', 'Tag Color');
        $crud->displayAs('name', 'Tag Name');
        $crud->displayAs('created_at', 'Created');

        $crud->required('name');
        $crud->unique('name');

        $crud->addAction('Preview', 'bi-eye', '#', 'btn-preview');

        $crud->callbackColumn('color', function ($value, $row) {
            return '<span class="badge" style="background-color: ' . htmlspecialchars($value) . '; color: #fff;">'
                . htmlspecialchars($value) . '</span>';
        });

        $crud->setColumnFilter('name', 'text');

        $crud->setLanguage('indonesian');
        $crud->orderBy('name', 'ASC');

        $this->applyRbac($crud, 'tags');

        return $crud->setPageHeader($this->renderNavbar('tags'))->render();
    }

    /**
     * Theme Demo — Renders a CRUD with the specified theme.
     */
    public function themeDemo(string $theme): ResponseInterface|string
    {
        $allowedThemes = ['bootstrap5', 'adminlte4', 'tailwind', 'materialize'];
        if (!in_array($theme, $allowedThemes)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $crud = new GroceryCrud();
        $crud->setTable('products', 'Products (' . ucfirst($theme) . ' Theme)');

        $crud->setColumns('name', 'category_id', 'price', 'stock', 'is_active', 'created_at');
        $crud->setFields('name', 'category_id', 'description', 'price', 'stock', 'is_active');

        $crud->displayAs('name', 'Product Name');
        $crud->displayAs('category_id', 'Category');
        $crud->displayAs('is_active', 'Active');
        $crud->displayAs('price', 'Price (Rp)');

        $crud->setFieldType('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);

        $crud->setRelation('category_id', 'categories', 'name', "status = 'active'", 'name ASC');

        $crud->required('name');
        $crud->required('price');
        $crud->unique('name');

        $crud->callbackColumn('price', function ($value, $row) {
            return 'Rp ' . number_format((float) $value, 0, ',', '.');
        });
        $crud->callbackColumn('is_active', function ($value, $row) {
            if ($value == 1) {
                return '<span class="badge bg-success">Active</span>';
            }
            return '<span class="badge bg-secondary">Inactive</span>';
        });

        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);
        $crud->setColumnFilterRelation('category_id', 'categories', 'name', 'id', "status = 'active'", 'name ASC');

        $crud->setBatchAction('delete_selected', 'Delete Selected');
        $crud->setBatchAction('restore_selected', 'Restore Selected');

        $crud->setSubGrid('variants', 'product_variants', 'product_id',
            ['name', 'price', 'stock', 'sku'],
            ['name' => 'Variant', 'price' => 'Price', 'stock' => 'Stock', 'sku' => 'SKU']
        );

        $crud->setSoftDelete();

        $this->applyRbac($crud, 'products');

        $crud->setTheme($theme);

        return $crud->setPageHeader($this->renderNavbar('products'))->render();
    }

    /**
     * Product Variants CRUD — Sub-Grid demo.
     */
    public function variants(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('products', 'Products with Variants');

        $crud->setColumns('name', 'category_id', 'price', 'stock', 'is_active', 'image');
        $crud->setFields('name', 'category_id', 'description', 'price', 'stock', 'is_active', 'image');

        $crud->displayAs('name', 'Product Name');
        $crud->displayAs('category_id', 'Category');
        $crud->displayAs('is_active', 'Active');
        $crud->displayAs('stock', 'Stock Quantity');

        $crud->setFieldType('is_active', 'dropdown', ['1' => 'Active', '0' => 'Inactive']);

        $crud->setRelation('category_id', 'categories', 'name', "status = 'active'", 'name ASC');

        $crud->setUpload('image', [
            'allowedTypes' => 'jpg|jpeg|png|gif|webp',
            'maxSize'      => 1024,
            'encryptFileName' => true,
        ]);

        $crud->required('name');
        $crud->unique('name');

        $crud->setSubGrid('variants', 'product_variants', 'product_id',
            ['name', 'price', 'stock', 'sku'],
            ['name' => 'Variant', 'price' => 'Price', 'stock' => 'Stock', 'sku' => 'SKU']
        );

        $crud->setSubGrid('tags', 'product_tags', 'product_id',
            ['tag_id'],
            ['tag_id' => 'Tag'],
            ['tag_id' => ['tags', 'name', 'tag_id', 'id']]
        );

        $crud->callbackColumn('image', function ($value, $row) {
            if (empty($value)) {
                return '<span class="text-muted">—</span>';
            }
            return '<img src="/uploads/image/' . $value . '" class="gc-thumb" alt="">';
        });

        $crud->callbackColumn('is_active', function ($value, $row) {
            if ($value == '1') {
                return '<span class="badge bg-success">Active</span>';
            }
            return '<span class="badge bg-secondary">Inactive</span>';
        });

        $crud->setTheme('bootstrap5');
        $crud->setSoftDelete();

        $this->applyRbac($crud, 'product_variants');

        return $crud->setPageHeader($this->renderNavbar('variants'))->render();
    }
}
