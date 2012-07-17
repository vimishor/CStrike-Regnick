<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Check if an DB update is available and show
 * alert when ACP dashboard is accessed.
 * 
 * @package     CStrike-Regnick
 * @subpackage  Update
 * @author      Alexandru G. ( www.gentle.ro )
 */
class Update_Event
{
    protected static $_ci;
    
    // ------------------------------------------------------------------------
    
    public static function run_update()
    {
        if (!$last_update = get_option('update_last_run'))
        {
            set_option('update_last_run', time());
        }
        
        if (time() > ((int)$last_update+60))
        {
            self::$_ci = & get_instance();
            self::$_ci->load->library('migration');
            self::is_update();
        }        
    }
    
    protected static function is_update()
    {
        $db_update      = self::$_ci->migration->db_update_available();
        
        store_location();
        
        // notify user only if an update is available.
        if ($db_update)
        {
            notify('Database update available. [ <a href="'. site_url('update/database') .'">update now</a> ]', 'info');
        }
        
        set_option('update_last_run', time());
        redirect('');
    }
}

Events::listen('acp_dashboard', 'Update_Event::run_update');
