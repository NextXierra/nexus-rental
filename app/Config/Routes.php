<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', '\Modules\LandingPage\Controllers\Home::index');
$routes->get('login', '\Modules\Login\Controllers\Login::index');
$routes->post('login/process', '\Modules\Login\Controllers\Login::processLogin');
$routes->get('register', '\Modules\Login\Controllers\Login::register');
$routes->post('register/process', '\Modules\Login\Controllers\Login::processRegister');
$routes->get('logout', '\Modules\Login\Controllers\Login::logout');
$routes->get('dashboard/admin', '\Modules\DashboardAdmin\Controllers\DashboardAdmin::index');
$routes->get('dashboard/admin/unit-ps', '\Modules\DashboardAdmin\Controllers\UnitPs::index');
$routes->post('dashboard/admin/unit-ps/store', '\Modules\DashboardAdmin\Controllers\UnitPs::store');
$routes->post('dashboard/admin/unit-ps/(:num)/update', '\Modules\DashboardAdmin\Controllers\UnitPs::update/$1');
$routes->post('dashboard/admin/unit-ps/(:num)/delete', '\Modules\DashboardAdmin\Controllers\UnitPs::delete/$1');

$routes->get('dashboard/admin/reservasi', '\Modules\DashboardAdmin\Controllers\Reservasi::index');
$routes->post('dashboard/admin/reservasi/store', '\Modules\DashboardAdmin\Controllers\Reservasi::store');
$routes->post('dashboard/admin/reservasi/(:num)/complete', '\Modules\DashboardAdmin\Controllers\Reservasi::complete/$1');
$routes->post('dashboard/admin/reservasi/(:num)/cancel', '\Modules\DashboardAdmin\Controllers\Reservasi::cancel/$1');

$routes->get('dashboard/admin/pembayaran', '\Modules\DashboardAdmin\Controllers\Pembayaran::index');
$routes->get('dashboard/user', '\Modules\DashboardUser\Controllers\DashboardUser::index');
