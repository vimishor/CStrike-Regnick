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

class Migration_users_table_alter extends CI_Migration {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
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
        set_option('db_version', '23082012');

        // add new columns to `users` table
        $fields = array(
            'last_login' => array(
                'type'          => 'INT',
                'constraint'    => 10
            ),
            'passwd_type' => array(
                'type'          => 'INT',
                'constraint'    => 2,
                'default'       => 0
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
        set_option('db_version', '19072012');

        // remove new columns from `users` table
        $this->dbforge->drop_column('users', 'last_login');
        $this->dbforge->drop_column('users', 'passwd_type');
    }
}