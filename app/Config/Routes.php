<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::processLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::processRegister');
$routes->get('logout', 'Auth::logout');
$routes->get('product', 'Product::index');
$routes->post('product/save', 'Product::save');
$routes->get('product/delete/(:num)', 'Product::delete/$1');
$routes->get('kasir', 'Kasir::index');
$routes->post('kasir/add', 'Kasir::addToCart');
$routes->get('kasir/clear', 'Kasir::clearCart');
$routes->post('kasir/checkout', 'Kasir::checkout');