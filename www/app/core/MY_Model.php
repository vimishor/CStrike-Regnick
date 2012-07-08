<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY_Model
 */
class MY_Model extends CI_Model {

    /**
     * Error(s) holder
     * @var array
     */
    private $errors = array();
    
    // ----------------------------------------------------------------------------------------------------------
    
    /**
     * Constructor method
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    // ----------------------------------------------------------------------------------------------------------
        
    /**
	 * Set an error message
	 *
     * @access  public
     * @param   string  $error  Error message
     * @return  string   
	 */
	public function set_error($error)
	{
		$this->errors[] = $error;
        notify($this->lang->line($error), 'error'); // show error to user
        
		return $error;
	}
    
    /**
     * Get last error
     * 
     * @access  public
     * @return  string
     */
    public function get_error()
    {
        if ($this->is_error() === false)
        {
            return '';
        }
        
        return array_pop($this->errors);
    }
    
    /**
     * Check if any error has occured
     * 
     * @access  public
     * @return  bool
     */
    public function is_error()
    {
        return (count($this->errors) > 0) ? true : false;
    }
    
}
/* End of file MY_Model.php */