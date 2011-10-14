<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * @package		CStrike Regnick
 * @version     1.0.0
 * @author		www.gentle.ro
 * @copyright	Copyright (c) 2009 - 2011, Gentle.ro 
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link		http://www.gentle.ro/proiecte/cstrike-regnick/
 * 
 */

/**
 * CStrike Regnick routing class
 * 
 * Basic routing rules.
 * 
 * @author www.gentle.ro
 * @version 0.1.1
 */
class Route
{
    public static function dispatch()
    {
        global $INP, $config;
        
        // if update directory exists, redirect any request
        if ( ($INP->segment(1) !== 'update') && (is_dir(BASEPATH.'core_update')) )
        {
            header('Location: '.$config['site_url'].'/update/', true);
        }
            
        
        /**
         * Block: Recover account
         */
        if (isset($_POST['recover']))
        {            
            if (!is_email($_POST['rec_email']))
            {
                show_page('default', 'Account recovery error', 'Invalid email address.', true);
            }
            // email exists in DB ?
            elseif (!email_exist($_POST['rec_email']))
            {
                show_page('default', 'Account recovery error', 'Specified email address is not known.', true);
            }

            $message = 'Your password is: '.get_pass_by_mail($_POST['rec_email']);
            
            // send email
            if (send_email($_POST['rec_email'], 'Counter-Strike server account recover', $message) === true)
            {
                show_page('default', 'Check your email', 'Password has been emailed to specified address. Please check your email.', true);
            }
            else
            {
                show_page('default', 'That\'s strange', 'It seems that there was a problem sending this email. Please try again later. Sorry!', true);
            }
        }
        /**
         * Block: Register account
         */
        elseif (isset($_POST['register']))
        {
            $nickname   = $_POST['nickname'];
            $pass1      = $_POST['password'];
            $pass2      = $_POST['check_password'];
            $email      = $_POST['email'];
            $server_tag = (int) $_POST['server'];
            $flags      = 'ab';
            
            // validate form
            if (strlen($nickname) < 3 )
            {
                show_page('default', 'Registration error', 'Nickname must be at least 3 characters long.', true);
            }
            elseif (user_exist($nickname))
            {
                show_page('default', 'Registration error', 'Sorry, user already exists.', true);
            }
            elseif ((strlen($pass1) < 6) || (strlen($pass2) < 6))
            {
                show_page('default', 'Registration error', 'Password must be at least 6 characters long.', true);
            } 
            elseif ($pass1 != $pass2) 
            {
                show_page('default', 'Registration error', 'The two passwords are not identical.', true);
            }
            elseif (!is_email($email))
            {
                show_page('default', 'Registration error', 'Invalid email address.', true);
            }
            elseif (email_exist($email))
            {
                show_page('default', 'Registration error', 'Sorry, email has been used already.', true);
            }
            
            /**
             * Switch AmxModX user flags:
             *  ab = nickname / clan tag
             *  ac = steamid
             *  ad = ip
             */
            if (is_ip($nickname)) { $flags = 'ad'; }
            elseif (is_steamid($nickname)) { $flags = 'ac'; }
            
            // add account
            if ( add_account($nickname, $pass1, 'b', $flags, $email, $server_tag) === true)
            {
                show_page('default', 'Nickname registred', 'Your nickname was registered successfully.', true);
            } 
            else
            {
                show_page('default', 'Service unavailable', 'Your nickname has not been registered.', true);
            }
        } 
        
        elseif ($INP->segment(1) === false)
        {
            show_page('register');
        }
        elseif ($INP->segment(1) === 'recover')
        {
            show_page('recover');
        }
        elseif ($INP->segment(1) === 'install')
        {
            if (is_installed() === true)
            {
                show_page('default', 'Already installed', 'CStrike Regnick is already installed.', true);
            }
            elseif (set_installed() === true)
            {
                show_page('default', 'Script installed', 'CStrike Regnick has been installed successfully.', true);
            }
            else
            {
                show_page('default', 'Install error', 'CStrike Regnick has NOT been installed!', true);
            }
        }
        elseif ($INP->segment(1) === 'update')
        {
            if (run_update() === true)
            {
                show_page('default', 'Script updated', 'CStrike Regnick has been updated successfully.<br /><br /><strong>Please delete "core_update" directory</strong> !', true);
            }
            else
            {
                show_page('default', 'Script update error', 'CStrike Regnick has NOT been updated !', true);
            }
        }
        elseif ($INP->segment(1) === 'users')
        {
            if ($INP->segment(2) !== false )
            {
                // paginate
                $page = (int) $INP->segment(2);
                echo show_users($page);
            }
            else
            {
                show_page('default', 'Active users', '', true);
            }
        }
        else
        {
            show_page('404');
        }
    }
}

?>