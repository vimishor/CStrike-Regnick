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
 * Acp controller
 * 
 * Administration control panel
 * 
 * @package     CStrike-Regnick
 * @category    Controllers
 * @copyright   (c) 2011 - 2012 Gentle Software Solutions
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @link        http://www.gentle.ro/ 
 */
class Acp extends ACP_Controller
{
	
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Application settings
     * 
     * @access  public
     * @return  void
     */
    public function settings()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        
        if ($this->form_validation->run('acp-settings') === true) // process form
        {
            $data = array(
                array(
                    'name'  => 'site_name', 
                    'value' => $this->input->post('site_name', TRUE)
                ),
                array(
                    'name'  => 'webmaster_email', 
                    'value' => $this->input->post('webmaster_email', TRUE)
                ),
                array(
                    'name'  => 'results_per_page', 
                    'value' => $this->input->post('results_per_page', TRUE)
                ),
                array(
                    'name'  => 'register_global', 
                    'value' => $this->input->post('register_global', TRUE)
                ),
                array(
                    'name'  => 'register_confirmation', 
                    'value' => $this->input->post('register_confirmation', TRUE)
                ),
                
            );
                                
            if ($this->core_model->set_options($data) === true)
            {
                notify($this->lang->line('data_saved'), 'success');
                redirect('acp/settings', 'refresh');
            }
            else
            {
                notify($this->lang->line('error_on_save'), 'error');
                redirect('acp/settings', 'refresh');
            }
        }
        else
        {
            $data = array(
                'page_title'    => lang('admin_settings'),
                'page_subtitle' => 'Change application settings',
                
                'form_site_name' => array(
                    'class' => 'input-xlarge',
                    'name'	=> 'site_name',
               	    'id'	=> 'site_name',
               	    'value' => get_option('site_name'),
               	    'maxlength'	=> 100,
                ),
                
                'form_webmaster_email' => array(
                    'class' => 'input-xlarge',
                    'name'	=> 'webmaster_email',
               	    'id'	=> 'webmaster_email',
               	    'value' => get_option('webmaster_email'),
               	    'maxlength'	=> 120,
                ),
                
                'form_results_per_page' => array(
                    'class' => 'input-xlarge',
                    'name'	=> 'results_per_page',
               	    'id'	=> 'results_per_page',
               	    'value' => get_option('results_per_page'),
               	    'maxlength'	=> 3,
                ),
                
                'ckbox_register_global' => array(
                    'name'      => 'register_global',
                    'id'        => 'register_global',
                    'value'     => '1',
                    'checked'   => (get_option('register_global') == 1) ? true : false,
                ),
                'ckbox_register_confirmation' => array(
                    'name'      => 'register_confirmation',
                    'id'        => 'register_confirmation',
                    'value'     => '1',
                    'checked'   => (get_option('register_confirmation') == 1) ? true : false,
                ),
                
            );
            
            Events::trigger('acp_settings', $data);
            
            $this->template->set_layout('two_col')->build('acp/settings', $data);
            
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
        // fetch data for `security` section
        $security = array(
            'key'   => ( strtoupper($this->config->item('encryption_key')) != 'CHANGE-ME') ? true : false,
            'xss'   => ($this->config->item('global_xss_filtering')),
            'csrf'  => ($this->config->item('csrf_protection')),
        );
        
        // fetch data for `speed` section
        $speed = array(
            'logs'  => ($this->config->item('log_threshold')>0) ? false : true,
            'gzip_output'  => ($this->config->item('compress_output')),
        );
        
        // fetch data for `stats` section
        $stats = array_merge($this->core_model->get_options(array('app_version', 'db_version')),
            array(
                'registred_users'   => $this->db->count_all('users'),
                'registred_servers' => $this->db->count_all('servers'),
                'mysql_version'     => $this->db->version(),
                'php_version'       => PHP_VERSION,
            )
        );
                
        $data = array(
            'page_title'    => lang('admin_dashboard'),
            'page_subtitle' => 'Application overview',
            'stats'         => $stats,
            'security'      => $security,
            'speed'         => $speed,
        );
        
        Events::trigger('acp_dashboard', $data);
        
        $this->template->set_layout('two_col')->build('acp/dashboard', $data);
    }
    
    /**
     * Index page
     * 
     * @access  public
     * @return  void
     */
    
	public function index()
	{
        redirect('ucp/dashboard', 'location', 301);
	}
    
    // ----------------------------------------------------------------------------------------------------------
    
    /**
     * Delete specified server
     * 
     * @access  public
     * @return  void
     */
    public function server_delete()
    {
        if (is_dev())
        {
            $this->output->enable_profiler(FALSE);
        }
        $response = array('status' => 'error', 'message' => 'Error saving new settings.');
        
        if ($this->input->is_post())
        {
            $this->load->model('server_model');
            $serverID = (int) $this->input->post('server_ID');
            
            if ($this->server_model->delServer($serverID))
            {
                $response = array('status' => 'ok', 'message' => 'Settings successfully saved.');
            }
        }
        
        echo json_encode($response);
    }
    
    /**
     * Edit a server
     * 
     * @access  public
     * @return  void
     */
    public function server_edit($serverID)
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('server_model');
        
        if ($this->form_validation->run('acp-server-add') === true) // process form
        {
            if ($this->server_model->saveServer($serverID, $this->input->post('s-address'), $this->input->post('s-name')))
            {
                console_log('OK');
                notify($this->lang->line('data_saved'), 'success');
                redirect('acp/server/list', 'refresh');
            }
            else
            {
                $error = $this->server_model->get_error();
                console_log('Error: '.$error);
                notify($this->lang->line($error), 'error');
                redirect('acp/server/edit/'.$serverID, 'refresh');
            }
        }
        else
        {
            $server = $this->server_model->getServer($serverID);
            
            $data = array(
                    'page_title'    => lang('edit_server'),
                    'page_subtitle' => '',
                    
                    'input_name' => array(
                        'class'     => 'input-xlarge',
                        'name'      => 's-name',
                        'id'        => 's-name',
                        'value'     => $server->name,
                    	'maxlength'	=> 60,
                    ),
                    
                    'input_address' => array(
                        'class'     => 'input-xlarge',
                        'name'      => 's-address',
                        'id'        => 's-address',
                        'value'     => $server->address,
                    	'maxlength'	=> 30,
                    ),
            );
            
            $this->template->set_layout('two_col')->build('acp/server/add', $data);
        }
    }
    
    /**
     * Add a new server
     * 
     * @access  public
     * @return  void
     */
    public function server_add()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('server_model');
        
        if ($this->form_validation->run('acp-server-add') === true) // process form
        {
            if ($this->server_model->addServer($this->input->post('s-address'), $this->input->post('s-name')))
            {
                console_log('OK');
                notify($this->lang->line('data_saved'), 'success');
                redirect('acp/server/list', 'refresh');
            }
            else
            {
                $error = $this->server_model->get_error();
                console_log('Error: '.$error);
                notify($this->lang->line($error), 'error');
                redirect('acp/server/add', 'refresh');
            }
        }
        else
        {
            $data = array(
                    'page_title'    => lang('add_server'),
                    'page_subtitle' => '',
                    
                    'input_name' => array(
                        'class' => 'input-xlarge',
                        'name'	=> 's-name',
                    	'id'	=> 's-name',
                    	'maxlength'	=> 60,
                    ),
                    
                    'input_address' => array(
                        'class' => 'input-xlarge',
                        'name'	=> 's-address',
                    	'id'	=> 's-address',
                    	'maxlength'	=> 30,
                    ),
            );
            
            $this->template->set_layout('two_col')->build('acp/server/add', $data);
        }
    }
    
    /**
     * Server list
     * 
     * @access  public
     * @return  void
     */
    public function server_list($page = 0, $search = '')
    {        
        $this->load->model('server_model');
        
        $this->load->library('pagination');
        $config['per_page'] = $this->config->item('results_per_page');
        
        if ($search != '')
        {   
            $config['base_url']     = base_url().'/acp/server/search/'.$search;
            $config['total_rows']   = $this->db->like('address', $search)->count_all_results('servers');
            $config['uri_segment']  = 5;
        }
        else
        {
            $config['base_url']     = base_url().'/acp/server/list/';
            $config['total_rows']   = $this->db->count_all('servers');
            $config['uri_segment']  = 4;
        }
        $this->pagination->initialize($config);
        
        $data = array(
            'page_title'    => lang('available_servers'),
            'page_subtitle' => '',
            'servers'       => $this->server_model->getServers(false, $config['per_page'], $page, 'obj', $search),
            'show_search'   => 'server'
        );
            
        $this->template->set_layout('two_col')->build('acp/server/list', $data);        
    }
    
    /**
     * Prepare a server search
     * 
     * @access  public
     * @return  void
     */
    public function pre_server_search($page = 0, $search)
    {
        redirect('acp/server/search/'.strtolower($this->input->post('server')).'/'.(int)$page);
    } 
    
    // ----------------------------------------------------------------------------------------------------------
    
    /**
     * Delete specified group
     * 
     * @access  public
     * @return  void
     */
    public function group_delete()
    {
        if (is_dev())
        {
            $this->output->enable_profiler(FALSE);
        }
        $response = array('status' => 'error', 'message' => 'Error saving new settings.');
        
        if ($this->input->is_post())
        {
            $this->load->model('group_model');
            $groupID = (int) $this->input->post('group_ID');
            
            if ($this->group_model->delGroup($groupID))
            {
                $response = array('status' => 'ok', 'message' => 'Settings successfully saved.');
            }
        }
        
        echo json_encode($response);
    }
    
    /**
     * Edit a group
     * 
     * @access  public
     * @return  void
     */
    public function group_edit($groupID)
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('group_model');
        
        if ($this->form_validation->run('acp-group-edit') === true) // process form
        {
            
            if ($this->group_model->saveGroup($groupID, $this->input->post('g-name'), $this->input->post('g-access'), $this->input->post('g-public')))
            {
                console_log('OK');
                notify($this->lang->line('data_saved'), 'success');
                redirect('acp/group/edit/'.$groupID, 'refresh');
            }
            else
            {
                console_log('Error: '.$this->group_model->get_error());
                notify($this->lang->line('error_on_save'), 'error');
                redirect('acp/group/edit/'.$groupID, 'refresh');
            }
        }
        else
        {
            $group = $this->group_model->getGroup($groupID);
            
            $data = array(
                    'page_title'    => lang('edit_group'),
                    'page_subtitle' => '',
                    
                    'input_name' => array(
                        'class'     => 'input-xlarge',
                        'name'      => 'g-name',
                        'id'        => 'g-name',
                        'value'     => $group->name,
                    	'maxlength'	=> 60,
                    ),
                    
                    'input_flags' => array(
                        'class'     => 'input-xlarge',
                        'name'      => 'g-access',
                        'id'        => 'g-access',
                        'value'     => $group->access,
                        'maxlength'	=> 30,
                    ),
                    
                    'ckbox_public' => array(
                        'name'      => 'g-public',
                        'id'        => 'g-public',
                        'value'     => 'accept',
                        'checked'   => ($group->public == 1) ? true : false,
                    ),
            );
            
            $this->template->set_layout('two_col')->build('acp/group/edit', $data);
        }
    }
    
    /**
     * Add a new group
     * 
     * @access  public
     * @return  void
     */
    public function group_add()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('group_model');
        
        if ($this->form_validation->run('acp-group-add') === true) // process form
        {
            if ($this->group_model->addGroup($this->input->post('g-name'), $this->input->post('g-access'), $this->input->post('g-public')))
            {
                console_log('OK');
                notify($this->lang->line('group_added'), 'success');
                redirect('acp/group/list', 'refresh');
            }
            else
            {
                console_log('Error: '.$this->regnick_auth->get_error());
                notify($this->lang->line('error_on_save'), 'error');
                redirect('acp/group/add', 'refresh');
            }
        }
        else
        {
            $data = array(
                    'page_title'    => lang('add_group'),
                    'page_subtitle' => '',
                    
                    'input_name' => array(
                        'class' => 'input-xlarge',
                        'name'	=> 'g-name',
                    	'id'	=> 'g-name',
                    	'maxlength'	=> 60,
                    ),
                    
                    'input_flags' => array(
                        'class' => 'input-xlarge',
                        'name'	=> 'g-access',
                    	'id'	=> 'g-access',
                    	'maxlength'	=> 30,
                    ),
                    
                    'ckbox_public' => array(
                        'name'      => 'g-public',
                        'id'        => 'g-public',
                        'value'     => 'accept',
                        'checked'   => false,
                    ),
            );
            
            $this->template->set_layout('two_col')->build('acp/group/add', $data);
        }
    }
    
    /**
     * Groups list
     * 
     * @access  public
     * @return  void
     */
    public function group_list($page = 0, $search = '')
    {        
        $this->load->model('group_model');
        
        $this->load->library('pagination');
        $config['per_page'] = $this->config->item('results_per_page');
        
        if ($search != '')
        {   
            $config['base_url']     = base_url().'/acp/group/search/'.$search;
            $config['total_rows']   = $this->db->like('name', $search)->count_all_results('groups');
            $config['uri_segment']  = 5;
        }
        else
        {
            $config['base_url']     = base_url().'/acp/group/list/';
            $config['total_rows']   = $this->db->count_all('groups');
            $config['uri_segment']  = 4;
        }
        $this->pagination->initialize($config);
        
        $data = array(
            'page_title'    => lang('available_groups'),
            'page_subtitle' => '',
            'groups'        => $this->group_model->get_groups(false, $config['per_page'],$page, $search),
            'show_search'   => 'groups'
        );
            
        $this->template->set_layout('two_col')->build('acp/group/list', $data);        
    }
    
    /**
     * Prepare a user search
     * 
     * @access  public
     * @return  void
     */
    public function pre_group_search($page = 0, $search)
    {
        redirect('acp/group/search/'.strtolower($this->input->post('group')).'/'.(int)$page);
    }    
        
    // ----------------------------------------------------------------------------------------------------------
    
    /**
     * Delete user access on specified server
     * 
     * @access  public
     * @return  void
     */
    public function user_access_del()
    {
        if (is_dev())
        {
            $this->output->enable_profiler(FALSE);
        }
        $response = array('status' => 'error', 'message' => 'Error saving new settings.');
        
        if ($this->input->is_post())
        {
            $this->load->model('server_model');
            
            $userID     = (int)$this->input->post('user_ID');
            $serverID   = (int)$this->input->post('server_ID');
                        
            if ($this->user_model->delAccess($userID, $serverID))
            {
                $response = array('status' => 'ok', 'message' => 'Settings successfully saved.');
            }
        }
        
        echo json_encode($response);
    }
    
    /**
     * Edit user access on specified server
     * 
     * @access  public
     * @return  void
     */
    public function user_access($userID, $serverID)
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('group_model');
        $this->load->model('server_model');
        $this->load->model('user_model');
        
        if ($this->form_validation->run('acp-user-acc-save') === true) // process form
        {           
            if ($this->user_model->saveAccess($userID, $serverID, (int)$this->input->post('user_group')) === true )
            {
                console_log('OK');
                notify($this->lang->line('data_saved'), 'success');
            }
            else
            {
                $error = $this->user_model->is_error() ? $this->user_model->get_error() : 'error_on_save';
                console_log('Error: '.$error);
                notify($this->lang->line($error), 'error');
            }
            redirect('acp/user/'.$userID.'/access/'.$serverID, 'refresh');
        }
        else
        {
            $data = array(
                'page_title'    => lang('change_access'),
                'page_subtitle' => '',
                'groups'        => $this->group_model->get_groups(false),
                'serverName'    => $this->server_model->getServer($serverID)->address,
                'userName'      => $this->user_model->getUsername($userID),
                'userGroup'     => $this->group_model->getUserGroup($userID, $serverID),
                'userID'        => $userID,            
            );
            
            $this->template->set_layout('two_col')->build('acp/user/access', $data);   
        }
    }
    
    // ----------------------------------------------------------------------------------------------------------
    
    /**
     * Edit a user
     * 
     * @access  public
     * @return  void
     */
    public function user_edit($userID)
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('server_model');
        
        if ($this->form_validation->run('acp-user-edit') === true) // process form
        {
            $login = $this->input->post('login');
            $flags = $this->user_model->checkFlags($login, $this->input->post('user_flags_b'), $this->input->post('user_flags_a'), $this->input->post('user_flags_c'));
            
            if ( $this->user_model->saveUser($userID, $login, $this->input->post('passwd'), $this->input->post('email'), 
                (bool)$this->input->post('active'), $flags, $this->input->post('notes') ) === true)
            {
                console_log('OK');
                notify($this->lang->line('data_saved'), 'success');
                redirect('acp/user/edit/'.$userID, 'refresh');
            }
            else
            {
                $error = $this->user_model->get_error();
                console_log('Error: '.$error);
                notify($this->lang->line($error), 'error');
                redirect('acp/user/edit/'.$userID, 'refresh');
            }
        }
        else
        {
            $user       = $this->user_model->getSettings($userID);
            $servers    = $this->server_model->getServers(true, 200, 0, 'arr');
            
            $data = array(
                    'page_title'    =>  'Edit account',  
                    'page_subtitle' =>  '',      
                    'no_access'     =>  $this->user_model->match_access($userID, $servers), // TODO: refactor this
                    'userID'        =>  $userID,
                    'servers'       =>  array_merge_recursive_distinct($servers, 
                                            $this->user_model->change_key_id(
                                                $this->user_model->getAllAccess($userID, true)
                                            )
                                        ),
                    
                    'input_login' => array(
                        'class'     => 'input-xlarge',
                        'name'      => 'login',
                        'id'        => 'login',
                        'value'     => $user->login,
                    	'maxlength' => 60,
                    ),
                    
                    'input_passwd' => array(
                        'class'         => 'input-xlarge',
                        'name'          => 'passwd',
                        'id'            => 'passwd',
                        'placeholder'   => 'Only if you wish to change it',
                    ),
                    
                    'input_email' => array(
                        'class'         => 'input-xlarge',
                        'name'          => 'email',
                        'id'            => 'email',
                        'placeholder'   => $user->email,
                    ),
                    'txt_notes' => array(
                        'class'         => 'input-xlarge',
                        'name'          => 'notes',
                        'id'            => 'notes',
                        'value'         => $user->notes,
                    ),
                    
                    'ckbox_public' => array(
                        'name'      => 'active',
                        'id'        => 'active',
                        'value'     => 'accept',
                        'checked'   => ($user->active == 1) ? true : false,
                    ),
                    
                    
                    'radio_none1' => array(
                        'name'      => 'user_flags_b',
                        'id'        => 'user_flags_b',
                        'value'     => '',
                        'checked'   => ($this->regnick_auth->hasFlag($userID, 'b') === false) ? true : false,
                    ),
                    'radio_b' => array(
                        'name'      => 'user_flags_b',
                        'id'        => 'user_flags_b',
                        'value'     => 'b',
                        'checked'   => $this->regnick_auth->hasFlag($userID, 'b'),
                    ),
                    'radio_c' => array(
                        'name'      => 'user_flags_b',
                        'id'        => 'user_flags_b',
                        'value'     => 'c',
                        'checked'   => $this->regnick_auth->hasFlag($userID, 'c'),
                    ),
                    'radio_d' => array(
                        'name'      => 'user_flags_b',
                        'id'        => 'user_flags_b',
                        'value'     => 'd',
                        'checked'   => $this->regnick_auth->hasFlag($userID, 'd'),
                    ),
                    
                    'radio_a' => array(
                        'name'      => 'user_flags_a',
                        'id'        => 'user_flags_a',
                        'value'     => 'a',
                        'checked'   => $this->regnick_auth->hasFlag($userID, 'a'),
                    ),
                    'radio_e' => array(
                        'name'      => 'user_flags_a',
                        'id'        => 'user_flags_a',
                        'value'     => 'e',
                        'checked'   => $this->regnick_auth->hasFlag($userID, 'e'),
                    ),
                    'radio_f' => array(
                        'name'      => 'user_flags_c',
                        'id'        => 'user_flags_c',
                        'value'     => 'f',
                        'checked'   => $this->regnick_auth->hasFlag($userID, 'f'),
                    ),
                    'radio_none' => array(
                        'name'      => 'user_flags_c',
                        'id'        => 'user_flags_c',
                        'value'     => '',
                        'checked'   => (!$this->regnick_auth->hasFlag($userID, 'f')) ? true : false
                    ),
            );
            
            $this->template->set_layout('two_col')->build('acp/user/edit', $data);
        }
    }
    
    /**
     * Add a new user
     * 
     * @access  public
     * @return  void
     */
    public function user_add()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('regnick_auth');
        
        if ($this->form_validation->run('acp-user-add') === true) // process form
        {
            $login          = $this->input->post('login');
            $is_owner       = (bool)strpos($this->input->post('user_flags_c'), 'f');
            $is_clan_tag    = (bool)strpos($this->input->post('user_flags_b'), 'b');
            $flags          = $this->regnick_auth->build_account_flags($login, array(
                                'is_owner'      => $is_owner,
                                'is_clan_tag'   => $is_clan_tag
                            ));
            
            if ($this->user_model->user_add($login, $this->input->post('passwd'), $this->input->post('email'), (bool)$this->input->post('active'), $flags ))
            {
                console_log('OK');
                notify($this->lang->line('account_created'), 'success');
                redirect('acp/user/list', 'refresh');
            }
            else
            {
                console_log('Error: '.$this->regnick_auth->get_error());
                notify($this->lang->line('error_on_save'), 'error');
                redirect('acp/user/add', 'refresh');
            }
        }
        else
        {
            $data = array(
                    'page_title'    => lang('add_user'),
                    'page_subtitle' => '',
                    
                    'input_login' => array(
                        'class'     => 'input-xlarge',
                        'name'      => 'login',
                        'id'        => 'login',
                    	'maxlength' => 60,
                    ),
                    
                    'input_passwd' => array(
                        'class'     => 'input-xlarge',
                        'name'      => 'passwd',
                        'id'        => 'passwd',
                    ),
                    
                    'input_email' => array(
                        'class'     => 'input-xlarge',
                        'name'      => 'email',
                        'id'        => 'email',
                    ),
            );
            
            $this->template->set_layout('two_col')->build('acp/user/add', $data);
        }
    }
    
    /**
     * Prepare a user search
     * 
     * @access  public
     * @return  void               
     */
    public function pre_user_search($page = 0, $search)
    {
        redirect('acp/user/search/'.strtolower($this->input->post('user')).'/'.(int)$page);
    }
    
    /**
     * User list
     * 
     * @access  public
     * @return  void
     */
    public function users_list($page = 0, $search = '')
    {        
        
        $this->load->library('pagination');
        $config['per_page'] = $this->config->item('results_per_page');
        
        if ($search != '')
        {   
            $config['base_url']     = base_url().'/acp/user/search/'.$search;
            $config['total_rows']   = $this->db->like('login', $search)->count_all_results('users');
            $config['uri_segment']  = 5;
        }
        else
        {
            $config['base_url']     = base_url().'/acp/user/list/';
            $config['total_rows']   = $this->db->count_all('users');
            $config['uri_segment']  = 4;
        }        
        $this->pagination->initialize($config);
        
        
        $data = array(
            'page_title'    => lang('registred_users'),
            'page_subtitle' => '',
            'users'         => $this->user_model->get_users($config['per_page'],$page, $search),
            'show_search'   => 'users'
        );
            
        $this->template->set_layout('two_col')->build('acp/user/list', $data);        
    }
    
    /**
     * Delete user
     * 
     * @access  public
     * @param   int     $userID
     * @return  void
     */
    public function user_delete()
    {
        if (is_dev())
        {
            $this->output->enable_profiler(FALSE);
        }
        $response = array('status' => 'error', 'message' => 'Error saving new settings.');
        
        if ($this->input->is_post())
        {
            $this->load->model('user_model');
            $userID = (int) $this->input->post('user_ID');
            
            if ( $this->user_model->user_exist($userID) AND $this->user_model->delete_user($userID) )
            {
                $response = array('status' => 'ok', 'message' => 'Settings successfully saved.');
            }
        }
        
        echo json_encode($response);
    }
    
}

/* End of file acp.php */
/* Location: ./application/controllers/acp.php */