<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Events Library
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
     * @return  mixed
     */
    public static function trigger($events, $params = array())
    {
        if (count(self::$_listeners)<1)
        {
            return count($params)>0 ? $params : null;
        }
        
        if (!is_array($events))
        {
            $events = array($events);
        }
        
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
                    
                    /**
                     * because data from controllers are passed to some events,
                     * we need to make sure that we return at least data that we received
                     * back to controller, so we dont brake views. 
                     */
                    return (!is_array($result) AND count($params)>0) ? $params : $result;
                }   
            }
        }
        
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