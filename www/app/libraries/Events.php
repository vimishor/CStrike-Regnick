<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Events
 * 
 * Offers an easy way to execute custom code, on specific application execution time 
 * 
 * @package     CStrike-Regnick
 * @subpackage  Library
 * @category    Library
 * @copyright   2012 Gentle Software Solutions
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version     1.0.0
 * @author      Alexandru G. <www.gentle.ro>
 */
class Events
{
    /**
     * Registred listeners
     * 
     * @var array
     */
    protected static $_listeners = array();
        
    // ------------------------------------------------------------------------
    
    public function __construct()
    {
        self::autoload();
    }
    
    /**
	 * Determine if an event has any registered listeners.
	 *
	 * @param  string  $event
	 * @return bool
	 */
    public static function listeners($event)
    {
        return isset(self::$_listeners[$event]);
    }
    
    /**
     * Register a callback for a given event
     * 
     * @param   string  $event      Event name
     * @param   mixed   $callback   
     * @return  void
     */
    public static function listen($event, $callback)
    {
        self::$_listeners[$event][] = $callback;
    }
        
    /**
     * Clear all event listeners for a given event.
     * 
     * @param   string  $event
     * @return  void
     */
    public static function clear($event)
    {
        unset(self::$_listeners[$event]);
    }
    
    /**
     * Trigger an event so that all listeners are called.
     * 
     * @param   string|array    $events
     * @param   array           $params
     * @param   bool            $halt       Stop execution after first valid response ?
     * @return  mixed
     */
    public static function trigger($events, $params = array(), $halt = false)
    {
        if (count(self::$_listeners)<1)
        {
            return count($params)>0 ? $params : null;
        }
        
        if (!is_array($events))
        {
            $events = array($events);
        }
        
        $responses = array();
        
        foreach ($events as $event)
        {
            if (!self::listeners($event))
            {
                return count($params)>0 ? $params : null;
            }
            
            foreach ( self::$_listeners[$event] as $callback)
            {
                if (is_callable($callback))
                {
                    $result = call_user_func_array($callback, array($params));
                    
                    if ($halt AND !is_null($result))
                    {
                        return $result;
                    }
                    
                    $responses[] = $result;
                }   
            }
        }
        return $responses;
    }
    
    /**
     * Load events file from modules
     * 
     * @return  mixed
     */
    public static function autoload()
    {
        include(APPPATH.'config/autoload.php');
        $modules = $autoload['modules'];
        
        if (count($modules)<1)
        {
            return false;
        }
        
        foreach ($modules as $module)
        {
            self::load_event_file($module);
        }
    }   
    
    /**
     * Load event file from specified module
     * 
     * @param   string  $module
     * @return  bool
     */
    protected static function load_event_file($module)
    {
        if (!is_file(MODULES_PATH.$module.'/events.php'))
        {
            return false;
        }
        
        include(MODULES_PATH.$module.'/events.php');
        return true;
    }
}

/* End of file Events.php */