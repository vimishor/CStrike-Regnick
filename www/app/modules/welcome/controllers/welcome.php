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
		//$this->loadpage('welcome_message', array());
		
		$data = array(
                'page_title'    => 'Welcome to CodeIgniter!',
                'page_subtitle' => '', 
        );
		$this->template->set_layout('one_col')->build('welcome_message', $data);
	}
    public function greet($name, $times=5){
        $i=0;
        while ($i++<$times){
            echo "#$i Hello, $name <br />";
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
