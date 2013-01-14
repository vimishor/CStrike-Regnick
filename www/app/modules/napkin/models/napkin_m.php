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
 * Napkin model
 * 
 * Provides database access for napkin module
 * 
 * @package     CStrike-Regnick
 * @category    Models
 * @copyright   (c) 2011 - 2012 Gentle Software Solutions
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @link        http://www.gentle.ro/ 
 */
class napkin_m extends MY_Model {

    /**
     * Fetch accounts which have no coresponding entry in access table
     *
     * @access public
     * @return array
     */
    public function getOrphanAccounts()
    {
        $sql = "SELECT ID, login FROM ".$this->db->dbprefix('users')." 
                    WHERE not exists (SELECT access_ID, user_ID 
                        FROM ".$this->db->dbprefix('users_access')." 
                            WHERE ".$this->db->dbprefix('users_access').".user_ID = ".$this->db->dbprefix('users').".ID);";
        $result = $this->db->query($sql);

        return $result->result_array();
    }

    /**
     * Fetch accesses which have no coresponding entry in users table
     *
     * @access public
     * @return array
     */
    public function getOrphanAccesses()
    {
        $sql = "SELECT access_ID, user_ID FROM ".$this->db->dbprefix('users_access')."  
                    WHERE not exists (SELECT ID 
                        FROM ".$this->db->dbprefix('users')." 
                            WHERE ".$this->db->dbprefix('users').".ID = ".$this->db->dbprefix('users_access').".user_ID);";
        $result = $this->db->query($sql);

        return $result->result_array();
    }

    /**
     * Fetch invalid emails
     *
     * @access public
     * @return array
     */
    public function getInvalidEmails()
    {
        $sql = "SELECT ID, login, email 
                    FROM ".$this->db->dbprefix('users')." 
                        WHERE email NOT REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]\.[a-zA-Z]{2,4}$';";
        $result = $this->db->query($sql);

        return $result->result_array();        
    }

    /**
     * Try to fix emails
     *
     * @access public
     * @return int
     */
    public function fixEmails()
    {
        // replace invalid chars from email addreses
        $sql = "UPDATE ".$this->db->dbprefix('users')." 
                    SET email = REPLACE (REPLACE (email, '>', ''), '<', '');";
        $result = $this->db->query($sql);

        return $this->db->affected_rows();
    }

    /**
     * Remove accounts with invalid email
     *
     * @access public
     * @return int
     */
    public function removeInvalidEmails()
    {
        $sql = "DELETE FROM ".$this->db->dbprefix('users')." 
                    WHERE 
                        email NOT REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]\.[a-zA-Z]{2,4}$';";
        $result = $this->db->query($sql);

        return $this->db->affected_rows();
    }

    /**
     * Delete accounts which have no coresponding entry in access table
     *
     * @access public
     * @return int
     */
    public function removeOrphanAccounts()
    {
        $sql = "DELETE FROM ".$this->db->dbprefix('users')." 
                    WHERE not exists (SELECT access_ID, user_ID 
                            FROM ".$this->db->dbprefix('users_access')." 
                                WHERE ".$this->db->dbprefix('users_access').".user_ID = ".$this->db->dbprefix('users').".ID);";
        $result = $this->db->query($sql);

        return $this->db->affected_rows();
    }

    /**
     * Delete accesses which have no coresponding entry in users table
     *
     * @access public
     * @return int
     */
    public function removeOrphanAccesses()
    {
        $sql = "DELETE FROM ".$this->db->dbprefix('users_access')." 
                    WHERE not exists (SELECT ID FROM ".$this->db->dbprefix('users')." 
                        WHERE ".$this->db->dbprefix('users').".ID = ".$this->db->dbprefix('users_access').".user_ID);";
        $result = $this->db->query($sql);

        return $this->db->affected_rows();
    }
}
/* End of file */
