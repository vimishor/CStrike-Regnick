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
class Acp extends MY_Controller
{
	
    public function __construct()
    {
        parent::__construct();
        
        // Each page served by this controller requires user to be logged in.
        if ($this->regnick_auth->logged_in() === false)
        {
            store_location();
            redirect('ucp/login', 'refresh');
        }
        
        // Each page served by this controller requires user to have `administrator` access.
        if ($this->regnick_auth->isOwner($this->session->userdata('user_id')) === false)
        {
            notify($this->lang->line('insuficient_access'), 'success');
            redirect('', 'refresh');
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
        $data = array(
            'page_title'    => lang('admin_dashboard'),
            'page_subtitle' => 'Application overview',
        );
        
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
        //$this->load->library('security');
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
        //$this->load->library('security');
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
    public function server_list($page = 0)
    {        
        $this->load->model('server_model');
        $this->load->library('pagination');
        $config['base_url']     = base_url().'/acp/server/list/';
        $config['total_rows']   = $this->db->where('ID >', 0)->count_all_results('servers');
        $config['per_page']     = 2;
        $config['uri_segment']  = 4;
        $this->pagination->initialize($config);
                
        $data = array(
            'page_title'    => lang('available_servers'),
            'page_subtitle' => '',
            //'groups'        => $this->group_model->get_groups($config['per_page'],$page),
            'servers'       => $this->server_model->getServers(false, $config['per_page'], $page),
        );
            
        $this->template->set_layout('two_col')->build('acp/server/list', $data);        
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
        //$this->load->library('security');
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
        //$this->load->library('security');
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
    public function group_list($page = 0)
    {        
        $this->load->model('group_model');
        $this->load->library('pagination');
        $config['base_url']     = base_url().'/acp/group/list/';
        $config['total_rows']   = $this->db->count_all('groups');
        $config['per_page']     = 2;
        $config['uri_segment']  = 4;
        $this->pagination->initialize($config);
        
        
        $data = array(
            'page_title'    => lang('available_groups'),
            'page_subtitle' => '',
            'groups'        => $this->group_model->get_groups($config['per_page'],$page),
        );
            
        $this->template->set_layout('two_col')->build('acp/group/list', $data);        
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
                $error = $this->user_model->get_error();
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
                'groups'        => $this->group_model->get_groups(100, 0),
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
        //$this->load->library('security');
        $this->load->model('server_model');
        
        if ($this->form_validation->run('acp-user-edit') === true) // process form
        {
            $login = $this->input->post('login');
            $flags = $this->user_model->checkFlags($login, $this->input->post('user_flags_b'), $this->input->post('user_flags_a'), $this->input->post('user_flags_c'));
            
            if ( $this->user_model->saveUser($userID, $login, $this->input->post('passwd'), $this->input->post('email'), (bool)$this->input->post('active'), $flags ) === true)
            {
                console_log('OK');
                notify($this->lang->line('data_saved'), 'success');
                redirect('acp/user/list', 'refresh');
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
                    'page_title'    => 'Edit account',  
                    'page_subtitle' => '',      
                    'servers'       => $servers,
                    'no_access'     => $this->user_model->match_access($userID, $servers), // TODO: refactor this
                    'userID'        => $userID,
                    
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
                    
                    'ckbox_public' => array(
                        'name'      => 'active',
                        'id'        => 'active',
                        'value'     => 'accept',
                        'checked'   => ($user->active == 1) ? true : false,
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
                        'value'     => ''
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
        //$this->load->library('security');
        
        if ($this->form_validation->run('acp-user-add') === true) // process form
        {
            $login = $this->input->post('login');
            $flags = $this->user_model->checkFlags($login, $this->input->post('user_flags_b'), $this->input->post('user_flags_a'), $this->input->post('user_flags_c'));
            
            if ($this->user_model->user_add($login, $this->input->post('passwd'), $this->input->post('email'), (bool)$this->input->post('active'), $flags ))
            {
                console_log('OK');
                notify('account_added', 'success');
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
     * User list
     * 
     * @access  public
     * @return  void
     */
    public function users_list($page = 0)
    {        
        
        $this->load->library('pagination');
        $config['base_url']     = base_url().'/acp/user/list/';
        $config['total_rows']   = $this->db->count_all('users');
        $config['per_page']     = 2;
        $config['uri_segment']  = 4;
        $this->pagination->initialize($config);
        
        
        $data = array(
            'page_title'    => lang('registred_users'),
            'page_subtitle' => '',
            'users'         => $this->user_model->get_users($config['per_page'],$page),
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