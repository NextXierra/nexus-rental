<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', '\Modules\LandingPage\Controllers\HomeController::index');
$routes->get('login', '\Modules\Login\Controllers\LoginController::index');
$routes->post('login/process', '\Modules\Login\Controllers\LoginController::processLogin');
$routes->get('register', '\Modules\Login\Controllers\LoginController::register');
$routes->post('register/process', '\Modules\Login\Controllers\LoginController::processRegister');
$routes->get('logout', '\Modules\Login\Controllers\LoginController::logout');
$routes->get('dashboard/admin', '\Modules\DashboardAdmin\Controllers\DashboardController::index');
$routes->get('dashboard/admin/unit-ps', '\Modules\DashboardAdmin\Controllers\UnitPsController::index');
$routes->post('dashboard/admin/unit-ps/store', '\Modules\DashboardAdmin\Controllers\UnitPsController::store');
$routes->post('dashboard/admin/unit-ps/(:num)/update', '\Modules\DashboardAdmin\Controllers\UnitPsController::update/$1');
$routes->post('dashboard/admin/unit-ps/(:num)/delete', '\Modules\DashboardAdmin\Controllers\UnitPsController::delete/$1');

$routes->get('dashboard/admin/reservasi', '\Modules\DashboardAdmin\Controllers\ReservationController::index');
$routes->get('dashboard/admin/reservasi/check-availability', '\Modules\DashboardAdmin\Controllers\ReservationController::checkAvailability');
$routes->get('dashboard/admin/reservasi/check-units', '\Modules\DashboardAdmin\Controllers\ReservationController::checkUnits');
$routes->post('dashboard/admin/reservasi/store', '\Modules\DashboardAdmin\Controllers\ReservationController::store');
$routes->post('dashboard/admin/reservasi/(:num)/approve', '\Modules\DashboardAdmin\Controllers\ReservationController::approve/$1');
$routes->post('dashboard/admin/reservasi/(:num)/reject', '\Modules\DashboardAdmin\Controllers\ReservationController::reject/$1');
$routes->post('dashboard/admin/reservasi/(:num)/complete', '\Modules\DashboardAdmin\Controllers\ReservationController::complete/$1');
$routes->post('dashboard/admin/reservasi/(:num)/cancel', '\Modules\DashboardAdmin\Controllers\ReservationController::cancel/$1');

$routes->get('dashboard/admin/pembayaran', '\Modules\DashboardAdmin\Controllers\PaymentController::index');
$routes->get('dashboard/user', '\Modules\DashboardUser\Controllers\DashboardController::index');
$routes->get('dashboard/user/reservasi', '\Modules\DashboardUser\Controllers\ReservationController::index');
$routes->post('dashboard/user/reservasi/store', '\Modules\DashboardUser\Controllers\ReservationController::store');

$routes->get('dashboard/admin/pelanggan', '\Modules\DashboardAdmin\Controllers\CustomerController::index');
$routes->post('dashboard/admin/pelanggan/store', '\Modules\DashboardAdmin\Controllers\CustomerController::store');
$routes->post('dashboard/admin/pelanggan/(:num)/update', '\Modules\DashboardAdmin\Controllers\CustomerController::update/$1');
$routes->post('dashboard/admin/pelanggan/(:num)/delete', '\Modules\DashboardAdmin\Controllers\CustomerController::delete/$1');
$routes->get('dashboard/admin/laporan', '\Modules\DashboardAdmin\Controllers\ReportController::index');
$routes->get('dashboard/admin/laporan/print', '\Modules\DashboardAdmin\Controllers\ReportController::printLaporan');
$routes->get('dashboard/admin/laporan/export/excel', '\Modules\DashboardAdmin\Controllers\ReportController::exportExcel');
$routes->get('dashboard/admin/profil', '\Modules\DashboardAdmin\Controllers\ProfileController::index');
$routes->get('dashboard/user/profil', '\Modules\DashboardUser\Controllers\ProfileController::index');
