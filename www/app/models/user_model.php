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
 * User model
 * 
 * Manipulate users stored in database.
 * 
 * @package     CStrike-Regnick
 * @category    Models
 * @copyright   (c) 2011 - 2012 Gentle Software Solutions
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @link        http://www.gentle.ro/ 
 */
class user_model extends MY_Model
{
    
    /**
     * Password encryption methods
     * @var array
     */
    public $encrypt_methods = array('md5', 'none');
    
    // ----------------------------------------------------------------------------------------------------------
    
    /**
     * Update user access on specified server
     * 
     * @access  public
     * @param   int     $userID
     * @param   int     $serverID
     * @param   int     $groupID
     * @return  bool
     */
    public function saveAccess($userID, $serverID, $groupID)
    {        
        $this->load->model('group_model');
        
        if ($this->group_model->getUserGroup($userID, $serverID) === false)
        {
            return $this->addAccess($userID, $serverID, $groupID);
        }
        else
        {
            return $this->updateAccess($userID, $serverID, $groupID);
        }
    }
    
    /**
     * Update user access on specified server
     * 
     * @see     saveAccess()
     * @access  public
     * @param   int     $userID
     * @param   int     $serverID
     * @param   int     $groupID
     * @return  bool
     */
    private function updateAccess($userID, $serverID, $groupID)
    {
        $data = array(
            'group_ID' => (int)$groupID,
        );
        
        $this->db->where('user_ID =', (int)$userID)
            ->where('server_ID =', (int)$serverID)
            ->update('users_access', $data);
        
        return $this->db->affected_rows() == 1;
    }
    
    /**
     * Add access for specified user, on specified server
     * 
     * @see     saveAccess()
     * @access  private
     * @param   int     $userID
     * @param   int     $serverID
     * @param   int     $groupID
     * @return  bool
     */
    private function addAccess($userID, $serverID, $groupID)
    {
        $data = array(
            'user_ID'   => (int)$userID,
            'server_ID' => (int)$serverID,
            'group_ID'  => (int)$groupID
        );
        
        return $this->db->insert('users_access', $data);
    }
    
    /**
     * Delete user access on a server
     *
     * If serverID = 0, accesses from all servers will be deleted.
     * 
     * @access  public
     * @param   int     $userID     User ID
     * @param   int     $serverID   Server ID (Optional)
     * @return  bool
     */
    public function delAccess($userID, $serverID = 0)
    {
        if (!$this->user_exist($userID))
        {
            $this->set_error('no_such_user');
            return false;
        }        
        
        if ($serverID > 0) {
            if (!$this->server_model->isServer($serverID))
            {
                $this->set_error('no_such_server');
                return false;
            }
            
            $this->db->where('user_ID =', $userID)
                ->where('server_ID =', $serverID)
                ->delete('users_access');
        } else {
            $this->db->where('user_ID =', $userID)
                ->delete('users_access');
        }
        
        return $this->db->affected_rows() > 0;
    }
    
    // ----------------------------------------------------------------------------------------------------------
    
    /**
     * @deprecated
     * @see Regnick_auth::build_account_flags()
     */
    public function checkFlags($login, $type, $connection, $regnick)
    {
        // defaults
        //$type = 'b';
        
        if (is_steamid($login)) { $type = 'c'; }
        elseif (is_ip($login)) { $type = 'd'; }
        
        return $type.$connection.$regnick;
    }
    
    /**
     * Return servers array on wich user has no access
     * 
     * @access  public
     * @param   int     $userID
     * @param   array   $servers    Available servers
     * @return  array
     */
    public function match_access($userID, array $servers)
    {
        $access     = $this->change_key_id($this->getAllAccess($userID));
        
        return array_diff_assoc($servers, $access);
    }
    
    /**
     * Use server ID as array key id
     * 
     * @access  public
     * @param   array   $arr
     * @return  array
     */
    public function change_key_id($arr)
    {
        $newArr = array();
        
        foreach ($arr as $key)
        {
            $newArr[$key['ID']]['ID'] = $key['ID'];
            if (isset($key['address'])) { $newArr[$key['ID']]['address'] = $key['address']; }
            if (isset($key['name'])) { $newArr[$key['ID']]['name'] = $key['name']; }
            
            if (isset($key['group_name'])) { $newArr[$key['ID']]['group_name'] = $key['group_name']; }
        }
        
        return $newArr;
    }
    
    /**
     * Fetch user access from all servers
     * 
     * @access  public
     * @param   int         $userID
     * @param   bool        $with_srv_name  Fetch server name ?
     * @return  array|bool                  False on error
     */
    public function getAllAccess($userID, $with_srv_name = false)
    {
        $sql = '';
        
        if (!$with_srv_name)
        {
            $sql = 'SELECT 
                        acc.server_ID as ID, grp.name as group_name
                    FROM
                        '.$this->db->dbprefix('users_access').' as acc, 
                        '.$this->db->dbprefix('groups').' as grp 
                    WHERE (acc.user_ID = ?) 
                        AND (acc.group_ID = grp.ID);';
        }
        else
        {
            $sql = 'SELECT
                        srv.ID, srv.address, acc.server_ID, grp.name as group_name
                    FROM
                        '.$this->db->dbprefix('users_access').' as acc,
                        '.$this->db->dbprefix('groups').' as grp
                    INNER JOIN
                        '.$this->db->dbprefix('servers').' as srv
                    WHERE (acc.user_ID = ?) 
                        AND (acc.server_ID = srv.ID) 
                        AND (acc.group_ID = grp.ID);';
        }
        
        $query = $this->db->query($sql, array($userID)); 
        
        return $query->result_array();
    }
    
    /**
     * Save user data
     * 
     * @access  public
     * @param   int     $userID     User ID
     * @param   string  $login      User login
     * @param   string  $password   User password
     * @param   string  $email      User email
     * @param   bool    $is_active  Account is active ?
     * @param   string  $flags      Connection flags
     * @param   string  $notes      User notes
     * @return  bool
     */
    public function saveUser($userID, $login, $password = '', $email = '', $is_active, $flags, $notes = '')
    {
        if (!$this->user_exist($userID))
        {
            $this->set_error('user_does_not_exists');
            return false;
        }
        
        $data = array(
            'login'             => $login,
            'active'            => ((bool)$is_active) ? 1 : 0, 
            'account_flags'     => $flags,
            'notes'             => $notes,
        );
        
        if ($password != '') {
            $data = array_merge( $data, array('password' => $this->hash_password($password)) );
        }
        
        if ($email != '') {
            $data = array_merge( $data, array('email' => $email) );
        }
                
        $this->db->where('ID', (int)$userID)->update('users', $data);
        
        if ($this->db->affected_rows() == 1)
        {
            return true;
        }
        
        $this->set_error('error_on_save');
        return false;
    }
    
    /**
     * Add new account
     */
    public function user_add($login, $password, $email, $is_active, $flags)
    {
        if ($this->user_exist($login))
        {
            $this->set_error('user_exists');
            return false;
        }
        
        $this->load->helper('string'); // used to generate key
        
        $data = array(
            'login'             => $login,
            'password'          => $this->hash_password($password),
            'email'             => $email,
            'register_date'     => time(),
            'active'            => ((bool)$is_active) ? 1 : 0, 
            'activation_key'    => strtolower(random_string('alnum', 24)),
            'account_flags'     => $flags,
            'last_login'        => 0,
            'passwd_type'       => 0
        );
        
        return $this->db->insert('users', $data);
    }
    
    /**
     * Fetch users
     * 
     * @access  public
     * @param   int         $num
     * @param   int         $offset
     * @return  array|bool          False on error
     */
    public function get_users($num = 0, $offset = 0, $login = '')
    {
        if ( ($num > 0) OR ($offset>0) )
        {
            $query = $this->db->select('ID, login, register_date, active')
                        ->like('login', $login)
                        ->limit($num, $offset)
                        ->get('users');
        }
        else
        {
            $query = $this->db->select('ID, login, register_date, active')
                        ->like('login', $login)
                        ->get('users');
        }
        
        return ($query->num_rows()>0) ? $query->result_object() : false;
    }
    
    /**
     * Delete user
     * 
     * @access  public
     * @param   int     $userID     Username ID
     * @return  bool
     */
    public function delete_user($userID)
    {
        $this->db->where('ID', $userID);
        $this->db->delete('users');
        
        return ($this->db->affected_rows() > 0) ? true : false;
    }
    
    // ----------------------------------------------------------------------------------------------------------
    
    /**
     * Fetch specified user data
     * 
     * @access  public
     * @param   int         $userID     User ID
     * @return  object|bool             False on error
     */
    public function getSettings($userID)
    {
        $query = $this->db->select('ID, login, password, email, register_date, active, account_flags, notes')
                    ->where('ID', (int)$userID)
                    ->limit(1)
                    ->get('users');
        
        $user = $query->row();
        
        return is_object($user) ? $user : false;
    }
    
    /**
     * Update user personal settings
     * 
     * update_user_settings
     * 
     * @access  public
     * @param   int     $user_id    User ID
     * @param   array   $settings   New user settings
     * @return  bool
     */
    public function setSettings($user_id, array $settings)
    {
        $this->db->where('ID', $user_id)->update('users', $settings);
        
        if ($this->db->affected_rows() > 0)
        {
            return true;
        }
        
        $this->set_error('error_on_save');
        return false;
    }
    
    /**
     * Get avatar image from gravatar.com
     * 
     * @access  public
     * @param   string  $email  Email address
     * @param   int     $size   Avatar image size
     * @return  string          Avatar link
     */
    public function getAvatar($email, $size = 32)
    {
        $hash = md5( strtolower( trim( $email ) ) );
        
        return 'http://www.gravatar.com/avatar/'.$hash.'?s='.$size.'&r=g';
    }
    
    /**
     * Get username by ID
     * 
     * @access  public
     * @param   int     $id If omited, username will be retrieved from session (optional)
     * @return  string      False on error
     */
    public function getUsername($id = 0)
    {        
        if ($id > 0)
        {
            return $this->getRow((int)$id, 'login');
        }
        
        return $this->session->getData('username');
    }
    
    /**
     * Fetch userID by email address
     * 
     * @access  public
     * @param   string      $email  Email address
     * @return  bool|int            False on error
     */
    public function getUserIDbyEmail($email)
    {
        $query = $this->db->select('ID', 'email')
                    ->where('email', $email)
                    ->limit(1)
                    ->get('users');
        
        $user = $query->row();
        
        return ($query->num_rows() == 0) ? false : $user->ID;
    }
    
    /**
     * Fetch specified data from users table
     * 
     * user_data
     * 
     * @access  public
     * @param   string|int  $identity   Username or userID
     * @param   string      $request    Column name from where to fetch data
     */
    public function getRow($identity, $request)
    {
        $col = 'login';
        
        if (is_numeric($identity))
        {
            $col = 'ID';
        }
        
        $query = $this->db->select($col .', '.$request)
                    ->where($col, $identity)
                    ->limit(1)
                    ->get('users');
        
        $user = $query->row();
        
        return ($query->num_rows() == 0) ? false : $user->$request;
    }
    
    /**
     * Update a single row from users table
     * 
     * user_data_set
     * 
     * @access  public
     * @param   int     $user_id    Username ID
     * @param   string  $column     Column name
     * @param   string  $value      New value
     * @return  bool
     */
    public function setRow($user_id, $column, $value)
    {
        $data = array($column => $value);
        $this->db->where('ID', (int)$user_id)->update('users', $data);
        
        return $this->db->affected_rows() == 1;
    }
    
    /**
     * Check if specified email address exists
     * 
     * @access  public
     * @param   string  $email  Email address
     * @return  bool
     */
    public function email_exist($email)
    {
        $query = $this->db->select('email')
                        ->where('email', $email)
                        ->limit(1)
                        ->get('users');
                    
        $user = $query->row();
        
        return ($query->num_rows() == 1) ? true : false;
    }
    
    /**
     * Check if specified user exists
     * 
     * @access  public
     * @param   string  $identity   Login or ID
     * @return  bool
     */
    public function user_exist($identity)
    {
        $column = 'login';
        
        if (is_numeric($identity))
        {
            $column = 'ID';
        }
        
        $query = $this->db->select('ID, login, active')
                        ->where($column, $identity)
                        ->limit(1)
                        ->get('users');
                    
        $user = $query->row();
        
        return ($query->num_rows() == 1) ? true : false;
    }
    
    /**
     * Try to activate account
     * 
     * @access  public
     * @param   string  $key    Activation key
     * @return  bool
     */
    public function activate_account($key)
    {
        $data = array(
            'active' => 1,
        );
        
        $this->db->where('activation_key', $key)
            ->update('users', $data);
        
        return $this->db->affected_rows() == 1;
    }
    
    /**
     * Check if specified account is active
     * 
     * @access  public
     * @param   string  $identity   Username
     * @return  bool
     */
    public function user_is_active($identity)
    {
        $query = $this->db->select('ID, login, active')
                    ->where('login', $identity)
                    ->limit(1)
                    ->get('users');
                    
        $user = $query->row();

        return ( ($query->num_rows() == 1) AND ($user->active == 1) ) ? true : false;
    }
    
    /**
     * Encrypt password
     * 
     * @access  public
     * @param   string  $password   Clear text password
     * @return  string              Encrypted password
     */
    public function hash_password($password)
    {
        $enc_method = $this->config->item('password_encrypt');
        
        if (in_array($enc_method, $this->encrypt_methods) === false)
        {
            $enc_method = 'none';
        }
        
        return ($enc_method == 'none') ? $password : call_user_func($enc_method, $password);
    }
    
}

/* End of file */
