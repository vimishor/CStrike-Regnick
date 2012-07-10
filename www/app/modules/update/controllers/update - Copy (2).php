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
    
    public function index($version = 0)
    {
        #$this->database($version);	
        
        var_dump($this->update_manager->is_update());
    }
    
    public function database($version = 0)
    {
        if (!is_numeric($version))
        {
            show_404();
        }
        
        $db_status = $this->update_manager->database($version);
                        
        $data = array(
                'page_title'    => 'CStrike-Regnick update',
                'page_subtitle' => '',
                'status'        => $db_status,
                'message'       => $this->update_manager->get_status(),
        );
        
        if ($db_status)
        {
            $this->template->set_layout('one_col')->build('success', $data);
        }
        else
        {
            $this->template->set_layout('one_col')->build('step_2', $data);
        }
    }
    
    //@todo: implement FS update
}

/* End of file update.php */
