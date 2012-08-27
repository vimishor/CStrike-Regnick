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
$config['smtp_host'] = '{smtp_host}';
$config['smtp_user'] = '{smtp_user}';
$config['smtp_pass'] = '{smtp_pass}';
$config['smtp_port'] = {smtp_port};

/**
 * Useragent
 * 
 * Note: Please do not change. This will have a future use.
 */
$config['useragent'] = 'CI/Regnick';

// Comply with RFC 822
$config['newline']  = "\r\n";
$config['crlf']     = "\r\n";

defined('RN_EMAIL_CONFIGURED') OR define('RN_EMAIL_CONFIGURED', TRUE);

/* End of file email.php */
/* Location: ./application/config/email.php */