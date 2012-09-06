<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Update Library
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
class Update_lib {
    
    // CI instance
    protected $CI;

    /**
     * Last message status
     * @var string
     */
    protected $status;
    
    /**
     * Cache info for later usage
     * @var array
     */
    protected $cache = array(
        'backup' => array(
            'filename'  => null,
            'data'      => null
        ),
    );
    
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
        
        log_message('debug', 'Update_lib class initialized');
    }
    // ------------------------------------------------------------------------
    
    /**
     * Perform a database backup
     * 
     * @access  public
     * @param   string  $filename   Backup archive filename
     * @return  bool
     */
    public function db_backup($filename)
    {
        $this->CI->load->dbutil();
        $this->CI->load->helper('file');
        
        $backup =& $this->CI->dbutil->backup();
        
        // store this info for later usage
        $this->set_cache('backup', 'filename', $filename);
        $this->set_cache('backup', 'data', $backup);
        
        return write_file(FCPATH.'pub/storage/'.$filename, $backup); 
    }
    
    /**
     * Perform a database update/downgrade
     * 
     * @access  public
     * @param   int     $version    Migration number
     * @return  bool
     */
    public function db_update($version = 0)
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
     * Check for a new release
     * 
     * Check if a new application version is available on github downloads
     * 
     * @access  public
     * @return  bool
     */
    public function release_available()
    {
        // if curl is not available, stop here.
        if(!function_exists('curl_version'))
        {
            $this->status = 'CURL is not available on this machine.';
            return false;
        }
        
        $local      = get_option('app_version');        
        $remote     = strtolower($this->get_latest_version());
        
        return ($remote) ? version_compare( strtolower($local), $remote, '<') : false;
    }
    
    /**
     * Fetch latest version available on github
     * 
     * @access  protected
     * @return  string|bool     False on error  
     */
    protected function get_latest_version()
    {
        // if curl is not available, stop here.
        if(!function_exists('curl_version'))
        {
            $this->status = 'CURL is not available on this machine.';
            return false;
        }
                        
        $curl = curl_init('https://api.github.com/repos/'. $this->github_user .'/'. $this->github_repo .'/downloads');
        
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $response     = curl_exec($curl);
        
        if (is_resource($curl))
        {
            curl_close($curl);
            $this->status = 'Invalid response received.';
            return false;
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
     * Fetch a cache item
     * 
     * @access  public
     * @param   string  $index  Item index
     * @param   string  $item   Item name
     * @return  mixed
     */
    public function get_cache($index, $item)
    {
        if (isset($this->cache[$index]) AND isset($this->cache[$index][$item]) )
        {
            return $this->cache[$index][$item];
        }
        
        return false;
    }
    
    /**
     * Set a cache item
     * 
     * @access  public
     * @param   string  $index  Item index
     * @param   string  $item   Item name
     * @param   mixed   $value  Item value
     * @return  void
     */
    public function set_cache($index, $item, $value)
    {
        $this->cache[$index][$item] = $value;
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