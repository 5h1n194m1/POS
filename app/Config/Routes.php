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
$routes->get('product/listData', 'Product::listData');
$routes->post('product/save', 'Product::save');
$routes->post('product/update', 'Product::update');
$routes->get('product/delete/(:num)', 'Product::delete/$1');
$routes->get('kasir', 'Kasir::index');
$routes->post('kasir/addToCart', 'Kasir::addToCart');
$routes->get('kasir/remove/(:any)', 'Kasir::remove/$1');
$routes->get('kasir/clear', 'Kasir::clearCart');
$routes->post('kasir/checkout', 'Kasir::checkout');
$routes->get('kasir/nota/(:num)', 'Kasir::nota/$1');
$routes->get('kasir', 'Penjualan::index');
$routes->post('penjualan/save', 'Penjualan::save');
$routes->get('kasir/print_nota/(:num)', 'Penjualan::print_nota/$1');
$routes->get('laporan', 'Laporan::index');

$routes->group('user', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'User::index');
    $routes->get('tambah', 'User::create');
    $routes->post('simpan', 'User::save');
    $routes->get('hapus/(:num)', 'User::delete/$1');

$routes->setAutoRoute(true);
});