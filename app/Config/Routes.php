<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->resource('parts_api',['controller' => 'PartsController']);
$routes->resource('purchase_api',['controller' => 'PurchaseOrderController']);
$routes->resource('request_api',['controller' => 'RequestController']);
$routes->resource('news_api',['controller' => 'NewsController']);
$routes->resource('user_api',['controller' => 'UserController']);
$routes->resource('notifications_api',['controller' => 'NotificationsController']);
$routes->resource('storage_api',['controller' => 'StorageController']);

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * REST CUSTOM ROUTES
 * --------------------------------------------------------------------
 */

$routes->add('user/login', 'UserController::login');
$routes->add('user/logout', 'UserController::logout');

$routes->get('home','Home::index');

$routes->get('parts/storage/(:any)', 'PartsController::getByStorage/$1');

$routes->get('requests/status/(:any)', 'RequestController::getByStatus/$1');

$routes->add('news', 'NewsController');
$routes->add('news/highlight', 'NewsController::highlight');
$routes->add('news/create', 'NewsController::writeNews');
