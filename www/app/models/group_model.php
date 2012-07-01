<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class group_model extends CI_Model
{
    /**
     * Error(s) holder
     * @var array
     */
    private $errors = array();
    
    // ----------------------------------------------------------------------------------------------------------
    
    /**
     * Delete specified group
     * 
     * @access  public
     * @param   int     $groupID    Group ID
     * @return  bool
     */
    public function delGroup($groupID)
    {
        // disallow default group to be deleted
        if ($groupID == 0)
        {
            $this->set_error('not_allowed');
            return false;
        }
        
        // check if group exist
        if (!$this->isGroup($groupID))
        {
            $this->set_error('no_such_group');
            return false;
        }
        
        // move users to default group
        $data = array(
            'group_ID'  => 0,
        );
        $this->db->where('group_ID', (int)$groupID)->update('users_access', $data);
        
        // delete group
        $this->db->where('ID', $groupID);
        $this->db->delete('groups');
        
        return ($this->db->affected_rows() > 0) ? true : false;
    }
    
    /**
     * Save a group
     * 
     * @access  public
     * @param   string  $name       Group name
     * @param   string  $access     Access flags
     * @param   bool    $public     Group is public ?
     * @return  bool
     */
    public function saveGroup($groupID, $name, $access, $is_public)
    {
        if ($this->isGroup($name) === false)
        {
            $this->set_error('no_group');
            console_log('no_group');
            return false;
        }
        
        $data = array(
            'name'      => $name,
            'access'    => $access,
            'public'    => ((bool)$is_public) ? 1 : 0,
        );
        
        $this->db->where('ID', (int)$groupID)->update('groups', $data);
        
        return $this->db->affected_rows() == 1;   
    }
    
    /**
     * Add a new group
     * 
     * @access  public
     * @param   string  $name       Group name
     * @param   string  $access     Access flags
     * @param   bool    $public     Group is public ?
     * @return  bool
     */
    public function addGroup($name, $access, $is_public)
    {
        if ($this->isGroup($name))
        {
            $this->set_error('group_exist');
            return false;
        }
        
        $data = array(
            'name'      => $name,
            'access'    => $access,
            'public'    => ((bool)$is_public) ? 1 : 0,
        );
        
        return $this->db->insert('groups', $data);   
    }
    
    /**
     * Check of specified group exists
     * 
     * @access  public
     * @param   int|string  $name   Group name or ID
     * @return  bool
     */
    public function isGroup($name)
    {
        $column = 'name';
        
        if (is_numeric($name))
        {
            $column = 'ID';
        }
        
        $query = $this->db->select('ID', 'name')
                        ->where($column, $name)
                        ->limit(1)
                        ->get('groups');
                
        return ($query->num_rows() == 1) ? true : false;
    }
    
    /**
     * Get user group name on specified server
     * 
     * @access  public
     * @param   int     $userID
     * @param   int     $serverID
     * @return  int|bool            Group ID | False on error
     */
    public function getUserGroup($userID, $serverID)
    {        
        $query = $this->db->select('user_ID, server_ID, group_ID')
                    ->from('users_access')
                    ->where('user_ID =', $userID)
                    ->where('server_ID =', $serverID)
                    ->get();
        
        $result = $query->row();
        
        return (is_object($result) AND is_numeric($result->group_ID) ) ? $result->group_ID : false;   
    }
    
    /**
     * Fetch specified group data
     * 
     * @access  public
     * @param   int         $groupID    Group ID
     * @return  object|bool             False on error
     */
    public function getGroup($groupID)
    {
        $query = $this->db->select('ID, name, access, public')
                    ->where('ID', (int)$groupID)
                    ->limit(1)
                    ->get('groups');
        
        $user = $query->row();
        
        return is_object($user) ? $user : false;
    }
    
    /**
     * Fetch groups
     * 
     * @access  public
     * @param   int         $num
     * @param   int         $offset
     * @return  array|bool          False on error
     */
    public function get_groups($num, $offset)
    {
        $query = $this->db->select('ID, name, access, public')
                    ->limit($num, $offset)
                    ->get('groups');
        
        return ($query->num_rows()>0) ? $query->result_object() : false;
    }
    
    
    // ----------------------------------------------------------------------------------------------------------
        
    /**
	 * Set an error message
	 *
     * @access  public
     * @param   string  $error  Error message
     * @return  string   
	 */
	public function set_error($error)
	{
		$this->errors[] = $error;
        notify($this->lang->line($error), 'error'); // show error to user
        
		return $error;
	}
    
    /**
     * Get last error
     * 
     * @access  public
     * @return  string
     */
    public function get_error()
    {
        if ($this->is_error() === false)
        {
            return '';
        }
        
        return array_pop($this->errors);
    }
    
    /**
     * Check if any error has occured
     * 
     * @access  public
     * @return  bool
     */
    public function is_error()
    {
        return (count($this->errors) > 0) ? true : false;
    }
}

/* End of file */