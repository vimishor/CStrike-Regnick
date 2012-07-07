<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| CStrike-Regnick application config
| -------------------------------------------------------------------------
| This file lets you change various settings for CStrike-Regnick 
| application. 
|
*/

/**
 * Website name.
 * You can use your community name.
 */
$config['site.name'] = 'My community';

/**
 * Password encryption method
 * Values: md5, sha1, 'none'
 */
$config['password_encrypt'] = 'none';

/**
 * Allow nickname registration on all servers ?
 * 
 * default: true
 */
$config['register.global'] = false;

/**
 * Send confirmation email for new accounts ?
 * 
 * default: true
 */
$config['register.confirmation'] = true;

/**
 * Use those sexy tooltips ?
 * 
 * default: false
 */
$config['ui.tooltips'] = true;


/**
 * What email address we will use to send emails ?
 */
$config['webmaster.email']  = 'accounts@example.com';

/**
 * How many SQL results do we show on one page ?
 */
$config['results_per_page'] = '12';

/* End of file regnick.php */
/* Location: ./application/config/regnick.php */