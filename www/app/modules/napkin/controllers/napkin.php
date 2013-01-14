<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Napkin module (aka maintenance module)
 * 
 * Provides a few methods to maintain a clean and healthy CStrike-Regnick 
 * installation.
 * 
 * @package     CStrike-Regnick
 * @category    Modules
 * @copyright   (c) 2011 - 2012 Gentle Software Solutions
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @link        http://www.gentle.ro/ 
 */
class Napkin extends ACP_Controller
{    
    public function __construct()
    {
        parent::__construct();

        $this->load->library('napkin_lib');
    }
    
    // ------------------------------------------------------------------------

    public function index()
    {
        $data = array(
            'page_title'    => 'Application maintenance',
            'page_subtitle' => 'Use a napkin to clean this CStrike-Regnick installation',
            'database'      => $this->napkin_lib->db_problems,
        );
        $this->template->set_layout('one_col')->build('index', $data);
    }

    /**
     * Database check and repair
     *
     * @access public
     * @param  string $action Action to be performed on database (ex: 'check' or 'repair')
     * @return void
     */
    public function database($action = null)
    {
        switch ($action) {
            case 'check':
                $this->napkin_lib->database_check();
                break;
            
            case 'repair':
                $this->napkin_lib->database_repair();
                break;
        }

        $data = array(
            'page_title'    => 'Application maintenance',
            'page_subtitle' => 'Use a napkin to clean this CStrike-Regnick installation',
            'database'      => $this->napkin_lib->db_problems,
        );
        $this->template->set_layout('one_col')->build('index', $data);
    }
}
