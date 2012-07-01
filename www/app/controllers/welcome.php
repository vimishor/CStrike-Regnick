<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		//$this->load->view('welcome_message');
        
        /*$this->template
        	// application/views/some_folder/header
        	->set_partial('header', 'partial/header')
        
        	// application/views/layouts/two_col.php
        	->set_layout('two_col')
        
        	// views/welcome_message
        	->build('welcome_message');*/
        
        # $this->template->build('welcome_message');
        //$this->template->set_partial('header', 'partial/header');
        #$this->template->title('Blog', 'bau');
        #$this->template->build('welcome_message');
        
        $this->template->set_layout('one_col')->build('frontpage');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */