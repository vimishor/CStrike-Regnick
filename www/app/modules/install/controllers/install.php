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
     * Permissions tests
     * @var array
     */
    private $permissions = array(
        'app_cache'     => false,
        'app_logs'      => false,
        'app_db_cfg'    => false,
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
            'app_file_db'   => $this->permissions['app_db_cfg'],
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
            redirect('install/');
        }
                
        // clean installer data from session
        $this->session->unset_userdata('installing');
        $this->session->unset_userdata('dbprefix');
        
        $data = array(
            'page_title'    => 'Instalation complete',
            'page_subtitle' => 'Have fun !'
        ); 
        $this->template->set_layout('one_col')->build('step_3', $data);
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
        $this->permissions['app_cache']     = is_writable(APPPATH.'cache') ? 'Yes' : 'No';
        $this->permissions['app_logs']      = is_writable(APPPATH.'cache') ? 'Yes' : 'No';
        $this->permissions['app_db_cfg']    = is_writable(APPPATH.'cache') ? 'Yes' : 'No';
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
        
        // open config file and write new data
        $handle = @fopen(APPPATH.'config/database.php','w+');
        
        if ($handle !== false)
        {
            return @fwrite($handle, $new_cfg_file);
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
