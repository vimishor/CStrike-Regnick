<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Update application
 */
class Update extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        
        $this->load->library('update_manager');
    }
    
    public function index()
    {    
        $db_update      = $this->migration->db_update_available();
        
        if ($db_update)
        {
            notify('Database update available. [ <a href="'. site_url('update/database') .'">update now</a> ]', 'info');
        }
        else
        {
            notify('Dabase is up to date.', 'success');
        }
        
        redirect('');
    }
    
    public function database()
    {
        if ( $this->migration->db_update_available() )
        {
            $this->update_manager->database();
            notify($this->update_manager->get_status());
        }
        
        redirect('');
    }
    
    //@todo: implement FS update
}

/* End of file update.php */
