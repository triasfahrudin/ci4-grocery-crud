<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Auth Routes (no filter)
$routes->get('auth/login',  'Auth::login');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('auth/profile', 'Auth::profile', ['filter' => 'auth']);

// Home
$routes->get('/', 'Home::index');

// Grocery CRUD Demo Routes (protected by auth filter)
$routes->group('grocery-crud-demo', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('/',                   'GroceryCrudDemo::index');
    $routes->get('products',            'GroceryCrudDemo::products');
    $routes->get('categories',          'GroceryCrudDemo::categories');
    $routes->get('tags',                'GroceryCrudDemo::tags');
    $routes->get('variants',            'GroceryCrudDemo::variants');
    $routes->post('products',           'GroceryCrudDemo::products');
    $routes->post('categories',         'GroceryCrudDemo::categories');
    $routes->post('tags',               'GroceryCrudDemo::tags');
    $routes->post('variants',           'GroceryCrudDemo::variants');
    // Theme Demo Routes
    $routes->get('theme-demo/(:segment)',           'GroceryCrudDemo::themeDemo/$1');
    $routes->post('theme-demo/(:segment)',          'GroceryCrudDemo::themeDemo/$1');
    $routes->get('theme-demo/(:segment)/(:any)',    'GroceryCrudDemo::themeDemo/$1/$2');
    $routes->post('theme-demo/(:segment)/(:any)',   'GroceryCrudDemo::themeDemo/$1/$2');

    // Catch-all for AJAX actions (ajax_list, ajax_restore, ajax_trash_list, ajax_sub_grid, etc.)
    $routes->get('products/(:any)',     'GroceryCrudDemo::products/$1');
    $routes->post('products/(:any)',    'GroceryCrudDemo::products/$1');
    $routes->get('categories/(:any)',   'GroceryCrudDemo::categories/$1');
    $routes->post('categories/(:any)',  'GroceryCrudDemo::categories/$1');
    $routes->get('tags/(:any)',         'GroceryCrudDemo::tags/$1');
    $routes->post('tags/(:any)',        'GroceryCrudDemo::tags/$1');
    $routes->get('variants/(:any)',     'GroceryCrudDemo::variants/$1');
    $routes->post('variants/(:any)',    'GroceryCrudDemo::variants/$1');
});

// DependsOn Demo Routes (protected by auth filter)
$routes->group('depends-on-demo', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('/',                   'DependsOnDemo::index');
    $routes->get('products',            'DependsOnDemo::products');
    $routes->post('products',           'DependsOnDemo::products');
    // Catch-all for AJAX actions
    $routes->get('products/(:any)',     'DependsOnDemo::products/$1');
    $routes->post('products/(:any)',    'DependsOnDemo::products/$1');
});

// Import Demo Routes (protected by auth filter)
$routes->group('import-demo', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('/',                   'ImportDemo::index');
    $routes->get('contacts',            'ImportDemo::contacts');
    $routes->post('contacts',           'ImportDemo::contacts');
    // Catch-all for AJAX actions
    $routes->get('contacts/(:any)',     'ImportDemo::contacts/$1');
    $routes->post('contacts/(:any)',    'ImportDemo::contacts/$1');
});

// Image CRUD Demo Routes (protected by auth filter)
$routes->group('image-crud-demo', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('/',                       'ImageCrudDemo::index');
    $routes->get('simple',                  'ImageCrudDemo::simple');
    $routes->get('ordering',                'ImageCrudDemo::ordering');
    $routes->get('relation',                'ImageCrudDemo::relation');
    $routes->get('title',                   'ImageCrudDemo::title');
    $routes->post('simple',                 'ImageCrudDemo::simple');
    $routes->post('ordering',               'ImageCrudDemo::ordering');
    $routes->post('relation',               'ImageCrudDemo::relation');
    $routes->post('title',                  'ImageCrudDemo::title');
    // Catch-all for any additional segments (ajax_list, upload_file, delete_file, etc.)
    $routes->get('simple/(:any)',           'ImageCrudDemo::simple/$1');
    $routes->post('simple/(:any)',          'ImageCrudDemo::simple/$1');
    $routes->get('ordering/(:any)',         'ImageCrudDemo::ordering/$1');
    $routes->post('ordering/(:any)',        'ImageCrudDemo::ordering/$1');
    $routes->get('relation/(:any)',         'ImageCrudDemo::relation/$1');
    $routes->post('relation/(:any)',        'ImageCrudDemo::relation/$1');
    $routes->get('title/(:any)',            'ImageCrudDemo::title/$1');
    $routes->post('title/(:any)',           'ImageCrudDemo::title/$1');
});
