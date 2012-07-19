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

/**
 * Note: Maybe end-user has personalized `default` group name, 
 *       so leave that unchanged.
 */
class Migration_default_group_flag extends CI_Migration {
        
    /**
     * Update database schema
     * 
     * @access  public
     * @return  void
     */
    public function up()
    {        
        // Change default group flag from 'z' to 'r'
        $data = array(
            'access'    => DEFAULT_GROUP_FLAG,
            'public'    => 0,
        );
        $this->db->where('ID', 0)->update('groups', $data);
        
        // update db version
        set_option('db_version', '16072012');
    }
    
    /**
     * Rollback changes made by this version
     */
    public function down()
    {
        // change default group flag from 'r' to 'z'
        $data = array(
            'access'    => 'z',
            'public'    => 0,
        );
        
        $this->db->where('ID', 0)->update('groups', $data);
    }
}