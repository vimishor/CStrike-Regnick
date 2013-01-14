<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Check if an DB update is available and show
 * alert when ACP dashboard is accessed.
 * 
 * @package     CStrike-Regnick
 * @subpackage  Update
 * @author      Alexandru G. ( www.gentle.ro )
 */
class Napkin_Event
{
    protected static $_ci;
    
    // ------------------------------------------------------------------------
    
    public static function update_menu()
    {
        self::$_ci = & get_instance();
        self::$_ci->navigation->add_item('Maintenance', site_url('napkin/'), 'owner');
    }
}
Events::listen('acp_controller', 'Napkin_Event::update_menu');
