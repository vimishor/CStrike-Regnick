<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * @package		CStrike Regnick
 * @version     1.0.0
 * @author		www.gentle.ro
 * @copyright	Copyright (c) 2009 - 2011, Gentle.ro 
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link		http://www.gentle.ro/proiecte/cstrike-regnick/
 * 
 */

/**
 * CStrike Regnick Input class
 * 
 * Custom rewrite based on _GET requests. All sanitization is made via
 * regex rule "ALLOWED_CHARS", defined in config.php
 * 
 * Example for a GET request to: www.domain.tld/custom/rewrite/ 
 * <code>
 * GET: www.domain.tld/my/custom/rewrite/
 * 
 * $INP = new Input();
 * $INP->sergment(2); // return: custom
 * $INP->sergment(3); // return: rewrite
 * $INP->sergment(4); // return: (bool) false
 * </code>
 * 
 * @author www.gentle.ro
 * @version 0.1.0
 */
class Input
{
    private $segments;
    private $uri_string;
    
    public function __construct()
    {
        $this->segments = array();
        
        $this->uri_string = $this->parse_requested_uri();
        
        $this->_explode_segments();
        
        $this->_regenerate_segments();
        
        $this->_reindex_segments();
    }
    
    private function parse_requested_uri()
    {
        if ( ! isset($_SERVER['REQUEST_URI']) OR $_SERVER['REQUEST_URI'] == '')
		{
			return '';
		}
        
        $request_uri = preg_replace("|/(.*)|", "\\1", str_replace("\\", "/", $_SERVER['REQUEST_URI']));
        
        if ($request_uri == '' OR $request_uri == SELF)
		{
			return '';
		}
        
        $parsed_uri = explode("/", $request_uri);
        $i = 0;
		foreach(explode("/", FCPATH.SELF) as $segment)
		{
			if (isset($parsed_uri[$i]) && $segment == $parsed_uri[$i])
			{
				$i++;
			}
		}
        
        $parsed_uri = implode("/", array_slice($parsed_uri, $i));

		if ($parsed_uri != '')
		{
			$parsed_uri = '/'.$parsed_uri;
		}

		return $parsed_uri;
    }
    
    private function _explode_segments()
    {
        foreach(explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $this->uri_string)) as $val)
		{
			$val = trim($this->filter($val));

			if ($val != '')
			{
				$this->segments[] = $val;
			}
		}
    }
    
    /**
     * Remove subdirectory(s) from $this->segments array.
     * 
     * @since 1.0.0
     */
    private function _regenerate_segments()
    {
        if ($this->_in_subdir() === true)
        {
            $tmp = explode('/', $_SERVER['PHP_SELF']);            
             
            foreach ($tmp as $no => $var)
            {
                $key = array_search($var, $this->segments);
                
                if ($key !== false)
                {
                    unset($this->segments[$key]);
                }

            }            
        }
    }
    
    /**
     *  First segment is supposed to start from "n". This function reindexes 
     * segments array to accomplish this.
     * 
     * @since 1.0.0
     */
    private function _reindex_segments()
    {
        array_unshift($this->segments, NULL);   
    }
    
    /**
     * Sanitize each request based on a regex rule 
     * defined as "ALLOWED_CHARS" in config.php
     * 
     * @since 1.0.0
     * 
     * @param string $str String to sanitize
     * @return string
     */
    private function filter($str)
    {
        return preg_replace('/[^' . ALLOWED_CHARS . ']+/i', "", $str);
    }
    
    /**
     * Return sanized segment from _GET request
     * 
     * @since 1.0.0
     * 
     * @param int $no Segment number
     * @return string|bool
     */
    public function segment($no)
    {
        return (!isset($this->segments[$no])) ? false : $this->segments[$no];
    }
    
    /**
     * Return total number of segments from the current _GET request
     * 
     * @since 1.0.0
     * 
     * @return int
     */
    public function segments_count()
    {
        return count($this->segments);
    }
    
    /**
     * Check if script is running in subdirectory.
     * This function is used at segments reindex on _GET request.
     * 
     * @since 1.0.0
     * @see _reindex_segments()
     * 
     * @return bool
     */
    private function _in_subdir()
    {
        preg_match('/^\/([^\/]+)/', $_SERVER['PHP_SELF'], $tmp);
                
        if ($tmp[1] === 'index.php')
            return false;
        else
            return true;        
    }
    
}

?>