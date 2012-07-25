<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Update application
 */
class Update extends ACP_Controller {

	public function __construct()
    {
        parent::__construct();
        
        $this->load->library('Update_Manager');
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
        
        $this->release();
    }
    
    /**
     * Check if a new release version is available on github downloads
     * 
     * @access  public
     * @return  void
     */
    public function release()
    {
        if ($this->update_manager->check_new_release())
        {
            notify('A new version of CStrike-Regnick is available. Please update as soon as posible. <br> 
                    Visit <a href="http://www.gentle.ro/proiecte/cstrike-regnick/">official page</a> for more informations.', 'info');
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
