<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['home'] = 'pages/view/home';
$route['dashboard'] = 'pages/view/dashboard';

$route['admin/profile'] = 'admin/view/profile';
$route['admin/order-management'] = 'admin/view/order_management';
$route['admin/product-management'] = 'admin/view/product_management';
$route['admin/products_bulk_price_update'] = 'admin/view/products_bulk_price_update';
$route['admin/price_level_settings'] = 'admin/view/price_level_settings';

$route['admin/product_sheet_update'] = 'admin/view/product_sheet_update';

/*
$route['get_order_info'] = 'pages/get_order_info';
$route['get_product_info_order'] = 'pages/get_product_info_order';
$route['update_profile'] = 'pages/update_profile';
$route['admin/add_user'] = 'admin/add_user';
*/

$route['customer/profile'] = 'customer/view/profile';
$route['customer/myorders'] = 'customer/view/myorders';

$route['order'] = 'pages/view/orderform';

$route['products_list'] = 'pages/products_list';
$route['get_product_info'] = 'pages/get_product_info';

$route['order-failed'] = 'pages/view/order_failed';
$route['order-success'] = 'pages/view/order_success';

$route['guide'] = 'pages/view/guide';






/*
$route['djur/(:any)'] = 'djur/view/$1';
$route['djur'] = 'djur/view/mina_djur';
$route['om'] = 'pages/view/om';


$route['djur/mina_djur'] = 'djur/view/mina_djur';
$route['djur/create_djur']['post'] = 'djur/create_djur';
$route['djur/get_animal_all'] = 'djur/get_animal_all';

$route['lamning'] = 'lamning/view/index';
$route['lamning/registrera_lamning'] = 'lamning/view/registrera_lamning';
$route['lamning/bet_grupp'] = 'lamning/view/bet_grupp';
*/



$route['default_controller'] = 'pages/view';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
