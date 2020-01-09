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
| https://codeigniter.com/user_guide/general/routing.html
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
$route['install'] = 'Auth/install';

$route['default_controller'] = 'Home/index';

/**** Admin Urls *********/
$route['admin/dashboard'] = 'admin/admin/index';
$route['admin/orders'] = 'admin/Adminorders';
$route['admin/orders/(:any)'] = 'admin/Adminorders';
$route['all-users'] = 'admin/Admin/getUsers';
$route['all-users/(:any)'] = 'admin/Admin/getUsers';
$route['all-vendors'] = 'admin/Admin/vendorUsers';
$route['all-vendors/(:any)'] = 'admin/Admin/vendorUsers';
$route['login/create_password/(:any)']='Login/reset_password/$1';
$route['admin/all-categories'] = 'admin/Categories/index';
$route['admin/all-categories/(:any)'] = 'admin/Categories/index';
$route['create-category'] = 'admin/Categories/createcategory';
$route['admin/delete-user/(:num)'] = 'admin/Admin/delete_user/$1';
$route['admin/edit-user/(:num)'] = 'admin/Admin/edit_user/$1';
$route['cust/uninstall'] = 'Auth/uninstall';
$route['shop/uninstall'] = 'Auth/after_uninstall';
$route['cust/data'] = 'Auth/cust_data';
/** Supplier Urls ***/
$route['all-suppliers'] = 'Suppliers/index';
$route['all-suppliers/(:any)'] = 'Suppliers/index';
$route['add-Supplier'] = 'Suppliers/addSupplier';
$route['import'] = 'Import/importProducts'; 
$route['import-products'] = 'Import/addToStore'; 

 


//$route['editSupplier'] = 'Suppliers/edit_supplier';

/** products url **/
$route['all-products'] = 'products/AllProducts/index';
$route['thank-you'] = 'Thankyoupayment';
$route['import-products-data'] = 'products/AllProducts/import_product_data';
$route['test'] = 'products/TestProducts/index';
$route['test/(:any)'] = 'products/TestProducts/index';
$route['all-products/(:any)'] = 'products/AllProducts/index';
$route['create-product'] = 'products/AllProducts/createItem';
$route['product/edit-product/(:num)'] = 'products/AllProducts/editProducts/$1';

$route['privacy-policy'] = 'privacy/index';

/**** Contact Us *********/
$route['contact-us'] = 'contact/index'; 
$route['user/contact-us'] = 'user/user/contact_us'; 
$route['about-us']='About_us/index';
/**** User Urls *********/ 

$route['user/dashboard'] = 'user/user/index';

$route['user/payment'] = 'user/user/checkout';
$route['user/make-payment'] = 'user/user/make_payment';
$route['notify-payment'] = 'user/user/notify_payment';

$route['user/user-profile/(:num)'] = 'user/user/user_profile/$1';
$route['profile/edit-profile'] = 'Profile/editprofile';
$route['user/products/(:num)'] = 'products/AllProducts/productsGird/$1';
$route['user/products'] = 'products/AllProducts/productsGird';
$route['user/all-products'] = 'products/AllProducts/user_products';

$route['user/sourced-products'] = 'products/AllProducts/sourced_products';
$route['user/sourced-products/(:any)'] = 'products/AllProducts/sourced_products';

$route['user/mylist'] = 'Mylist/get_list_detail';
$route['user/mylist/(:any)'] = 'Mylist/get_list_detail';
$route['user/orders'] = 'Orders/get_orders';
$route['user/orders-search'] = 'Orders/orders_search';

/********** Sourcing List URL ***********/
$route['create-sourcing-product'] = 'sourcing/create_sourcing_product';
$route['add-sourcing'] = 'Sourcing/index/';

$route['products-sourcing-list'] = 'Sourcinglist/index'; 
$route['sourcinglist/product-detail-page/(:num)'] = 'Sourcinglist/product_detail_page/$1'; 

/***************** End Here ************/


$route['product/product-details/(:any)'] = 'products/AllProducts/product_details/$1';

/**** Vendor Urls *********/
$route['vendor/dashboard'] = 'vendor/vendor/index';
$route['vendor/orders'] = 'vendor/vendororders/index';
$route['vendor/orders-search'] = 'vendor/Vendororders/orders_search';



$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*********** Sourcing url ************/
$route['sourcinglist'] = 'sourcinglist/index';
