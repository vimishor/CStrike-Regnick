<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY_Controller
 */
class MY_Controller extends CI_Controller {

    /**
     * Constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        
        /**
         * Profiler extended with debug-bar
         * @see https://github.com/purwandi/codeigniter-debug-bar
         */
        $this->config->load('profiler', false, true);
        if ($this->config->config['enable_profiler'])
        {
            $this->load->library('console');
            $this->output->enable_profiler(TRUE);
        }
        
        // Set some default data for forgetful devs :-)
        $data = array(
            'site_name'         => $this->config->item('site.name'),
            'register_global'   => $this->config->item('register.global'),
            'ui_tooltips'       => $this->config->item('ui.tooltips'),
            'page_title'        => '',
            'page_subtitle'     => '', 
        );

        $this->template
            ->title($data['site_name'])
            ->set_partial('header', 'partial/header')
            ->set_partial('footer', 'partial/footer')
            ->set_partial('sidebar', 'partial/sidebar')
            ->set_layout('one_col')
            ->set($data);
    }
    
    public function __destruct() { }
}
/* End of file MY_Controller.php */