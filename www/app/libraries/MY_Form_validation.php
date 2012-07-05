<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation 
{
    
    public $caller;
    
    // ------------------------------------------------------------------------
    
    function __construct($rules = array())
	{
		parent::__construct($rules);
	}
    
    // ------------------------------------------------------------------------
    
    function run($group = '', $module = '')
    {        
        if (is_object($module))
        {
            $this->CI =& $module;   
        }
        
        if (is_object($this->caller))
        {
            $this->CI =& $this->caller;
        }
        
        return parent::run($group);
    }    
}
/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */ 