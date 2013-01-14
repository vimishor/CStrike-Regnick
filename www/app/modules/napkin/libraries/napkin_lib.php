<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Update Library
 * 
 * Provides simple update functionalities for CStrike-Regnick application
 * 
 * @package     CStrike-Regnick
 * @subpackage  Module
 * @category    Update
 * @copyright   2012 Gentle Software Solutions
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version     1.0.1
 * @author      Alexandru G. <www.gentle.ro>
 */
class Napkin_lib {
    
    // CI instance
    protected $CI;

    public $db_problems = array(
        'orphan_accounts'   => 0,
        'orphan_accesses'   => 0,
        'invalid_emails'    => 0,
        'total'             => 0,
        'fixed'             => 0
    );
    
    // ------------------------------------------------------------------------
    
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('napkin_m');
        
        log_message('debug', 'Napkin_lib class initialized');
    }
    
    // ------------------------------------------------------------------------

    /**
     * Run all checks to identify database problems
     *
     * @access public
     * @return void
     */
    public function database_check()
    {
        if ($orphan_accounts = $this->CI->napkin_m->getOrphanAccounts()) {
            $this->db_problems['orphan_accounts'] = count($orphan_accounts);
            $this->db_problems['total'] += count($orphan_accounts);
        }

        if ($orphan_accesses = $this->CI->napkin_m->getOrphanAccesses()) {
            $this->db_problems['orphan_accesses'] = count($orphan_accesses);
            $this->db_problems['total'] += count($orphan_accesses);
        }

        if ($invalid_emails = $this->CI->napkin_m->getInvalidEmails()) {
            $this->db_problems['invalid_emails'] = count($invalid_emails);
            $this->db_problems['total'] += count($invalid_emails);
        }
    }

    /**
     * Repair database problems
     *
     * @access public
     * @return void
     */
    public function database_repair()
    {        
        // Fix some invalid emails.
        $this->db_problems['fixed'] += $this->CI->napkin_m->fixEmails();

        // Delete accounts with invalid emails
        $this->db_problems['fixed'] += $this->CI->napkin_m->removeInvalidEmails();

        // Delete orphan accounts
        $this->db_problems['fixed'] += $this->CI->napkin_m->removeOrphanAccounts();

        // Delete orphan accesses
        $this->db_problems['fixed'] += $this->CI->napkin_m->removeOrphanAccesses();
    }
}
