<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', '\Modules\LandingPage\Controllers\Home::index');
$routes->get('login', '\Modules\Login\Controllers\Login::index');
$routes->post('login/process', '\Modules\Login\Controllers\Login::processLogin');
$routes->get('register', '\Modules\Login\Controllers\Login::register');
$routes->post('register/process', '\Modules\Login\Controllers\Login::processRegister');
$routes->get('logout', '\Modules\Login\Controllers\Login::logout');
