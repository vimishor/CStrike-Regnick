<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Update module
 * 
 * Provides a simple update features for CStrike-Regnick
 * application.
 * 
 * @package     CStrike-Regnick
 * @category    Modules
 * @copyright   (c) 2011 - 2012 Gentle Software Solutions
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @link        http://www.gentle.ro/ 
 */  
class Update extends ACP_Controller {
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->library('update_lib');
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Perform a database upgrade/downgrade
     * 
     * @access  public
     * @return  void
     */
    public function database($force = false)
    {
        if ($force == 'force')
        {
            if ( $this->migration->db_update_available() )
            {
                $this->update_lib->db_update();
                notify($this->update_lib->get_status());
            }
            else
            {
                notify('No database update is available', 'success');
            }
            
            redirect('');   
        }
        
        $data = array(
            'page_title'    => 'Database update',
            'page_subtitle' => ''
        );
        
        $this->template->set_layout('one_col')->build('backup_db', $data);                
    }
    
    /**
     * Check if a new release version is available on github downloads
     * 
     * @access  public
     * @return  void
     */
    public function release()
    {
        $response   = $this->update_lib->release_available();
        $status     = $this->update_lib->get_status();
        
        // library error
        if ( (!$response) AND (!empty($status)) )
        {
            notify($this->update_lib->get_status(), 'error');
        }
        
        
        if ($response)
        {
            notify('A new version of CStrike-Regnick is available. Please update as soon as posible. <br> 
                    Visit <a href="http://www.gentle.ro/proiecte/cstrike-regnick/">official page</a> for more informations.', '');
        }
        else
        {
            notify('You have the latest CStrike-Regnick version. Good for you !', 'success');
        }
        
        redirect('');
    }
    
    /**
     * Create/delete a backup file
     * 
     * `$what` can be:
     *  'db'    = create database backup
     *  'fs'    = create filesystem backup (not implemented)
     *  'clean' = delete backup files from server 
     * 
     * @access  public
     * @param   string  $what   See above description.
     * @return  void
     */
    public function backup($what = 'db')
    {
        // delete backup files from server
        if ($what == 'clean')
        {
            $this->load->helper('file');
            $extensions = array('db.backup.gz');
            $files      = get_filenames_by_partial_name(FCPATH.'pub/storage/', $extensions);
            $count      = count($files);
                        
            if ($count == 0)
            {
                notify('No backup files exist.', 'success');
                redirect('');
            }
            
            foreach ($files as $file)
            {
                unlink(FCPATH.'pub/storage/'.$file);
            }
            notify(count($files).' backup files were deleted.', 'success');
            
        }
        
        // backup database
        if ($what == 'db')
        {
            // create a backup
            $this->load->helper('string');
            $filename = 'cstrike-regnick_'. strtolower(random_string('alnum', 12)) .'.db.backup.gz';
            
            if ($this->update_lib->db_backup($filename))
            {
                // allow the owner to download this backup file
                $this->load->helper('download');
                $backup = $this->update_lib->get_cache('backup', 'data');
                
                force_download(base_url().'storage/'.$filename, $backup);
            }
            else
            {
                notify('Can\'t write backup file!<br>Please make sure that user under which web server is running, has write permission in directory `pub/storage` ', 'error');
            }
        }
        
        if ($what == 'fs')
        {
            //@TODO: implement FS backup
            notify('File system backup is not yet implemented.', 'info');
        }
        
        redirect('');
    }
}