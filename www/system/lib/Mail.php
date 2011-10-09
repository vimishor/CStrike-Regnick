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
 * Just setup PHPMailer here
 */

//TODO: refactor this
include(SYS.'lib'.DS.'phpmailer'.DS.'class.phpmailer'.EXT);
$mail           = new PHPMailer();

function email_setup($email_send_to, $email_subject, $email_body)
{    
    global $mail, $config;

    // telling the class to use SMTP
    $mail->IsSMTP();
    
    /**
     *  Enables SMTP debug information (for testing)
     *      1 = errors and messages
     *      2 = messages only
     */
    $mail->SMTPDebug  = 0;
    
    // enable SMTP authentication
    $mail->SMTPAuth   = true;
    
    // sets the prefix to the servier
    $mail->SMTPSecure = $config['email_secure'];
    
    // sets SMTP server
    $mail->Host       = $config['email_server'];
    
    // set the SMTP port for server
    $mail->Port       = $config['email_port'];
    
    // send as HTML
    $mail->IsHTML(false);
    
    // login data
    $mail->Username   = ''.$config['email_username'].'';
    $mail->Password   = ''.$config['email_password'].'';
    
    $mail->SetFrom(''.$config['email_username'].'', ''.$config['email_username'].'');
    $mail->AddReplyTo(''.$config['email_username'].'', ''.$config['email_username'].'');
    $mail->Subject    = $email_subject;
    $mail->AltBody    = $email_body;
    $mail->MsgHTML($email_body);
    $mail->AddAddress($email_send_to, $email_send_to);   
}
?>