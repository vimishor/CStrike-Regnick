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

class Migration_update_module_github extends CI_Migration {
        
    /**
     * Update database schema
     * 
     * @access  public
     * @return  void
     */
    public function up()
    {
        // update db version
        set_option('app_version', '2.0.0-rc2');
    }
    
    /**
     * Rollback changes made by this version
     */
    public function down() { }
}