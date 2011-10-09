<?php

/**
 * 
 * @package		CStrike Regnick
 * @author		www.gentle.ro
 * @copyright	Copyright (c) 2009 - 2011, Gentle.ro 
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link		http://www.gentle.ro/proiecte/cstrike-regnick/
 * 
 */

 
/**
 * Setup application environment for appropiate error_report setting
 *  Accepted values: dev ; prod 
 *  (default value: dev)
 */
define('ENV', 'dev');

define('DS', DIRECTORY_SEPARATOR);
define('EXT', '.php');

// absolute path to script directory
if ( !defined('BASEPATH') )
	define('BASEPATH', dirname(__FILE__) . DS);

define('APP', BASEPATH.'app'.DS);
define('SYS', BASEPATH.'system'.DS);
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('FCPATH', str_replace(SELF, '', __FILE__));

require_once(SYS.'init'.EXT);
?>