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

class Migration_md5_passwords extends CI_Migration {
    
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
        set_option('db_version', '26012013');

        // hash with salted md5 all clear text passwords
        $salt = $this->config->item('encryption_key');
        $this->db->simple_query('UPDATE '. $this->db->dbprefix('users') .' SET password = MD5(CONCAT("'.$salt.'", password)), passwd_type = "1" WHERE passwd_type = "0";');

        // change password column to 32
        $this->dbforge->modify_column('users', array(
            'password' => array(
                'name'          => 'password',
                'type'          => 'VARCHAR',
                'constraint'    => 32
            ),
        ));
    }
    
    /**
     * Rollback changes made by this version
     */
    public function down()
    { }
}
