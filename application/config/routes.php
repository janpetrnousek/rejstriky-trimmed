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
$route['default_controller'] = 'pages';
$route['404_override'] = 'pages/notfound'; // 'nenalezeno';
$route['translate_uri_dashes'] = FALSE;

$route['provozovatel'] = 'pages/content/provozovatel';
$route['inzerce'] = 'pages/content/inzerce';
$route['nenalezeno'] = 'pages/content/notfound';
$route['pravni-upozorneni'] = 'pages/content/pravni-upozorneni';
$route['zdroje-informaci'] = 'pages/content/zdroje-informaci';
$route['pravni-podminky'] = 'pages/content/pravni-podminky';
$route['kontakt'] = 'pages/content/kontakt';

$route['monitoring-rejstriku'] = 'monitoring/index';
$route['monitoring-rejstriku/sledovane/(:num)/(:num)/(:num)'] = 'monitoring/watches/$1/$2/$3';
$route['monitoring-rejstriku/sledovane/(:num)/(:num)'] = 'monitoring/watches/$1/$2/0';
$route['monitoring-rejstriku/sledovane/(:num)'] = 'monitoring/watches/$1/1/0';
$route['monitoring-rejstriku/sledovane'] = 'monitoring/watches/0/1/0';
$route['monitoring-rejstriku/vlozit'] = 'monitoring/watches_add';
$route['monitoring-rejstriku/smazat/(:num)/(:num)/(:num)/(:num)'] = 'monitoring/watches_delete/$1/$2/$3/$4';
$route['monitoring-rejstriku/smazatvse'] = 'monitoring/watches_delete_all';
$route['monitoring-rejstriku/editovat/(:num)'] = 'monitoring/watches_edit/$1';
$route['monitoring-rejstriku/export'] = 'monitoring/watches_export';
$route['monitoring-rejstriku/import'] = 'monitoring/watches_import';
$route['monitoring-rejstriku/import-notifikace'] = 'monitoring/watches_import_notification';
$route['monitoring-rejstriku/import-report/(:num)'] = 'monitoring/watches_import_report/$1';
$route['monitoring-rejstriku/nastaveni'] = 'monitoring/settings';
$route['monitoring-rejstriku/historie'] = 'monitoring/history';
$route['monitoring-rejstriku/synchronizace'] = 'monitoring/synchronization';

$route['rejstrikovy-servis'] = 'servis/index';
$route['rejstrikovy-servis/zalozit'] = 'servis/create';
$route['rejstrikovy-servis/zmena'] = 'servis/change';
$route['rejstrikovy-servis/hledat'] = 'servis/find';

$route['hledat'] = 'search';
$route['hledat/vysledky/(:num)'] = 'search/results/$1';
$route['vypis/o/(:num)/(:any)'] = 'detail/obchodni/$1';
$route['vypis/i/(:num)/(:any)'] = 'detail/isir/$1';
$route['vypis/isir/(:num)/(:any)'] = 'detail/isironly/$1';
$route['vypis/isir/doc/(:num)/(:any)'] = 'detail/isirdoc/$1';
$route['lustrace/o/(:num)/(:any)'] = 'detail/screening/o/$1';
$route['lustrace/i/(:num)/(:any)'] = 'detail/screening/i/$1';
$route['vazby/(:num)/(:any)'] = 'relations/subject/$1';
$route['vazby/(:num)/(:any)/(:any)'] = 'relations/subject/$1/$3';

// legacy url from reports - should be removed in future
$route['inr1/spis/(:num)/(:any)/(:any)'] = 'detail/isironly/$1';

$route['registrace'] = 'register';
$route['registrace/udaje-z-rejstriku'] = 'register/userdata/justdata'; // represented by $this->config->item('WATCH_SOURCE_JUSTDATA');
$route['registrace/rejstrikovy-servis'] = 'register/userdata/servis'; // represented by $this->config->item('WATCH_SOURCE_SERVIS');
$route['registrace/monitoring-rejstriku'] = 'register/monitoring/';
$route['registrace/monitoring-rejstriku/(:num)'] = 'register/monitoring/$1';
$route['registrace/monitoring-rejstriku/user/(:num)'] = 'register/userdata/monitoring/$1'; // represented by $this->config->item('WATCH_SOURCE_MONITORING');

$route['prihlasit'] = 'user/login';
$route['odhlasit'] = 'user/logout';
$route['zapomenute-heslo'] = 'user/forgottenpassword';
$route['obnovit-heslo/(:any)'] = 'user/resetpassword/$1';
$route['zmenit-ucet'] = 'user/changeaccount';
$route['zmenit-ucet-potvrzeni/(:num)'] = 'user/changeaccountconfirmed/$1';
$route['zrusit-ucet'] = 'user/deleteloggedaccount';
$route['zrusit-ucet/(:any)'] = 'user/deleteaccount/$1';
$route['zpet-do-uctu'] = 'user/accounthome';
