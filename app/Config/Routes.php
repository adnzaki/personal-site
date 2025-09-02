<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('posts', function (RouteCollection $routes) {
    $routes->get('/', 'Posts::index');
    $routes->get('(:any)/(:any)', 'Posts::index/$1/$2'); // handle category and tag filter
    $routes->get('read/(:any)', 'Posts::read/$1');
});
