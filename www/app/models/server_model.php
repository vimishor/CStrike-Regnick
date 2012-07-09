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
 * Server model
 * 
 * Manipulate servers stored in database.
 * 
 * @package     CStrike-Regnick
 * @category    Models
 * @copyright   (c) 2011 - 2012 Gentle Software Solutions
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @link        http://www.gentle.ro/ 
 */
class server_model extends MY_Model {
    
    
    /**
     * Fetch all active users from public groups
     * 
     * @access  public
     * @param   int         $serverID
     * @return  array|bool              False on error
     */
    public function getMembers($serverID)
    {
        return $this->getUsers($serverID, true);
    }
    
    /**
     * Fetch all active users from non-public groups
     * 
     * @access  public
     * @param   int         $serverID
     * @return  array|bool              False on error
     */
    public function getTeam($serverID)
    {
        return $this->getUsers($serverID, false);
    }
    
    /**
     * Return all active users with access on specified server
     * 
     * @access  private
     * @param   int         $serverID
     * @param   bool        $public     Only from public groups ?
     * @return  array|bool              False on error
     */
    private function getUsers($serverID, $public = false)
    {
        $this->benchmark->mark('server_members_list_(SQL)_start');
        
        $public = ($public) ? 1 : 0;
        
        $sql = 'SELECT 
                    acc.user_ID, acc.server_ID, acc.group_ID, 
                    usr.login, 
                    grp.name 
                FROM
                    '.$this->db->dbprefix('users_access').' as acc, 
                    '.$this->db->dbprefix('users').' as usr, 
                    '.$this->db->dbprefix('groups').' as grp 
                WHERE (acc.group_ID > 0)
                    AND (usr.active = 1)  
                    AND (acc.server_ID = ?) 
                    AND (acc.user_ID = usr.ID)
                    AND (acc.group_ID = grp.ID)
                    AND (grp.public = ?);';
        
        $query = $this->db->query($sql, array((int)$serverID, (int)$public));
        
        $this->benchmark->mark('server_members_list_(SQL)_end');
        
        return $query->result_array(); 
    }
    
    // ----------------------------------------------------------------------------------------------------------
    
    
    /**
     * Delete specified server
     * 
     * @access  public
     * @param   int     $serverID    Server ID
     * @return  bool
     */
    public function delServer($serverID)
    {
        // disallow global server to be deleted
        if ($serverID == 0)
        {
            $this->set_error('not_allowed');
            return false;
        }
        
        // check if server exist
        if (!$this->isServer($serverID))
        {
            $this->set_error('no_such_server');
            return false;
        }
        
        $this->benchmark->mark('delete_server_(SQL)_start');
                
        // delete server
        $this->db->where('ID', $serverID);
        $this->db->delete('servers');
        
        $this->benchmark->mark('delete_server_(SQL)_end');
        
        return ($this->db->affected_rows() > 0) ? true : false;
    }
    
    /**
     * Fetch specified server data
     * 
     * @access  public
     * @param   int         $serverID   Server ID
     * @return  object|bool             False on error
     */
    public function getServer($serverID)
    {
        $this->benchmark->mark('get_server_(SQL)_start');
        
        $query = $this->db->select('ID, address, name')
                    ->where('ID', (int)$serverID)
                    ->limit(1)
                    ->get('servers');
        
        $server = $query->row();
        
        $this->benchmark->mark('get_server_(SQL)_end');
        
        return is_object($server) ? $server : false;
    }
    
    /**
     * Save a server
     * 
     * @access  public
     * @param   int     $serverID   Server ID
     * @param   string  $address    Server address
     * @param   string  $name       Server name (optional)
     * @return  bool
     */
    public function saveServer($serverID, $address, $name = '')
    {
        if (!$this->isServer($serverID))
        {
            $this->set_error('server_does_not_exist');
            return false;
        }
        
        $data = array(
            'address'   => $address,
            'name'      => $name,
        );
        
        $this->benchmark->mark('save_server_(SQL)_start');
        
        $this->db->where('ID', (int)$serverID)->update('servers', $data);
        
        $this->benchmark->mark('save_server_(SQL)_end');
        
        if ($this->db->affected_rows() == 1)
        {
            return true;
        }
        
        $this->set_error('error_on_save');
        return false;
    }
    
    /**
     * Add a new server
     * 
     * @access  public
     * @param   string  $address    Server address
     * @param   string  $name       Server name (optional)
     * @return  bool
     */
    public function addServer($address, $name = '')
    {
        if ($this->isServer($address))
        {
            $this->set_error('server_exist');
            return false;
        }
        
        $data = array(
            'address'   => $address,
            'name'      => $name,
        );
        
        return $this->db->insert('servers', $data);   
    }
    
    /**
     * Check of specified server exists
     * 
     * @access  public
     * @param   int|string  $address   Server address or ID
     * @return  bool
     */
    public function isServer($address)
    {
        $this->benchmark->mark('is_server_(SQL)_start');
        
        $column = 'address';
        
        if (is_numeric($address))
        {
            $column = 'ID';
        }
        
        $query = $this->db->select('ID', 'address')
                        ->where($column, $address)
                        ->limit(1)
                        ->get('servers');
        
        $this->benchmark->mark('is_server_(SQL)_end');
        
        return ($query->num_rows() == 1) ? true : false;
    }
    
    /**
     * Fetch all servers
     * 
     * @access  public
     * @param   bool        $with_global    Include server with ID = 0, that is used for global accounts ?
     * @param   int         $num            Results number
     * @param   int         $offset         Results offset number
     * @param   string      $return         Return data as object or array ? Values: 'obj' or 'arr'
     * @return  array|bool  False on error
     */
    public function getServers($with_global = false, $num = 100, $offset = 0, $return = 'obj')
    {
        $this->benchmark->mark('servers_list_(SQL)_start');
        
        $query = '';
        
        if ($with_global === true)
        {
            $query = $this->db->select('ID, address, name')
                        ->limit($num, $offset)
                        ->get('servers');
        }
        else
        {
            $query = $this->db->select('ID, address, name')
                        ->where('ID >', 0)
                        ->limit($num, $offset)
                        ->get('servers');
        }
        
        $this->benchmark->mark('servers_list_(SQL)_end');
        
        if ($return == 'obj')
        {
            return ($query->num_rows()>0) ? $query->result_object() : false;
        }
        else
        {
            return ($query->num_rows()>0) ? $query->result_array() : false;
        }
    }
    
    
}

/* End of file */