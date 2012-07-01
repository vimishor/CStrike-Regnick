<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Email configuration
 * 
 * @see http://codeigniter.com/user_guide/libraries/email.html  Section "Email Preferences"
 */

// ----------------------------------------------------------------------------------------------------------

/**
 * Type of mail.
 * 
 * Options: text or html
 */
$config['mailtype'] = 'html';

/**
 * The mail sending protocol.
 * 
 * Options: mail, sendmail, or smtp
 */
$config['protocol'] = 'smtp';

/**
 * SMTP server settings
 */
$config['smtp_host'] = 'ssl://smtp.googlemail.com';
$config['smtp_user'] = 'username@gmail.com';
$config['smtp_pass'] = 'my-super-duper-password';
$config['smtp_port'] = 465;

/**
 * Useragent
 * 
 * Note: Please do not change. This will have a future use.
 */
$config['useragent'] = 'CI/Regnick';

// Comply with RFC 822
$config['newline']  = "\r\n";
$config['crlf']     = "\r\n";

/* End of file email.php */
/* Location: ./application/config/email.php */