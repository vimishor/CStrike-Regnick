<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Migration extends CI_Migration {

    public function __construct($config = array())
    {
        parent::__construct($config);
        
        // migration path is not preserved when passing it to constructor. why ?
        $this->_migration_path = MODULES_PATH.'update/migrations/';
    }

    /**
     * Check if a database update is available
     * 
     * @access  public
     * @return  bool
     */
    public function db_update_available()
    {
        return version_compare($this->version_available(), $this->_get_version(), '>');
    }
    
    /**
     * Get version available
     * 
     * @access  public
     * @return  int|bool    False on error
     */
    public function version_available()
    {
        if ( ! $migrations = $this->find_migrations())
		{
			$this->_error_string = $this->lang->line('migration_none_found');
			return false;
		}

		$last_migration = basename(end($migrations));
        
        return substr($last_migration, 0, 3);
    }
}

//--------------------------------------------------------------------

/* End of file MY_Migration.php */