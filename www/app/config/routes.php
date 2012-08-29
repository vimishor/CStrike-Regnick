<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller']        = "server/server_list";
$route['404_override']              = '';

$route['server/(:num)/members']         = 'server/members/$1';
$route['server/(:num)/members/(:num)']  = 'server/members/$1/$2';
$route['server/(:num)/team']            = 'server/team/$1';
$route['server/(:num)/team/(:num)']     = 'server/team/$1/$2';
$route['server/list/(:num)']            = 'server/server_list/$1';
$route['server/list']                   = 'server/server_list';

$route['acp/server/edit/(:num)']                        = 'acp/server_edit/$1';
$route['acp/server/add']                                = 'acp/server_add';
$route['acp/server/del']                                = 'acp/server_delete';
$route['acp/server/list']                               = 'acp/server_list';
$route['acp/server/list/(:num)']                        = 'acp/server_list/$1';
$route['acp/server/search/([a-z 0-9~.:_\-]+)']          = 'acp/server_list/0/$1';
$route['acp/server/search/([a-z 0-9~.:_\-]+)/(:num)']   = 'acp/server_list/$2/$1';
$route['acp/server/pre_search']                         = 'acp/pre_server_search';

$route['acp/group/edit/(:num)']                         = 'acp/group_edit/$1';
$route['acp/group/add']                                 = 'acp/group_add';
$route['acp/group/del']                                 = 'acp/group_delete';
$route['acp/group/list']                                = 'acp/group_list';
$route['acp/group/list/(:num)']                         = 'acp/group_list/$1';
$route['acp/group/search/([a-z 0-9~.:_\-]+)']           = 'acp/group_list/0/$1';
$route['acp/group/search/([a-z 0-9~.:_\-]+)/(:num)']    = 'acp/group_list/$2/$1';
$route['acp/group/pre_search']                          = 'acp/pre_group_search';


$route['acp/user/edit/(:num)']                      = 'acp/user_edit/$1';
$route['acp/user/add']                              = 'acp/user_add';
$route['acp/user/del']                              = 'acp/user_delete';
$route['acp/user/list']                             = 'acp/users_list';
$route['acp/user/list/(:num)']                      = 'acp/users_list/$1';
$route['acp/user/search/([a-z 0-9~.:_\-]+)']        = 'acp/users_list/0/$1';
$route['acp/user/search/([a-z 0-9~.:_\-]+)/(:num)'] = 'acp/users_list/$2/$1';
$route['acp/user/pre_search']                       = 'acp/pre_user_search';
$route['acp/user/(:num)/access/(:num)']             = 'acp/user_access/$1/$2';      # userID , serverID
$route['acp/user/access/del']                       = 'acp/user_access_del';        # userID

/* End of file routes.php */
/* Location: ./application/config/routes.php */