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
 * CStrike-Regnick Email Helpers
 *
 * @package		CStrike-Regnick
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Gentle Software Solutions
 */
 
// ------------------------------------------------------------------------

/**
 * Prepare email message from template
 *  
 * @access  public
 * @param   string  $to         Destination email address
 * @param   string  $subject    Email subject
 * @param   string  $message    Email message
 * @param   array   $options    Options that will be available within template file
 * @return  string
 */
if ( ! function_exists('prep_email_message'))
{
    function prep_email_message($template_file, array $options = array() )
    {
        $CI =& get_instance();
        
        $CI->load->vars($options);
        return $CI->load->file(APPPATH.$template_file.EXT, true);
    }
}

// ------------------------------------------------------------------------

/**
 * Send email
 *  
 * @access  public
 * @param   string  $recipient  Destination email address
 * @param   string  $subject    Email subject
 * @param   string  $message    Email message
 * @return  bool
 */
if ( ! function_exists('send_email'))
{
    function send_email($recipient, $subject = 'Test email', $message = 'Hello World' )
    {
        if (can_send_email() === false)
        {
            log_message('error', 'You are trying to send email, without setting up email configuration first.');
            return false;
        }
            
        $CI =& get_instance();
        
        $CI->load->library('email');
        $CI->email->from( $CI->config->item('webmaster.email'), $CI->config->item('site.name') );
        $CI->email->to($recipient);
        $CI->email->subject( $subject .' - '. $CI->config->item('site.name') );
        $CI->email->message($message);
        
        return $CI->email->send();
    }
}

// ------------------------------------------------------------------------

/**
 * Validate email address
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('valid_email'))
{
	function valid_email($address)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
	}
}

/* End of file email_helper.php */
/* Location: ./app/helpers/email_helper.php */