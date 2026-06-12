<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', '\Modules\LandingPage\Controllers\Home::index');
$routes->get('login', 'Login::index');
$routes->post('login/process', 'Login::processLogin');
$routes->get('register', 'Login::register');
$routes->post('register/process', 'Login::processRegister');
$routes->get('logout', 'Login::logout');
