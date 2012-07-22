<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{

	public function index()
	{
		$data = array(
                'page_title'    => 'Welcome to CodeIgniter!',
                'page_subtitle' => '', 
        );
		$this->template->set_layout('one_col')->build('welcome_message', $data);
	}
    
    public function greet($name, $times=5)
    {
        $i=0;
        while ($i++<$times){
            echo "#$i Hello, $name <br />";
        }
    }
}

/* End of file welcome.php */
