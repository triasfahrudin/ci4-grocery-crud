<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Grocery CRUD Demo Routes
$routes->group('grocery-crud-demo', static function ($routes): void {
    $routes->get('/',                   'GroceryCrudDemo::index');
    $routes->get('products',            'GroceryCrudDemo::products');
    $routes->get('categories',          'GroceryCrudDemo::categories');
    $routes->get('tags',                'GroceryCrudDemo::tags');
    $routes->post('products',           'GroceryCrudDemo::products');
    $routes->post('categories',         'GroceryCrudDemo::categories');
    $routes->post('tags',               'GroceryCrudDemo::tags');
    // Catch-all for AJAX actions (ajax_list, ajax_restore, ajax_trash_list, etc.)
    $routes->get('products/(:any)',     'GroceryCrudDemo::products/$1');
    $routes->post('products/(:any)',    'GroceryCrudDemo::products/$1');
    $routes->get('categories/(:any)',   'GroceryCrudDemo::categories/$1');
    $routes->post('categories/(:any)',  'GroceryCrudDemo::categories/$1');
    $routes->get('tags/(:any)',         'GroceryCrudDemo::tags/$1');
    $routes->post('tags/(:any)',        'GroceryCrudDemo::tags/$1');
});

// Image CRUD Demo Routes
$routes->group('image-crud-demo', static function ($routes): void {
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
