<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Clean string based on regex rule
 * 
 * @since 1.0.0
 * 
 * @param string $data String to sanitize
 * @return string
 */
function clean_string($data)
{
    if (!defined('ALLOWED_CHARS'))
        define('ALLOWED_CHARS', 'a-zA-Z0-9 ~%@.:_\-');

    return preg_replace('/[^' . ALLOWED_CHARS . ']+/i', "", $data);
}

/**
 * Output array in a easy readable format.
 * 
 * @since 1.0.1
 * 
 * @param array $array Array to output
 */
function _debug($array)
{
    echo '<pre style="text-align: left;">';
    var_dump($array);
    echo '</pre>';
}

/**
 * Load template file with an optional message to parse.
 * 
 * @since 1.0.1
 * 
 * @param string $name Template filename
 * @param string $message Message to show in template
 * @param bool $die Stop execution after template render ?
 */
function show_page($name, $page_title=null, $page_message=null, $die=false)
{
    global $config;

    if (file_exists(APP . $name . EXT))
    {
        include (APP . $name . EXT);
        
        if ($die === true)
            exit;
    }
    
}

/**
 * @see show_page();
 */
function add_template($file)
{
    show_page($file);
}

/**
 * Add server data to a big array
 * 
 * @since 1.0.0
 * 
 * @param int $id Server ID
 * @param string $address Server DNS or IP
 * @param string $access_level User access level
 * @return array
 */
function add_server($id, $address, $access_level='ab')
{
	global $config;
	
	return array_push($config['servers'], array('id' => $id, 'address' => $address, 'access' => $access_level) );
}


/**
 * Validate email address
 * 
 * @since 1.0.0
 * 
 * @param string $address String to validate
 * @return bool
 */
function is_email($address)
{
    return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? false : true;
}


/**
 * Validate string as IP (IPV4)
 * 
 * @since 1.0.0
 * 
 * @param string $ip_addr String to be validated
 * @return bool
 */
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


/**
 * Validate string as steamid
 * 
 * @since 1.0.1
 * 
 * @param string $steam_id String to be checked
 * @return bool
 */
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


/**
 * Nickname / IP / SteamID already registred
 * 
 * @param string nickname/ip/steamid
 * @return bool True if exist 
 */
function user_exist($nickname)
{
    global $sql;
    
    $q = $sql->query(
            $sql->prepare("SELECT auth FROM `".DB_TABLE."` WHERE `auth` = %s", $nickname)
         );
        
    return ($sql->num_rows() > 0) ? true : false;
}


/**
 * Add new user account to database
 * 
 * @since 1.0.0
 * 
 * @param string $nickname User Nickname/IP/SteamID
 * @param string $password Account password
 * @param string $access Account access
 * @param string $flags Account flags
 * @param string $email User email
 * @param int $server_tag Server ID tag
 * @param int $activ Account can be used
 * @param int $date Date in unix time format
 * @return bool
 */
function add_account($nickname, $password, $access, $flags, $email, $server_tag, $activ=1, $date=null)
{
    global $sql;
    
    $date = mktime();
    
    $q = $sql->query(
            $sql->prepare("INSERT INTO `".DB_TABLE."`
            (`auth`, `password`, `access`, `flags`, `email`, `server_tag`, `activ`, `date`, `key`)
            VALUES ('%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%s')
            ", $nickname, $password, $access, $flags, $email, $server_tag, $activ, $date, rand_string())
         );
    
    return ($sql->insert_id) ? true : false;
}


/**
 * Email is registred ?
 * 
 * @param string email
 * @return bool True if exist 
 */
function email_exist($address)
{
    global $sql;

    $q = $sql->query(
            $sql->prepare("SELECT email FROM `".DB_TABLE."` WHERE `email` = %s", $address)
         );
        
    return ($sql->num_rows() > 0) ? true : false;
}


/**
 * Find password by email
 * 
 * @param string email
 * @return string Account password 
 */
function get_pass_by_mail($email)
{
    global $sql;
    
    $q = $sql->query(
            $sql->prepare("SELECT `password`, `email` FROM `".DB_TABLE."` WHERE `email` = '%s'", $email)
         );

    return ($sql->num_rows() > 0) ? $q[1]->password : false;
}


/**
 * Send email
 * 
 * @since 1.0.0
 * 
 * @param string $to Email destination address
 * @param string $ubject Email subject
 * @param string $message Email body
 */
//TODO: refactor this ?
function send_email($to, $subject, $message)
{
    global $mail, $config;
    
    email_setup($to, $subject, $message);
        
    return ($mail->Send()) ? true : false;
}


/**
 * Generate random string
 * 
 * @since 1.0.0
 * 
 * @param int $len Number of characters
 * @return string
 */
function rand_string($len=6)
{
    $allowed = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
    $str = '';
    
    for ($i=0; $i < $len; $i++)
    {
        $str .= substr($allowed, mt_rand(0, strlen($allowed) -1), 1);
    }
    return $str;
}

function fs_write($file, $contents, $mode=0644)
{
    if ( ! ($fp = @fopen($file, 'w')) )
        return false;
    
    @fwrite($fp, $contents);
    @fclose($fp);
    return @chmod($file, $mode);
}

function set_installed()
{
    global $sql;
    
    // create table
    $q = $sql->query("CREATE TABLE `".DB_TABLE."` 
            ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, 
            `auth` varchar(32) NOT NULL DEFAULT '', 
            `password` varchar(50) NOT NULL DEFAULT '', 
            `access` varchar(50) NOT NULL DEFAULT '', 
            `flags` varchar(50) NOT NULL DEFAULT '', 
            `email` varchar(255) DEFAULT NULL, 
            `server_tag` int(2) NOT NULL, 
            `activ` int(11) DEFAULT '0', 
            `date` int(11) NOT NULL, 
            `key` varchar(6) NOT NULL, PRIMARY KEY (`id`)
            ) COMMENT = 'AMX Mod X Admins'"
         );
    if ($q === false)
        return false;
        
    // write file
    if ( fs_write(BASEPATH.'.installed', 'gentle software solutions | www.gentle.ro', 0644) === false)
        return false;
    
    return true;
}

function is_installed()
{
    if ( defined('IS_INSTALLED') )
        return true;
    elseif (is_file(BASEPATH.'.installed'))
        return true;
    else
        return false;
}
?>