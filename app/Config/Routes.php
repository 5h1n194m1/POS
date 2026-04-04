<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =========================
// RUTE PUBLIK
// =========================
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::processLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::processRegister');

// =========================
// RUTE TERPROTEKSI
// =========================
$routes->group('', ['filter' => 'auth'], function($routes) {

    $routes->get('logout', 'Auth::logout');

    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('dashboard/getNavbarData', 'Dashboard::getNavbarData');

    // Profil
    $routes->get('profile', 'Profile::index');
    $routes->post('profile/update', 'Profile::update');
    $routes->post('profile/change-password', 'Profile::changePassword');
    $routes->post('profile/upload-avatar', 'Profile::uploadAvatar');
    $routes->post('profile/delete-avatar', 'Profile::deleteAvatar');

    // Kasir
    $routes->group('kasir', function($routes) {
        $routes->get('/', 'Kasir::index');
        $routes->post('addToCart', 'Kasir::addToCart');
        $routes->get('remove/(:num)', 'Kasir::remove/$1');
        $routes->get('clearCart', 'Kasir::clearCart');
        $routes->post('checkout', 'Kasir::checkout');
        $routes->get('print_nota/(:num)', 'Penjualan::print_nota/$1');
    });

    // Penjualan
    $routes->post('penjualan/save', 'Penjualan::save');
    $routes->get('penjualan/print_nota/(:num)', 'Penjualan::print_nota/$1');

    // =========================
    // KHUSUS ADMIN
    // =========================
    $routes->group('', ['filter' => 'admin'], function($routes) {

        // Produk
        $routes->group('product', function($routes) {
            $routes->get('/', 'Product::index');
            $routes->get('listData', 'Product::listData');
            $routes->post('save', 'Product::save');
            $routes->post('update', 'Product::update');
            $routes->get('delete/(:num)', 'Product::delete/$1');
        });

        // Kategori
        $routes->group('kategori', function($routes) {
            $routes->get('/', 'Category::index');
            $routes->get('listData', 'Category::listData');
            $routes->post('save', 'Category::save');
            $routes->post('update', 'Category::update');
            $routes->post('delete/(:num)', 'Category::delete/$1');
        });

        // Member
        $routes->group('member', function($routes) {
            $routes->get('/', 'Member::index');
            $routes->get('listData', 'Member::listData');
            $routes->post('save', 'Member::save');
            $routes->post('update', 'Member::update');
            $routes->post('delete/(:num)', 'Member::delete/$1');
        });

        // User / Karyawan
        $routes->group('user', function($routes) {
            $routes->get('/', 'User::index');
            $routes->get('listData', 'User::listData');
            $routes->post('save', 'User::save');
            $routes->post('update', 'User::update');
            $routes->post('delete/(:num)', 'User::delete/$1');
        });

        // Laporan
        $routes->get('laporan', 'Laporan::index');
        $routes->get('laporan-penjualan', 'Laporan::index');
        $routes->get('laporan-penjualan/summary', 'Laporan::summary');
        $routes->get('laporan-penjualan/chart', 'Laporan::chartData');
        $routes->get('laporan-penjualan/export-csv', 'Laporan::exportCsv');
        $routes->get('laporan-penjualan/print', 'Laporan::printReport');

        // Laporan keuntungan
        $routes->get('laporan-keuntungan', 'Keuntungan::index');
        $routes->get('laporan-keuntungan/summary', 'Keuntungan::summary');
        $routes->get('laporan-keuntungan/chart', 'Keuntungan::chartData');
        $routes->get('laporan-keuntungan/data', 'Keuntungan::tableData');
        $routes->get('laporan-keuntungan/export-csv', 'Keuntungan::exportCsv');
        $routes->get('laporan-keuntungan/print', 'Keuntungan::printReport');

        // Riwayat
        $routes->get('riwayat-transaksi', 'Laporan::riwayat');
        $routes->get('riwayat-transaksi/data', 'Laporan::riwayatData');
        $routes->get('riwayat-transaksi/detail/(:num)', 'Laporan::detail/$1');
    });
});

$routes->setAutoRoute(true);
