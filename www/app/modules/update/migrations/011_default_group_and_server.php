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

class Migration_default_group_and_server extends CI_Migration {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
        $this->load->dbutil();
    }

    /**
     * Update database schema
     * 
     * @access  public
     * @return  void
     */
    public function up()
    {        
        // update db version 
        set_option('db_version', '12012013');

        // @see https://github.com/vimishor/CStrike-Regnick/issues/36
        $this->db->simple_query('UPDATE '.$this->db->dbprefix('groups') .' SET ID = REPLACE(ID, ID, ID+1) ORDER BY ID DESC;');
        $this->db->simple_query('UPDATE '.$this->db->dbprefix('groups') .' AUTO_INCREMENT = 1;');
        $this->db->simple_query('UPDATE '.$this->db->dbprefix('users_access') .' SET group_ID = REPLACE(group_ID, group_ID, group_ID+1);');
    }
    
    /**
     * Rollback changes made by this version
     */
    public function down()
    { }
}
