<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY_Input
 */
class MY_Input extends CI_Input {

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
    
    /**
     * Check if current request is a POST
     * 
     * @access  public
     * @return  bool
     */
    public function is_post()
    {
        return $this->server('REQUEST_METHOD') == 'POST';
    }
    
    /**
     * Check if current request is a GET
     * 
     * @access  public
     * @return  bool
     */
    public function is_get()
    {
        return $this->server('REQUEST_METHOD') == 'GET';
    }
    
}
/* End of file MY_Input.php */