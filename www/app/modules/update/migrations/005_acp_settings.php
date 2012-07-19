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

class Migration_acp_settings extends CI_Migration {
        
    /**
     * Update database schema
     * 
     * @access  public
     * @return  void
     */
    public function up()
    {   
        $data = array(
            array(
                'name'  => 'site_name', 
                'value' => $this->config->item('site.name')
            ),
            array(
                'name'  => 'register_global',
                'value' => ($this->config->item('register.global')) ? 1 : 0
            ),
            array(
                'name'  => 'webmaster_email', 
                'value' => $this->config->item('webmaster.email')
            ),
            array(
                'name'  => 'results_per_page', 
                'value' => $this->config->item('results_per_page')
            ),
        );
        $this->db->insert_batch('options', $data);
        
        // update db version
        set_option('db_version', '19072012');
    }
    
    /**
     * Rollback changes made by this version
     */
    public function down() { }
}