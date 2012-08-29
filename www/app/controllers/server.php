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
 * Server controller
 * 
 * Provides servers listing
 * 
 * @package     CStrike-Regnick
 * @category    Controllers
 * @copyright   (c) 2011 - 2012 Gentle Software Solutions
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @link        http://www.gentle.ro/ 
 */
class Server extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Fetch server members
     * 
     * @access  public
     * @return  void
     */
    public function members($serverID, $page = 0)
    {
        $this->load->model('server_model');
        
        $this->load->library('pagination');
        $config['per_page']     = $this->config->item('results_per_page');
        $config['base_url']     = base_url().'/server/'. (int)$serverID .'/members/';
        $config['total_rows']   = count($this->server_model->getMembers($serverID));
        $config['uri_segment']  = 4;
        $this->pagination->initialize($config);
        
        $data = array(
            'page_title'    => lang('server_members'),
            'page_subtitle' => '',
            'users'         => $this->server_model->getMembers($serverID, $config['per_page'], $page),
            'server_name'   => $this->server_model->getServer($serverID)->address,
        );
        
        Events::trigger('members_list', $data);
        
        $this->template->set_layout('one_col')->build('server/team', $data);
    }
    
    /**
     * Fetch server administration team
     * 
     * @access  public
     * @return  void
     */
    public function team($serverID, $page = 0)
    {
        $this->load->model('server_model');
        
        $this->load->library('pagination');
        $config['per_page']     = $this->config->item('results_per_page');
        $config['base_url']     = base_url().'/server/'. (int)$serverID .'/team/';
        $config['total_rows']   = count($this->server_model->getTeam($serverID));
        $config['uri_segment']  = 4;
        $this->pagination->initialize($config);
        
        $data = array(
            'page_title'    => lang('server_team'),
            'page_subtitle' => '',
            'users'         => $this->server_model->getTeam($serverID, $config['per_page'], $page),
            'server_name'   => $this->server_model->getServer($serverID)->address,
        );
        
        Events::trigger('team_list', $data);
        
        $this->template->set_layout('one_col')->build('server/team', $data);
    }
    
    /**
     * List servers
     * 
     * @access  public
     * @return  void
     */
	public function server_list($page = 0)
	{
        $this->load->model('server_model');
        $this->load->library('pagination');
        // + pagination config
        $config['base_url']     = site_url('server/list');
        $config['total_rows']   = $this->db->where('ID >', DEFAULT_SERVER_ID)->count_all_results('servers');
        $config['per_page']     = $this->config->item('results_per_page');
        $config['uri_segment']  = 3;
        $this->pagination->initialize($config);
        // - pagination config 
        
        $data = array(
            'page_title'    => lang('community_servers'),
            'page_subtitle' => 'Play with joy !',
            'servers'       => $this->server_model->getServers(false, $config['per_page'], $page),
        );
        
        Events::trigger('server_list', $data);
        
        $this->template->set_layout('one_col')->build('server/list', $data);
	}
}

/* End of file server.php */
/* Location: ./application/controllers/server.php */