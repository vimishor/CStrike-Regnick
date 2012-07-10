<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * CStrike-Regnick update class
 * 
 * Provides simple update functionalities for CStrike-Regnick application
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Update
 * @author		Alexandru G.
 * @link		http://www.gentle.ro/
 * @license     http://www.gnu.org/licenses/gpl-2.0.txt
 * @version     1.0.0
 */
class Update_Manager {

    // CI instance
    private $CI;

    /**
     * Last message status
     * @var string
     */
    private $status;
    
    /**
     * Github settings
     */
    protected $github_user = 'vimishor';
    protected $github_repo = 'CStrike-Regnick';

    // ------------------------------------------------------------------------
    
    public function __construct()
    {
        $this->CI = &get_instance();
        
        $this->CI->load->library('migration', array(
            'migration_enabled' => TRUE,
            'migration_path'    => MODULES_PATH.'update/migrations/',
        ));
        
        log_message('debug', 'Update_Manager class initialized');
    }

    // ------------------------------------------------------------------------
    
    /**
     * Update database
     * 
     * @access  public
     * @param   int     $version    At what version you wish to shift ? (optional)
     * @return  bool
     */
	public function database($version = 0)
    {
        if (!is_numeric($version))
        {
            $this->status = 'Version must be a numeric value';
            return false;
        }
        
        $schema = $this->schema($version);
        
        if (is_numeric($schema))
        {
            $this->status = 'Updated successfully';
            return true;
        }
        
        if ($schema)
        {
            $this->status = 'No update available';
            return false;
        }
                
        $this->status = 'Update failed';
        return false;
    }
    
    /**
     * Update database schema
     * 
     * @access  protected
     * @param   int         $version    At what version you wish to shift ? (optional)
     * @return  mixed                   true if already latest, false if failed, int if upgraded
     */
    protected function schema($version)
    {
        if ($version == 0)
        {
            return $this->CI->migration->latest();
        }         
        
        return $this->CI->migration->version($version);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Get current status
     * 
     * @access  public
     * @return  string
     */
    public function get_status()
    {
        return $this->status;
    }
}

/* End of file Update.php */