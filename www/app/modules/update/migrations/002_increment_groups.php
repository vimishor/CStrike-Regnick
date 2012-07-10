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

class Migration_increment_groups extends CI_Migration {
        
    /**
     * Update database schema
     */
    public function up()
    {
        // set auto_increment on `groups` table
        $this->db->query('ALTER TABLE '.$this->db->dbprefix('groups') .'AUTO_INCREMENT = 1');
        
        // update db version
        set_option('db_version', '09072012');
    }
    
    /**
     * Rollback changes made by this version
     */
    public function down()
    {
        // update db version
        set_option('db_version', '21062012');
    }
}