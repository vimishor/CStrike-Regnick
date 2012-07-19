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

class Migration_add_email_option extends CI_Migration {
        
    /**
     * Update database schema
     */
    public function up()
    {        
        // update db version
        set_option('register_confirmation', '1');
        
        // update db version
        set_option('db_version', '12072012');
    }
    
    /**
     * Rollback changes made by this version
     */
    public function down() { }
}