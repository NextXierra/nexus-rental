<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', '\Modules\LandingPage\Controllers\HomeController::index');
$routes->get('login', '\Modules\Auth\Controllers\AuthController::index');
$routes->post('login/process', '\Modules\Auth\Controllers\AuthController::processLogin');
$routes->get('register', '\Modules\Auth\Controllers\AuthController::register');
$routes->post('register/process', '\Modules\Auth\Controllers\AuthController::processRegister');
$routes->get('logout', '\Modules\Auth\Controllers\AuthController::logout');

// Dashboard Admin Group
$routes->group('dashboard/admin', ['filter' => ['auth', 'admin']], function($routes) {
    $routes->get('/', '\Modules\DashboardAdmin\Controllers\DashboardController::index');
    
    // Unit CRUD
    $routes->get('unit', '\Modules\DashboardAdmin\Controllers\UnitController::index');
    $routes->post('unit/store', '\Modules\DashboardAdmin\Controllers\UnitController::store');
    $routes->post('unit/(:num)/update', '\Modules\DashboardAdmin\Controllers\UnitController::update/$1');
    $routes->post('unit/(:num)/delete', '\Modules\DashboardAdmin\Controllers\UnitController::delete/$1');

    // Games CRUD
    $routes->get('games', '\Modules\DashboardAdmin\Controllers\GameController::index');
    $routes->post('games/store', '\Modules\DashboardAdmin\Controllers\GameController::store');
    $routes->post('games/(:num)/update', '\Modules\DashboardAdmin\Controllers\GameController::update/$1');
    $routes->post('games/(:num)/delete', '\Modules\DashboardAdmin\Controllers\GameController::delete/$1');

    // Reservasi Admin
    $routes->get('reservasi', '\Modules\DashboardAdmin\Controllers\ReservationController::index');
    $routes->get('reservasi/check-availability', '\Modules\DashboardAdmin\Controllers\ReservationController::checkAvailability');
    $routes->get('reservasi/check-units', '\Modules\DashboardAdmin\Controllers\ReservationController::checkUnits');
    $routes->post('reservasi/store', '\Modules\DashboardAdmin\Controllers\ReservationController::store');
    $routes->post('reservasi/(:num)/approve', '\Modules\DashboardAdmin\Controllers\ReservationController::approve/$1');
    $routes->post('reservasi/(:num)/reject', '\Modules\DashboardAdmin\Controllers\ReservationController::reject/$1');
    $routes->post('reservasi/(:num)/complete', '\Modules\DashboardAdmin\Controllers\ReservationController::complete/$1');
    $routes->post('reservasi/(:num)/cancel', '\Modules\DashboardAdmin\Controllers\ReservationController::cancel/$1');

    // Pembayaran
    $routes->get('pembayaran', '\Modules\DashboardAdmin\Controllers\PaymentController::index');

    // Pelanggan CRUD
    $routes->get('pelanggan', '\Modules\DashboardAdmin\Controllers\CustomerController::index');
    $routes->post('pelanggan/store', '\Modules\DashboardAdmin\Controllers\CustomerController::store');
    $routes->post('pelanggan/(:num)/update', '\Modules\DashboardAdmin\Controllers\CustomerController::update/$1');
    $routes->post('pelanggan/(:num)/delete', '\Modules\DashboardAdmin\Controllers\CustomerController::delete/$1');

    // Laporan
    $routes->get('laporan', '\Modules\DashboardAdmin\Controllers\ReportController::index');
    $routes->get('laporan/print', '\Modules\DashboardAdmin\Controllers\ReportController::printLaporan');
    $routes->get('laporan/export/excel', '\Modules\DashboardAdmin\Controllers\ReportController::exportExcel');

    // Profil Admin
    $routes->get('profil', '\Modules\DashboardAdmin\Controllers\ProfileController::index');
});

// Dashboard User Group
$routes->group('dashboard/user', ['filter' => ['auth', 'user']], function($routes) {
    $routes->get('/', '\Modules\DashboardUser\Controllers\DashboardController::index');
    
    // Reservasi User
    $routes->get('reservasi', '\Modules\DashboardUser\Controllers\ReservationController::index');
    $routes->post('reservasi/store', '\Modules\DashboardUser\Controllers\ReservationController::store');

    // Profil User
    $routes->get('profil', '\Modules\DashboardUser\Controllers\ProfileController::index');
    $routes->post('profil/update', '\Modules\DashboardUser\Controllers\ProfileController::update');
});
