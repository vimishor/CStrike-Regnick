<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (defined('ENV'))
{
	switch (ENV)
	{
		case 'dev':
			error_reporting(E_ALL | E_STRICT);
			ini_set("display_errors", 1);
		break;
		
		case 'prod':
			error_reporting(0);
		break;
		
		default:
			error_reporting(E_ALL | E_STRICT);
			ini_set("display_errors", 1);
	}
}

// Set default timezone in PHP 5.
if ( function_exists( 'date_default_timezone_set' ) )
	date_default_timezone_set( 'UTC' );

/**
 * Check and fix config values
 */
function check_config()
{
	global $config;
	
	// remove trailing slash
	$config['site_url'] = preg_replace('{/$}', '', $config['site_url']);
    $config['email_subject'] = $config['email_body'] = $config['email_send_to'] = ''; 
}

$config['servers'] = array();

require_once(SYS.'common'.EXT);
require_once(BASEPATH.'config'.EXT);

// clean any _GET and _POST request before sending it to Route class
$_GET = array_map('clean_string', $_GET);
$_POST = array_map('clean_string', $_POST);

// to ensure that the user has not written something wrong in settings
check_config();

require_once(SYS.'lib'.DS.'Route'.EXT);
require_once(SYS.'lib'.DS.'Input'.EXT);
require_once(SYS.'lib'.DS.'Database'.EXT);
require_once(SYS.'lib'.DS.'Pagination'.EXT);
require_once(SYS.'lib'.DS.'Mail'.EXT);

// instantiate classes
$INP = new Input();
$sql = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$pagination = new Pagination();

// setup route
Route::dispatch();
?>