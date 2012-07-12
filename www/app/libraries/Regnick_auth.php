<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Regnick Auth
 * 
 * Basic authentication library.
 * 
 * @package Regnick_Auth
 * @author  Alexandru G. (http://vim.gentle.ro)
 * @version 0.1.0
 * @license MIT
 */
class Regnick_auth
{    
    /**
     * Codeigniter instance
     * 
     * @var CI_Controller
     */
    private $ci;
    
    /**
     * Error(s) holder
     * @var array
     */
    private $errors = array();
    
    // ----------------------------------------------------------------------------------------------------------
    
    public function __construct()
    {
        $this->ci = &get_instance();
        
        // load dependencies
        $this->ci->load->library('session');
        $this->ci->load->database();
        $this->ci->load->model('user_model');
        
        // terminate if user has disabled xss filtering.
        if ($this->ci->config->item('global_xss_filtering') === false)
        {
            show_error('Please activate `global_xss_filtering` from config.');
        }
    }
    
    // ----------------------------------------------------------------------------------------------------------
    
    /**
     * Check if specified user has owner access.
     * 
     * @access  public
     * @param   int     $userID
     * @return  bool
     */
    public function isOwner($userID)
    {
        if ($this->logged_in() === false)
        {
            return false;
        }
        
        return $this->hasFlag($userID, 'f');
    }
    
    /**
     * Check if specified user has specified account flag
     * 
     * `$flag` can be any letter or group of letters.
     * 
     * @access  public
     * @param   int     $userID     Username ID
     * @param   string  $flag       Account flag(s)
     * @return  bool
     */
    public function hasFlag($userID, $flag)
    {
        $user_flags = $this->ci->user_model->getRow($userID, 'account_flags');
        
        return (($user_flags) AND (strpos($user_flags, $flag) !== false) ) ? true : false;
    }
    
    // ----------------------------------------------------------------------------------------------------------
    
    /**
     * Update UCP settings
     * 
     * @access  public
     * @return  bool
     */
    public function ucp_settings($user_id)
    {
        return $this->ci->user_model->setSettings($user_id, array(
            'email' => $this->ci->input->post('email'),
        ));
    }
    
    /**
     * Login user
     * 
     * @access  public
     * @param   string  $identity   Username
     * @param   string  $password   Password
     * @param   bool    $remember   Remember login
     * @return  bool
     */
    public function user_login($identity, $password, $remember)
    {
        $this->ci->benchmark->mark('user_login_start');
        if (empty($identity) || empty($password))
		{
			$this->set_error('login_unsuccessful');
			return false;
		}
        
        if ($this->ci->user_model->user_exist($identity) === false) // account exists ?
        {
            $this->set_error('account_invalid');
            return false;
        }
        
        if ($this->ci->user_model->user_is_active($identity) === false) // account active ?
        {
            $this->set_error('account_not_active');
            return false;
        }
        
        if ($this->password_valid($identity, $password) === false) // password match ?
        {
            $this->set_error('invalid_password');
            return false;
        }
        
        // register session
        $session_data = array(
            'identity'  => $identity,
            'email'     => $this->ci->user_model->getRow($identity, 'email'),
            'user_id'   => $this->ci->user_model->getRow($identity, 'ID'),
        );
        $this->ci->session->set_userdata($session_data);
                
        notify($this->ci->lang->line('login_successful'), 'success');
        
        $this->ci->benchmark->mark('user_login_end');
        
        return true;
    }
    
    /**
     * Save data for user autologin
     * 
     * @access  private
     * @param   string      $identity   Username
     * @return  void
     */
    private function create_autologin($identity) { }
    
    /**
     * Is user logged in ?
     * 
     * @access  public  
     * @return  bool
     */
    public function logged_in()
    {
        return $this->ci->session->userdata('email');
    }
    
    /**
     * Terminate user session
     * 
     * @access  public
     * @return  void
     */
    public function user_logout()
    {
        if ($this->logged_in())
        {
            $this->ci->session->unset_userdata('identity');
            $this->ci->session->unset_userdata('email');
            $this->ci->session->unset_userdata('user_id');
            
            // @todo: delete cookie(s)
            
            // destroy session
            $this->ci->session->sess_destroy();
            
            notify($this->ci->lang->line('logout_successful'), 'success');
        }
    }
    
    /**
     * Add new account
     * 
     * Data is validated automatically by framework, so we can assume this is 
     * valid and sanitized data.
     * 
     * @access  public
     * @param   string      $login          Username
     * @param   string      $email          Email address
     * @param   string      $email-conf     Email address confirmation
     * @param   string      $password       Account password
     * @param   string      $password-conf  Account password confirmation
     * @param   int|bool    $groupID        Add user to this group on specified server
     * @param   int|bool    $serverID       Add user access on this server
     * @return  bool
     */
    public function user_add($login, $email, $email_conf, $password, $password_conf, $groupID = false, $serverID = false)
    {        
        $flags      = $this->ci->user_model->checkFlags($login, 'b', 'a', '');
        $is_active  = ( (get_option('register_confirmation') == '1') AND (can_send_email() === true) ) ? false : true;
        
        // add user
        $user       = $this->ci->user_model->user_add($login, $password, $email, $is_active, $flags);
                
        // include user in chosen group
        if ( is_numeric($groupID) AND is_numeric($serverID) )
        { 
            $user_group = $this->ci->user_model->saveAccess($this->ci->db->insert_id(), $serverID, $groupID);
        }
                
        return $user;
    }
    
    /**
     * Recover lost account password
     * 
     * @access  public
     * @param   string  $email  Account email
     * @return  bool
     */
    public function account_recover($email)
    {
        if (!$this->ci->user_model->email_exist($email))
        {
            $this->set_error('invalid_email');
            return false;
        }
        
        if ( can_send_email() === false)
        {
            $this->set_error('email_not_configured');
            return false;
        }
        
        $this->ci->load->helper('string'); // used to generate new password
        
        $new_pass   = strtolower(random_string('alnum', 12));
        $userID     = $this->ci->user_model->getUserIDbyEmail($email);
        
        if ($this->ci->user_model->setRow($userID, 'password', $this->ci->user_model->hash_password($new_pass)) === false )
        {
            $this->set_error('password_update_error');
            return false;
        }
        
        // send email
        $this->ci->load->helper('email');        
        $message = prep_email_message('email/recover_password', 
            array(
                'site_name'         => $this->ci->config->item('site.name'), 
                'generated_pass'    => $new_pass,
                'login_link'        => site_url('ucp/login/'),
            )
        );
        send_email($email, 'Recover account', $message);
        
        notify($this->ci->lang->line('recover_successful'), 'success');
        return true;
    }
    
    public function user_activate() { }
    
    /**
     * Change user account password
     * 
     * @access  public
     * @param   string  $current_pass   Current account password
     * @param   string  $new_password   New account password
     * @param   string  $confirm_pass   Confirm new password
     * @return  bool
     */
    public function change_pass($current_pass, $new_password, $confirm_pass)
    {
        if ($this->password_valid($this->ci->session->userdata('user_id'), $current_pass ) === false)
        {
            $this->set_error('invalid_password');
            return false;
        }
        
        if ($this->ci->user_model->setRow($this->ci->session->userdata('user_id'), 'password', $this->ci->user_model->hash_password($confirm_pass)) === false )
        {
            $this->set_error('password_update_error');
            return false;
        }
        
        notify($this->ci->lang->line('chg_pass_successful'), 'success');
        return true;
    }
    
    // ----------------------------------------------------------------------------------------------------------
    
    /**
     * Validate user password
     * 
     * @access  protected
     * @param   string|int  $identity   Username or userID
     * @return  bool
     */
    protected function password_valid($identity, $password)
    {
        $db_pass    = $this->ci->user_model->getRow($identity, 'password');
        $password   = $this->ci->user_model->hash_password($password);
        
        return ($db_pass == $password);
    }
    
    // ----------------------------------------------------------------------------------------------------------
        
    /**
	 * Set an error message
	 *
     * @access  public
     * @param   string  $error  Error message
     * @return  string   
	 */
	public function set_error($error)
	{
		$this->errors[] = $error;
        notify($this->ci->lang->line($error), 'error'); // show error to user
        
		return $error;
	}
    
    /**
     * Get last error
     * 
     * @access  public
     * @return  string
     */
    public function get_error()
    {
        if ($this->is_error() === false)
        {
            return '';
        }
        
        return array_pop($this->errors);
    }
    
    /**
     * Check if any error has occured
     * 
     * @access  public
     * @return  bool
     */
    public function is_error()
    {
        return (count($this->errors) > 0) ? true : false;
    }
}

/**
 * @TODO[4]
 */
