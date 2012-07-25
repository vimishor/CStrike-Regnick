<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Update manager
 * 
 * Provides simple update functionalities for CStrike-Regnick application
 * 
 * @package     CStrike-Regnick
 * @subpackage  Module
 * @category    Update
 * @copyright   2012 Gentle Software Solutions
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version     1.0.0
 * @author      Alexandru G. <www.gentle.ro>
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
     * Is a new version available for download ?
     * 
     * Compare local application version with the newest version 
     * available on github downloads.
     * 
     * @access  public
     * @return  bool
     */
    public function check_new_release()
    {
        // if curl is not available, stop here.
        if(!function_exists('curl_version'))
        {
            notify('I can\'t check if a new version is available, because CURL library is not available on this server.');
            redirect('');
        }
        
        $local      = get_option('app_version');        
        $remote     = strtolower($this->get_latest_version());
        
        return version_compare( strtolower($local), $remote, '<');
    }
    
    /**
     * Fetch latest version available on github
     * 
     * @access  protected
     * @return  string|bool     False on error  
     */
    protected function get_latest_version()
    {
        $curl = curl_init('https://api.github.com/repos/'. $this->github_user .'/'. $this->github_repo .'/downloads');
        
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $response     = curl_exec($curl);
        
        if (is_resource($curl))
        {
            curl_close($curl);
        }
        
        if ($response)
        {
            $response   = json_decode($response);
            $versions   = array_map(create_function('$t', 'return $t->description;'), $response);
            usort($versions, "version_compare");
            $response = end($versions);
        }
        
        return $response;
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