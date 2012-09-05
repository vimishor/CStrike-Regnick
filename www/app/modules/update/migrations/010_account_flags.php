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

class Migration_account_flags extends CI_Migration {
    
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
        set_option('db_version', '05092012');

        // remove "clan tag" flag
        $this->db->simple_query('UPDATE '.$this->db->dbprefix('users') .' SET account_flags = REPLACE(account_flags, "b", "");');


        // add new column to `users` table
        $fields = array(
            'notes' => array(
                'type'  => 'TEXT',
                'null'  => true
            ),
        );
        $this->dbforge->add_column('users', $fields);
    }
    
    /**
     * Rollback changes made by this version
     */
    public function down()
    {
        // update db version
        set_option('db_version', '23082012');

        // remove new columns from `users` table
        $this->dbforge->drop_column('users', 'notes');
    }
}