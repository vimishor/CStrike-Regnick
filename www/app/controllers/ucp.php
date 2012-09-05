<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This file is part of the CStrike-Regnick package
 * 
 * (c) Gentle Software Solutions <www.gentle.ro>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// ------------------------------------------------------------------------

/**
 * Ucp controller
 * 
 * User control panel
 * 
 * @package     CStrike-Regnick
 * @category    Controllers
 * @copyright   (c) 2011 - 2012 Gentle Software Solutions
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @link        http://www.gentle.ro/ 
 */
class Ucp extends MY_Controller
{
	
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Index page
     * 
     * @access  public
     * @return  void
     */
	public function index()
	{
        if ($this->regnick_auth->logged_in() === false)
        {
            redirect('ucp/login', 'refresh');
        }
        
        redirect('ucp/dashboard', 'refresh');
	}
    
    /**
     * Confirm new account
     * 
     * @access  public
     * @return  void
     */
    public function confirm($key)
    {
        if ($this->user_model->activate_account($key) === true)
        {
            console_log('Register OK');
            notify($this->lang->line('account_confirmed'), 'success');
            redirect('ucp/login', 'refresh');
        }
        else
        {
            console_log('Registration error: '.$this->regnick_auth->get_error());
            notify($this->lang->line('account_confirmed_error'), 'error');
            redirect('', 'refresh');
        }
    }
    
    /**
     * User registration
     * 
     * @access  public
     * @return  void
     */
    public function register()
    {
        if ($this->regnick_auth->logged_in())
        {
            redirect('ucp/dashboard', 'refresh');
        }
        
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('regnick_auth');
        
        if ($this->form_validation->run('ucp-register') === true) // process form
        {
            
            if ($this->regnick_auth->user_add(
                $this->input->post('username'), $this->input->post('email'), $this->input->post('email-conf'), 
                $this->input->post('password'), $this->input->post('password-conf'),
                $this->input->post('group'), $this->input->post('server') ) === true)
            {
                // register successful.
                console_log('Register OK');
                
                /**
                 * If email is not configured, register user anyway, even if `register_confirmation`
                 * option is activated.
                 */
                if ( (get_option('register_confirmation') == '1') AND (can_send_email() === true) )
                {
                    notify($this->lang->line('account_created_validation'), 'success');

                    // send email
                    $this->load->helper('email');
                    $key = $this->user_model->getRow($this->input->post('username'), 'activation_key');
                    $message = prep_email_message('email/registration', 
                        array(
                        
                            'site_name'         => $this->config->item('site.name'), 
                            'activation_code'   => $key,
                            'activation_link'   => site_url('ucp/confirm/'.$key),
                        )
                    );
                    send_email($this->user_model->getRow($this->input->post('username'), 'email'), 'Confirm registration', $message);
                    
                }
                else
                {
                    notify($this->lang->line('account_created'), 'success');
                }
                                
                redirect('', 'refresh');
            }
            else
            {
                console_log('Registration error: '.$this->regnick_auth->get_error());
                redirect('ucp/register', 'refresh');
            }
        }
        else
        {
            $this->load->model('group_model');
            $this->load->model('server_model');
            
            $pub_groups         = $this->group_model->get_groups(true);
            $server_list        = $this->server_model->getServers( (bool)$this->config->item('register.global') );
            $groups = $servers  = array();
            
            if ($pub_groups AND count($pub_groups>0))
            {
                // filter groups
                // @todo: refactor this
                foreach ($pub_groups as $group)
                {
                    $groups[$group->ID] = $group->name;
                }
                
                // filter servers
                // @todo: refactor this
                foreach ($server_list as $server)
                {
                    $servers[$server->ID] = $server->address;
                }
            }
            
            // spring cleaning
            unset($pub_groups, $server_list);
                        
            $data = array(
                'page_title'    => lang('register'),
                'page_subtitle' => '', 
                'groups'        => $groups,
                'servers'       => $servers, 
                
                'form_username' => array(
                    'class'     => 'input-xlarge',
                    'name'      => 'username',
               	    'id'        => 'username',
                    'maxlength' => 80,
                ),
                'form_email' => array(
                    'class'     => 'input-xlarge',
                    'name'      => 'email',
               	    'id'        => 'email',
                    'maxlength' => 80,
                ),
                'form_email_conf' => array(
                    'class'     => 'input-xlarge',
                    'name'      => 'email-conf',
               	    'id'        => 'email-conf',
                    'maxlength' => 80,
                ),
                'form_password' => array(
                    'class'     => 'input-xlarge',
                    'name'      => 'password',
                    'id'        => 'password',
                ),
                'form_passsword_conf' => array(
                    'class'     => 'input-xlarge',
                    'name'      => 'password-conf',
                    'id'        => 'password-conf',
                ),
            );
            
            $this->template->set_layout('one_col')->build('ucp/register', $data);
        }
    }
    
    /**
     * User settings
     * 
     * @access  public
     * @return  void
     */
    public function settings()
    {
        if ($this->regnick_auth->logged_in() === false)
        {
            redirect('ucp/login', 'refresh');
        }
        
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
                
        if ($this->form_validation->run('ucp-settings') === true) // process form
        {
            if ($this->regnick_auth->ucp_settings($this->session->userdata('user_id')) === true)
            {
                console_log('OK');
                notify($this->lang->line('data_saved'), 'success');
                redirect('ucp/settings', 'refresh');
            }
            else
            {
                $error = $this->user_model->get_error();
                console_log('Error: '.$error);
                notify($this->lang->line($error), 'error');
                redirect('ucp/settings', 'refresh');
            }
        }
        else
        {
            $data = array(
                    'page_title'    => lang('account_settings'),
                    'page_subtitle' => '',
                    
                    'form_email' => array(
                        'class' => 'input-xlarge',
                        'name'	=> 'email',
                    	'id'	=> 'email',
                    	'value' => $this->user_model->getRow($this->session->userdata('user_id'), 'email'),
                    	'maxlength'	=> 80,
                    ),
            );
            
            $this->template->set_layout('two_col')->build('ucp/settings', $data);
        }
        
    }
    
    /**
     * Change account password
     * 
     * @access  public
     * @return  void
     */
    public function password()
    {
        if ($this->regnick_auth->logged_in() === false)
        {
            store_location();
            redirect('ucp/login', 'refresh');
        }
        
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
                        
        if ($this->form_validation->run('ucp-password') === true) // process form
        {
            if ($this->regnick_auth->change_pass($this->input->post('password_a'), $this->input->post('password_b'), $this->input->post('password_c')))
            {
                console_log('OK');
                redirect('ucp/password', 'refresh');
            }
            else
            {
                console_log('Error: '.$this->regnick_auth->get_error());
                redirect('ucp/password', 'refresh');
            }
        }
        else
        {
        
            $data = array(
                'page_title'    => lang('change_password'),
                'page_subtitle' => '',
                
                'form_password_a' => array(
                    'class' => 'input-xlarge',
                    'name'	=> 'password_a',
                    'id'	=> 'password_a',
                ),
                'form_password_b' => array(
                    'class' => 'input-xlarge',
                    'name'	=> 'password_b',
                    'id'	=> 'password_b',
                ),
                'form_password_c' => array(
                    'class' => 'input-xlarge',
                    'name'	=> 'password_c',
                    'id'	=> 'password_c',
                ),
            );
            
            $this->template->set_layout('two_col')->build('ucp/password', $data);
        }
        
    }
    
    /**
     * UCP dashboard
     * 
     * @access  public
     * @return  void
     */
    public function dashboard()
    {
        if ($this->regnick_auth->logged_in() === false)
        {
            store_location();
            redirect('ucp/login', 'refresh');
        }
        
        $user_id    = $this->session->userdata('user_id');
        $user       = $this->user_model->getSettings($user_id);
                
        $stats = array(
            'member_since'  => date('d/m/Y', $user->register_date),
            'email'         => $user->email,
            'is_owner'      => $this->regnick_auth->isOwner($this->session->userdata('user_id'))
        );
        
        $data = array(
            'page_title'    => lang('user_dashboard'),
            'page_subtitle' => lang('account_overview'),
            'stats'         => $stats,
            'membership'    => $this->user_model->getAllAccess($user_id, true),
        );
        
        $this->template->set_layout('two_col')->build('ucp/dashboard', $data);
    }
    
    /**
     * Logout user
     * 
     * @access  public
     * @return  void
     */
    public function logout()
    {
        Events::trigger('user_logged_out', array('user_id', $this->session->userdata('user_id')) );
        $this->regnick_auth->user_logout();
        
        redirect('', 'refresh');
    }
    
    /**
     * Login user
     * 
     * @access  public
     * @return  void
     */
    public function login()
    {
        if ($this->regnick_auth->logged_in())
        {
            redirect('ucp/dashboard', 'refresh');
        }

        $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
        
        if ($this->form_validation->run('ucp-login') === true) // process form
        {            
            if ($this->regnick_auth->user_login($this->input->post('username'), $this->input->post('password'), (bool) $this->input->post('remember') ) )
            {
                // login successful.
                console_log('Login OK');
                
                Events::trigger('user_logged_in', array('user_id', $this->session->userdata('user_id')) );
                
                if ($continue = $this->session->userdata('continue'))
                {
                    $this->session->unset_userdata('continue');
                    redirect($continue);
                }
                redirect('ucp/dashboard', 'refresh');
            }
            else
            {
                // login was un-successful
                console_log('Invalid login: '.$this->regnick_auth->get_error());
                redirect('ucp/login', 'refresh');
            }
        }
        else
        {
            
            // not logged in, show the form
            $data = array(
                'page_title'    => lang('login'),
                'page_subtitle' => '',
                
                'form_username' => array(
                    'class' => 'input-xlarge',
                    'name'	=> 'username',
                	'id'	=> 'username',
                	'maxlength'	=> 80,
                ),
                'form_password' => array(
                    'class' => 'input-xlarge',
                    'name'	=> 'password',
                	'id'	=> 'password',
                ),
                'form_remember' => array(
                    'name'      => 'remember',
                    'id'        => 'remember',
                    'value'     => 1,
                    'checked'   => false,
                ),
            );
            
            $this->template->set_layout('one_col')->build('ucp/login', $data);
        }
        
        
    }
    
    /**
     * Recover account
     * 
     * @access  public
     * @return  void
     */
    public function recover()
    {
        if ($this->regnick_auth->logged_in())
        {
            redirect('ucp/dashboard', 'refresh');
        }
        
        $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
        
        if ($this->form_validation->run('ucp-recover') === true) // process form
        {
            if ($this->regnick_auth->account_recover($this->input->post('email')) )
            {
                // process successful.
                console_log('Recover OK');
                redirect('ucp/login', 'refresh');
            }
            else
            {
                console_log('Recover error: '.$this->regnick_auth->get_error());
                redirect('ucp/recover', 'refresh');
            }
        }
        else
        {            
            // not logged in, show the form
            $data = array(
                'page_title'    => lang('recover_account'),
                'page_subtitle' => lang('recover_lost_password'),
                
                'form_email' => array(
                    'class'     => 'input-xlarge',
                    'name'      => 'email',
               	    'id'        => 'email',
                    'maxlength' => 80,
                ),
            );
            
            $this->template->set_layout('one_col')->build('ucp/recover', $data);
        }
        
    }
    
}

/* End of file ucp.php */
/* Location: ./application/controllers/ucp.php */