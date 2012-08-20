<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Install module
 * 
 * Provides installation wizard for CStrike-Regnick
 * application.
 * 
 * @package     CStrike-Regnick
 * @category    Modules
 * @copyright   (c) 2011 - 2012 Gentle Software Solutions
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @link        http://www.gentle.ro/ 
 */ 
class install extends MY_Controller {

    /**
     * Database connection
     * @var resource
     */
    private $database;
    
    /**
     * Database credentials
     * @var array
     */
    private $db_conf = array(
        'hostname' => '{hostname}',
        'database' => '{database}',
        'username' => '{username}',
        'password' => '{password}',
        // ------------------
        'dbprefix' => '',
        'dbdriver' => 'mysql',
        'pconnect' => FALSE,
        'db_debug' => FALSE,
        'cache_on' => FALSE,
        'cachedir' => '',
        'char_set' => 'utf8',
        'dbcollat' => 'utf8_general_ci'
    );
    
    /**
     * Email credentials
     */
    private $email_conf = array(
        'mailtype'  => 'html',
        'protocol'  => 'smtp',
        
        // sendmail
        //'mailpath'  => '',
        
        // smtp
        'smtp_host' => '',
        'smtp_user' => '',
        'smtp_pass' => '',
        'smtp_port' => ''
    );
    
    /**
     * Permissions tests
     * @var array
     */
    private $permissions = array(
        'app_cache'     => false,
        'app_logs'      => false,
        'app_cfg'       => false,
        'app_cfg_env'   => false,
        'pub_storage'   => false
    );
    
    // ------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        
        $this->load->library('session');
                
        // if is already installed, stop execution here.
        if (!$this->session->userdata('installing') AND defined('RN_INSTALLED') )
        {
            redirect();
        }
        else
        { 
            $this->session->set_userdata('installing', TRUE);
        }
        
        $this->template->site_name = 'CStrike-Regnick';
    }
    
    /**
     * Main entry point
     * 
     * Show step #1.
     * 
     * @access  public
     * @return  void
     */
	public function index()
	{
		$this->step1();
	}
    
    /**
     * This is step #1
     * 
     * Check environment to make sure we can install this application.
     * 
     * @access  public
     * @return  void
     */
    public function step1()
    {
        $this->test_permissions();
        $is_error = in_array('No', $this->permissions);
        
        $data = array(
            'page_title'    => 'Check environment',
            'page_subtitle' => 'Make sure we can install this application',
            'app_dir_cache' => $this->permissions['app_cache'],
            'app_dir_logs'  => $this->permissions['app_logs'],
            'app_cfg'       => $this->permissions['app_cfg'],
            'app_cfg_env'   => $this->permissions['app_cfg_env'],
            'pub_storage'   => $this->permissions['pub_storage'],
            'is_error'      => $is_error
        ); 
        $this->template->set_layout('one_col')->build('step_1', $data);
    }
    
    /**
     * This is step #2
     * 
     * Get database credentials from user and save them to config file.
     * 
     * @access  public
     * @return  void
     */
    public function step2()
    {
        $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation'); 
                
        $this->form_validation->set_rules(array(
            array(
				'field' => 'hostname',
				'label'	=> 'Hostname',
				'rules'	=> 'trim|required|callback_test_db_credentials'
			),
            array(
				'field' => 'database',
				'label'	=> 'Database',
				'rules'	=> 'trim|required'
			),
            array(
				'field' => 'username',
				'label'	=> 'Username',
				'rules'	=> 'trim|required'
			),
            array(
				'field' => 'password',
				'label'	=> 'Password',
				'rules'	=> 'trim'
			),
            array(
				'field' => 'prefix',
				'label'	=> 'Prefix',
				'rules'	=> 'trim'
			),
        ));
        
        // If the form validation passed
        if ($this->form_validation->run('', $this))
        {
            $conf = array(
                'hostname' => $this->input->post('hostname'),
                'database' => $this->input->post('database'),
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'dbprefix' => $this->input->post('prefix')
            );
            $this->db_conf = array_merge($this->db_conf, $conf);
            
            // store dbprefix in session for latter.
            $this->session->set_userdata('dbprefix', $this->db_conf['dbprefix']);
            
            // save database credentials to config
            if ($this->save_db_config())
            {
                redirect('install/step3');
            }
            
            // config not saved for some reason
            notify('Unable to save file database.php. (Probably no write permissions?)', 'error');
            redirect('install/'); 
        }
        else 
        {
            $data = array(
                'page_title'    => 'CStrike-Regnick install',
                'page_subtitle' => 'Please provide database credentials', 
                
                'form_hostname' => array(
                    'class'         => 'input-xlarge',
                    'name'          => 'hostname',
               	    'id'            => 'hostname',
                    'placeholder'   => 'ex: 127.0.0.1',
                    'maxlength'     => 80,
                ),
                'form_database' => array(
                    'class'         => 'input-xlarge',
                    'name'          => 'database',
               	    'id'            => 'database',
                    'placeholder'   => 'MySQL database name',
                    'maxlength'     => 80,
                ),
                'form_username' => array(
                    'class'         => 'input-xlarge',
                    'name'          => 'username',
               	    'id'            => 'username',
                    'placeholder'   => 'MySQL username',
                    'maxlength'     => 60,
                ),
                'form_password' => array(
                    'class'         => 'input-xlarge',
                    'name'          => 'password',
               	    'id'            => 'password',
                    'maxlength'     => 80,
                ),
                'form_prefix' => array(
                    'class'         => 'input-xlarge',
                    'name'          => 'prefix',
               	    'id'            => 'prefix',
                    'placeholder'   => 'ex: regnick_',
                    'maxlength'     => 40,
                ),
            );
    		$this->template->set_layout('one_col')->build('step_2', $data);
        }
    }
    
    /**
     * This is step #3
     * 
     * Load SQL file and import data from it.
     * 
     * @access  public
     * @return  void
     */
    public function step3()
    {
        $schema     = file_get_contents(MODULES_PATH.'install/data/regnick.sql');
        $schema     = str_replace('{prefix}', $this->session->userdata('dbprefix'), $schema);
        $queries    = explode(':: split ::', $schema);
        $complete   = true;
        
        foreach($queries as $query)
        {
            if(trim($query) != "" && strpos($query, "--") === false)
            {   
                if (!$this->db->simple_query($query))
                {
                    $complete = false;
                    break;
                }
            }
        }
        
        if (!$complete)
        {
            $this->load->helper('core');
            notify('Database query error occured when importing SQL file.', 'error');
            redirect('install/step2');
        }
        
        redirect('install/step4');
    }
    
    /**
     * This is step #4
     * 
     * Get email credentials from user and save them to config file.
     * 
     * @access  public
     * @return  void
     */
    public function step4()
    {
        $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation'); 
        
        $this->form_validation->set_rules(array(
            array(
				'field' => 'smtp_host',
				'label'	=> 'SMTP Host',
				'rules'	=> 'trim|required'
			),
            array(
				'field' => 'smtp_port',
				'label'	=> 'SMTP Port',
				'rules'	=> 'trim|required|numeric'
			),
            array(
				'field' => 'smtp_user',
				'label'	=> 'SMTP User',
				'rules'	=> 'trim|required'
			),
            array(
				'field' => 'smtp_pass',
				'label'	=> 'SMTP Pass',
				'rules'	=> 'trim|required'
			)
        ));
        
        // If the form validation passed
        if ($this->form_validation->run('', $this))
        {
            $conf = array(
                'smtp_host' => $this->input->post('smtp_host'),
                'smtp_port' => $this->input->post('smtp_port'),
                'smtp_user' => $this->input->post('smtp_user'),
                'smtp_pass' => $this->input->post('smtp_pass')
            );
            $this->email_conf = array_merge($this->email_conf, $conf);
            
            // save email credentials to config
            if ($this->save_email_config())
            {
                set_option('register_confirmation', '1'); // replaces `register.confirmation` setting from config file
                redirect('install/step5');
            }
            
            notify('Unable to save config file email.php. (Probably no write permissions?)', 'error');
            redirect('install/step3');
        }
        else
        {
            $data = array(
                'page_title'    => 'CStrike-Regnick install',
                'page_subtitle' => 'Please provide email credentials', 
                
                'smtp_host' => array(
                    'class'         => 'input-xlarge',
                    'name'          => 'smtp_host',
               	    'id'            => 'smtp_host',
                    'placeholder'   => '',
                    'maxlength'     => 210,
                ),
                'smtp_port' => array(
                    'class'         => 'input-xlarge',
                    'name'          => 'smtp_port',
               	    'id'            => 'smtp_port',
                    'placeholder'   => '',
                    'maxlength'     => 80,
                ),
                'smtp_user' => array(
                    'class'         => 'input-xlarge',
                    'name'          => 'smtp_user',
               	    'id'            => 'smtp_user',
                    'placeholder'   => '',
                    'maxlength'     => 80,
                ),
                'smtp_pass' => array(
                    'class'         => 'input-xlarge',
                    'name'          => 'smtp_pass',
               	    'id'            => 'smtp_pass',
                    'maxlength'     => 80,
                ),
            );
            $this->template->set_layout('one_col')->build('step_4', $data);
        }
        
    }
    
    /**
     * This is step #5
     * 
     * We are done !
     * 
     * @access  public
     * @return  void
     */
    public function step5()
    {
        // clean installer data from session
        $this->session->unset_userdata('installing');
        $this->session->unset_userdata('dbprefix');
        
        $data = array(
            'page_title'    => 'Instalation complete',
            'page_subtitle' => 'Have fun !'
        ); 
        $this->template->set_layout('one_col')->build('step_5', $data);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Test directories and files permissions
     * 
     * Make sure certain directories and files are writable or not.
     * 
     * @access  private
     * @return  void
     */
    private function test_permissions()
    {
        // create environment directory 
        if(!is_dir(APPPATH.'config/'.ENVIRONMENT))
        {
            $old = umask(0); 
            mkdir(APPPATH.'config/'.ENVIRONMENT,0777);
            umask($old);
        }

        $this->permissions['app_cache']     = is_really_writable(APPPATH.'cache') ? 'Yes' : 'No';
        $this->permissions['app_logs']      = is_really_writable(APPPATH.'logs') ? 'Yes' : 'No';
        $this->permissions['app_cfg']       = is_really_writable(APPPATH.'config') ? 'Yes' : 'No';
        $this->permissions['app_cfg_env']   = is_really_writable(APPPATH.'config/'.ENVIRONMENT) ? 'Yes' : 'No';
        $this->permissions['pub_storage']   = is_really_writable(FCPATH.'pub/storage') ? 'Yes' : 'No';
    }
    
    /**
     * Write database config file
     * 
     * @access  protected
     * @return  bool
     */
    protected function save_db_config()
    {   
        $cfg_tpl = file_get_contents(MODULES_PATH.'install/data/database.sample.php');
        
        $replace = array(
			'{hostname}' 	=> $this->db_conf['hostname'],
            '{database}'    => $this->db_conf['database'],
			'{username}' 	=> $this->db_conf['username'],
			'{password}' 	=> $this->db_conf['password'],
            '{prefix}'      => $this->db_conf['dbprefix'],
		);
        
        // replace {} variables from template with real data
        $new_cfg_file   = str_replace(array_keys($replace), $replace, $cfg_tpl);
        
        // process main config file
        $this->save_main_config();
        
        return $this->write_file(APPPATH.'config/'.ENVIRONMENT.'/database.php', $new_cfg_file);
    }
    
    /**
     * Write email config file
     * 
     * @access  protected
     * @return  bool
     */
    protected function save_email_config()
    {   
        $cfg_tpl = file_get_contents(MODULES_PATH.'install/data/email.sample.php');
        
        $replace = array(
			'{smtp_host}' 	=> $this->email_conf['smtp_host'],
            '{smtp_user}'   => $this->email_conf['smtp_user'],
			'{smtp_pass}' 	=> $this->email_conf['smtp_pass'],
			'{smtp_port}' 	=> $this->email_conf['smtp_port']
		);
        
        // replace {} variables from template with real data
        $new_cfg_file   = str_replace(array_keys($replace), $replace, $cfg_tpl);
        
        return $this->write_file(APPPATH.'config/'.ENVIRONMENT.'/email.php', $new_cfg_file);
    }
    
    /**
     * Write main config file
     * 
     * @access  protected
     * @return  bool
     */
    protected function save_main_config()
    {
        $this->load->helper('string');
        
        $cfg_tpl        = file_get_contents(APPPATH.'config/config.php');
        $new_key        = random_string('alnum', 34); 
        $new_cfg_file   = str_replace('CHANGE-ME', $new_key, $cfg_tpl);
        
        
        return $this->write_file(APPPATH.'config/config.php', $new_cfg_file);
    }
    
    /**
     * Write content to file
     * 
     * @access  protected
     * @param   string      $file       Path to file
     * @param   string      $content    Content that will be written in file
     * @return  bool
     */
    protected function write_file($file, $content)
    {
        // open config file and write new data
        $handle = @fopen($file,'w+');
        
        if ($handle !== false)
        {
            return @fwrite($handle, $content);
        }
        
        return false;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Test database connection and check if database exists 
     * 
     * @access  public
     * @return  bool
     */
    public function test_db_credentials()
    {
        $this->db_conf['hostname'] = $this->input->post('hostname');
        $this->db_conf['database'] = $this->input->post('database');
        $this->db_conf['username'] = $this->input->post('username');
        $this->db_conf['password'] = $this->input->post('password');
        
        if (!$this->test_db_connection())
        {
            $this->form_validation->set_message('test_db_credentials', 'Can\'t connect to database using specified credentials.');
            return false;
        }
        
        if (!$this->test_database_exists())
        {
            $this->form_validation->set_message('test_db_credentials', 'Can\'t select database "'.$this->db_conf['database'].'" ');
            return false;
        }
        
        return true;
    }
    
    /**
     * Test database connection
     * 
     * @access  private
     * @return  bool
     */
    private function test_db_connection()
    {
        return ($this->database = @mysql_connect($this->db_conf['hostname'], $this->db_conf['username'], $this->db_conf['password']));
    }
        
    /**
     * Test if specified database exists
     * 
     * @access  private
     * @return  bool
     */
    private function test_database_exists()
    {
        return @mysql_select_db($this->db_conf['database'], $this->database);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Do some cleaning before exit
     * 
     * @access  public
     * @return  void
     */
    public function __destruct()
    {
        @mysql_close($this->database);
    }
}

/* End of file install.php */
/* Location: modules/install/install.php */
