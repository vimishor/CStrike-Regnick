<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------ 

/**
 * CStrike-Regnick Core Helpers
 *
 * @package		CStrike-Regnick
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Gentle Software Solutions
 */

// ------------------------------------------------------------------------ 

/**
 * Store current URL
 * 
 * @return  void
 */
if ( ! function_exists('store_location'))
{
    function store_location()
    {
        $CI =& get_instance();
        $CI->load->helper('url');
        $CI->session->set_userdata('continue', current_url());
    }
}

/**
 * Fetch option from DB
 * 
 * @param   string      $name   Option name
 * @return  string|bool         False on error
 */
if ( ! function_exists('get_option'))
{
    function get_option($name)
    {
        $CI =& get_instance();
        return $CI->core_model->get_option($name);
    }
}

/**
 * Add a new option or update an existing one
 * 
 * @param   string  $name
 * @param   string  $value
 * @return  bool
 */
if ( ! function_exists('set_option'))
{
    function set_option($name, $value)
    {
        $CI =& get_instance();
        return $CI->core_model->set_option($name, $value);
    }
}

/**
 * Send message to user
 * 
 * @param   string  $message    Message text
 * @param   string  $type       Message type (ie: error, success, info)
 * @return  void
 */
if ( ! function_exists('notify'))
{
    function notify($message, $type = 'info')
    {
        $CI =& get_instance();
        $CI->session->set_flashdata('userNotify', array(
            'type' => $type,
            'body' => $message
        ));
    }
}

/**
 * Fetch user notice from flashdata
 * 
 * @return  array
 */
if ( ! function_exists('get_userNotice'))
{
    function get_userNotice()
    {
        $CI =& get_instance();
        return is_array($CI->session->flashdata('userNotify')) ? $CI->session->flashdata('userNotify') : false;
    }
}

/**
 * Get name of current theme.
 * 
 * @return  string
 */
if ( ! function_exists('theme_name'))
{
    function theme_name()
    {
        $CI =& get_instance();
        return $CI->template->get_theme();
    }
}

/**
 * Current theme assets base URL
 * 
 * @return  string
 */
if ( ! function_exists('assets_base'))
{
    function assets_base()
    {
        return base_url().'themes/'.theme_name().'/assets/';
    }
}

/**
 * Check if application is running under development environment
 * 
 * @return  string
 */
if ( ! function_exists('is_dev'))
{
    function is_dev()
    {
        return ('development' == ENVIRONMENT) ? true : false;
    }
}

/**
 * Console log
 * 
 * Log data to console, if we are in development environment
 * 
 * @return  void
 */
if ( ! function_exists('console_log'))
{
    function console_log($data = null)
    {
        if (is_dev())
        {
            Console::log($data);
        }
    }
}

/**
 * Check if form validation error(s) exists
 * 
 * @return  bool
 */
if ( ! function_exists('form_has_error'))
{
    function form_has_error()
    {
        return (function_exists('validation_errors') AND (validation_errors() != '') ) ? true : false; 
    }
}

/**
* Validate string as IP (IPV4)
*
* @param    string  $ip_addr    String to be validated
* @return   bool
*/
if ( ! function_exists('is_ip'))
{
    function is_ip($ip_addr)
    {
        if (preg_match("/^(\d{1,3})\.$/", $ip_addr) || preg_match("/^(\d{1,3})\.(\d{1,3})$/",
            $ip_addr) || preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $ip_addr) ||
            preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $ip_addr))
        {
            $parts = explode(".", $ip_addr);
            
            foreach ($parts as $ip_parts)
            {
                if (intval($ip_parts) > 255 || intval($ip_parts) < 0)
                    return false; //if number is not within range of 0-255
            }
            return true;
        }
        else
            return false;
    }
}

/**
* Validate string as steamid
*
* @param    string  $steam_id   String to be checked
* @return   bool
*/
if ( ! function_exists('is_steamid'))
{
    function is_steamid($steam_id)
    {
        $steam_id = preg_replace("/^STEAM_/i", "", $steam_id);
        if (preg_match("/^0:([0-1]):([0-9]+)$/", $steam_id, $m))
        {
            $status = true;
        }
        else
        {
            $status = false;
        }
        return $status;
    }
}

/**
 * Is email configured properly ?
 * 
 * @return  bool
 */
if ( ! function_exists('can_send_email'))
{
    function can_send_email()
    {
        return (defined('RN_EMAIL_CONFIGURED') AND (RN_EMAIL_CONFIGURED));
    }
}

function ci()
{
    return get_instance();
}

/**
 * Merge two arrays without changing the datatypes of the values in the arrays.
 * 
 * array_merge_recursive() it converts values with duplicate keys to
 * arrays rather then overwriting the value with the new one.
 * 
 * @param   array $array1
 * @param   array $array2
 * @return  array
 */
function array_merge_recursive_distinct ( array &$array1, array &$array2 )
{
    $merged = $array1;

    foreach ( $array2 as $key => &$value )
    {
        if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
        {
            $merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
        }
        else
        {
            $merged [$key] = $value;
        }
    }

    return $merged;
}

/**
 * Output search form for specified page
 * 
 * @param   string  $for_what   Page name where the form will be used
 * @return  string              HTML to output
 */
function show_search_form($for_what)
{
    ci()->load->helper('form');
        
    if ($for_what == 'users')
    {        
        $output = form_open('acp/user/pre_search', array('class' => 'form-search')) . form_input(array(
            'name'          => 'user',
            'class'         => 'input-medium search-query',
            'placeholder'   => 'search ...',
       )) .' '. form_button(array(
            'class'         => 'btn btn-small',
            'content'       => 'Go',
            'type'          => 'submit'
       )) . form_close();
    }
    
    if ($for_what == 'groups')
    {        
        $output = form_open('acp/group/pre_search', array('class' => 'form-search')) . form_input(array(
            'name'          => 'group',
            'class'         => 'input-medium search-query',
            'placeholder'   => 'search ...',
       )) .' '. form_button(array(
            'class'         => 'btn btn-small',
            'content'       => 'Go',
            'type'          => 'submit'
       )) . form_close();
    }
    
    if ($for_what == 'server')
    {        
        $output = form_open('acp/server/pre_search', array('class' => 'form-search')) . form_input(array(
            'name'          => 'server',
            'class'         => 'input-medium search-query',
            'placeholder'   => 'search ...',
       )) .' '. form_button(array(
            'class'         => 'btn btn-small',
            'content'       => 'Go',
            'type'          => 'submit'
       )) . form_close();
    }
    
    return $output;
}

/**
 * Check if a PHP function is disabled from php.ini
 * 
 * @param   string  $function   Function name
 * @return  bool
 */
function is_disabled($function) {
    $disabled_functions=explode(',',ini_get('disable_functions'));
    return in_array($function, $disabled_functions);
}