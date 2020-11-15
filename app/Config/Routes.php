<?php namespace Config;
$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

$routes->get('/', 'Home::index');
$routes->resource('api/auth', ['controller' => 'Auth']);

## App Routing
$routes->get('api/product/list', 'Product::list');
$routes->get('api/user/list', 'User::list');
$routes->post('api/user/save', 'User::save');
$routes->post('api/user/changePassword', 'User::changePassword');
$routes->delete('api/user/delete/(:num)', 'User::deleteById/$1');
## App Routing


if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
