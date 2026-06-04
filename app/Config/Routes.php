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
});
