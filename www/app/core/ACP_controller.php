<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ACP_Controller
 */
class ACP_Controller extends MY_Controller {

    /**
     * Constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        
        // Each page served by this controller requires user to be logged in.
        if ($this->regnick_auth->logged_in() === false)
        {
            store_location();
            redirect('ucp/login', 'refresh');
        }
        
        // Each page served by this controller requires user to have `administrator` access.
        if ($this->regnick_auth->isOwner($this->session->userdata('user_id')) === false)
        {
            notify($this->lang->line('insuficient_access'), 'success');
            redirect('', 'refresh');
        }
    }
    
}
/* End of file MY_Controller.php */