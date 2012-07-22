<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Navigation
 * 
 * Navigation library provides simple navigation (one level deep) for CStrike-Regnick, 
 * without a database backend.
 * 
 * @package     CStrike-Regnick
 * @subpackage  Module
 * @category    Navigation
 * @copyright   2012 Gentle Software Solutions
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version     1.0.0
 * @author      Alexandru G. <www.gentle.ro>
 */
class Navigation {
    
    protected $_CI;
    
    /**
     * Menu items
     * @var array
     */
    protected $_menu = array();
    
    /**
     * HTML output
     * @var string
     */
    protected $_html = null;
    
    /**
     * Allowed groups
     * @var array
     */
    protected $_groups = array('public', 'member', 'owner');
    
    /**
     * Assigns some access flags to those groups
     * @var array
     */
    protected $_flags = array(
        'public'    => 'a',
        'member'    => 'ab',
        'owner'     => 'abc'
    );
        
    /**
     * CSS classes
     */
    public $ul_parent   = 'nav nav-tabs nav-stacked';
    public $li_parent   = '';
    public $link_class  = '';
        
    public function __construct()
    {
        $this->_CI =& get_instance();
        $this->_CI->load->config('navigation/navigation', true);
        $this->_menu = $this->_CI->config->item('navigation');
    }
    
    /**
     * Fetch HTML to output
     * 
     * @access  public
     * @param   string  $group  Group name for which to return output
     * @return  string          HTML data
     */
    public function show($group = 'public')
    {
        $this->_html = null;
        
        // prevent infinite execution
        if ( !is_array($this->_menu))
        {
            return '';
        }
        
        // supposedly we have no menu items defined.
        if (count($this->_menu)<1)
        {
            $this->to_html($group);
        }
        
        $this->to_html($group);
        
        return $this->_html;
    }
    
    /**
     * Transform menu array in HTML
     * 
     * @access  public
     * @param   array   $items      Menu items
     * @param   bool    $is_child   This item is a child ?
     * @return  void
     */
    public function to_html($group)
    {
        // do not allow usage of custom groups
        if (!in_array($group, $this->_groups))
        {
            return '';
        }
        
        // do we have items defined for this group ?
        if (!isset($this->_menu[$group]) OR (count($this->_menu[$group])<1) )
        {
            return '';
        }
        
        $this->append_html('<ul class="'.$this->ul_parent.'">');
                       
        foreach ( $this->_menu[$group] as $key => $item )
        {            
            // current user can use this menu item ?
            if (!$this->user_can_use($group))
            {
                return '';
            }
            
            $this->append_html('<li class="'. $this->li_parent .'"><a class="'. $this->link_class .'" href="'. $item['link'] .'">'. $item['label'] .'</a></li>');
        }
        
        $this->append_html('</ul>');
    }
    
    /**
     * Check if current user can access items from specified menu group
     * 
     * @access  public
     * @param   string  $menu_group Group name
     * @return  bool
     */
    public function user_can_use($menu_group)
    {
        return $this->user_has_flag($this->_flags[$menu_group]);
    }
    
    /**
     * Check if current user has specified flag
     * 
     * @access  public
     * @param   string  $flag
     * @return  bool
     */
    public function user_has_flag($flag)
    {
        $user_flags = $this->get_user_access();
        
        return (($user_flags) AND (strpos($user_flags, $flag) !== false) ) ? true : false;
    }
    
    /**
     * Get user access flags
     * 
     * @access  public
     * @return  string
     */
    public function get_user_access()
    {        
        if ($this->_CI->session->userdata('user_id') AND $this->_CI->regnick_auth->isOwner($this->_CI->session->userdata('user_id')))
        {
            return $this->_flags['owner'];
        }
        
        if ($this->_CI->session->userdata('user_id'))
        {
            return $this->_flags['member']; 
        }
        
        return $this->_flags['public'];
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Store HTML data that will be sent to browser
     * 
     * @access  private
     * @param   string  $html
     * @return  void
     */
    private function append_html($html)
    {
        $this->_html .= $html;
    }
}