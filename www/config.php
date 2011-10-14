<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
 * Allowed URL characters
 *
 * Specify with a regular expression which characters are permitted
 * within your _GET and _POST requests.
 *
 * DO NOT CHANGE THIS UNLESS YOU REALLY KNOW WHAT ARE YOU DOING !
 */
define('ALLOWED_CHARS', 'a-zA-Z0-9 %@.:_\/-');


/**
 * -----------------------
 * Website section
 * -----------------------
 */
// Website Title
$config['title']    = 'CStrike Regnick';

// Script full URL
$config['site_url'] = 'http://localhost/regnick/';

// Entries number to show per page (used by pagination)
$config['per_page'] = '10'; 

/**
 * -----------------------
 * Database section
 * -----------------------
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'regnick');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_TABLE', 'admins');
// ---
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_general_ci');
define('DB_TABLE_PREFIX', '');


/**
 * -----------------------
 * Email section
 * -----------------------
 */
$config['email_username'] = 'my_username@gmail.com'; 
$config['email_password'] = 'my_password';
$config['email_server'] = 'smtp.gmail.com';
$config['email_port'] = '465';
$config['email_secure'] = 'ssl';


/**
 * -----------------------
 * Servers section
 * -----------------------
 * 
 * NOTE: ID must be a unique number !
 * 
 * PARAMS: id, address, default access level
 */
add_server(1, 'cs.domain.tld:27015', 'ab');
add_server(2, 'war.domain.tld:26015', 'ab');

// CONFIG END
?>