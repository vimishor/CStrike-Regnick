<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * @package		CStrike Regnick
 * @subpackage  Pagination
 * @version     1.0.1
 * @author		www.gentle.ro
 * @copyright	Copyright (c) 2009 - 2011, Gentle.ro 
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link		http://www.gentle.ro/proiecte/cstrike-regnick/
 * 
 */

/**
 * CStrike Regnick pagination class
 * 
 * This is a basic pagination class.
 * 
 * @author www.gentle.ro
 * @version 0.1.0
 */
class Pagination
{
    
    private $items_per_page;
    private $items_total;
    private $current_page;
    private $total_pages;

    
    public function __construct()
    {
    }
    
    public function setup($cfg = null)
    {
        if (is_array($cfg))
        {
            $this->items_per_page   = (isset($cfg['items_per_page']) AND is_numeric($cfg['items_per_page']) ) ? $cfg['items_per_page'] : 10;
            $this->items_total      = (isset($cfg['items_total']) AND is_numeric($cfg['items_total']) ) ? $cfg['items_total'] : 0;
            $this->current_page     = (isset($cfg['current_page']) AND is_numeric($cfg['current_page']) ) ? $cfg['current_page'] : 1;
        }
    }
    
    public function start()
    {
        $this->total_pages = ceil($this->items_total/$this->items_per_page);
        
        $start = ( ($this->current_page-1) < 1) ? 1 : $this->current_page-1;
        if ( ($this->current_page >= $this->total_pages) OR ($this->current_page == ($this->total_pages-1)) )
        {
            $start = $this->total_pages-2;
        }
        
        $end = ( ($start+4) >= $this->total_pages) ? $this->total_pages : $start+4;
        
        $ret = '<ul class="pagination">';
        
        for ($loop = $start-1; $loop <= $end; $loop++)
        {
            if ( ($loop > 0) AND ($loop > ($start-1)) )
            {
                if ($loop == $this->current_page)
                {
                    $ret .= '<li class="active"><a href="javascript:void(0);" name="'.$loop.'">'.$loop.'</a></li>';
                }
                else
                {
                    $ret .= '<li><a href="javascript:void(0);" name="'.$loop.'">'.$loop.'</a></li>';
                }
            }
        }
        $ret .= '</ul>';
        
        return $ret;
    }    
}

?>