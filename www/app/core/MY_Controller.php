<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY_Controller
 */
class MY_Controller extends MX_Controller {

    /**
     * Constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        parent::__construct();
                
        // If application is not installed, proceed with installation
        if ( ($this->session->userdata('installing') OR !defined('RN_INSTALLED')) AND ($this->uri->segment(1) != 'install') )
        {
             redirect('install/');
        }
        
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
        
        // backward compatible
        $this->config->set_item('site.name', get_option('site_name'));
        $this->config->set_item('register.global', get_option('register_global'));
        $this->config->set_item('webmaster.email', get_option('webmaster_email'));
        $this->config->set_item('results_per_page', get_option('results_per_page'));
        
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
        
        Events::autoload();
        
        Events::trigger('base_controller');
    }
    
    public function __destruct() { }
}

/* End of file MY_Controller.php */