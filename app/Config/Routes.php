<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', '\Modules\LandingPage\Controllers\Home::index');
