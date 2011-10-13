<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * @package		CStrike Regnick
 * @subpackage  Database
 * @version     1.0.0
 * @author		www.gentle.ro
 * @copyright	Copyright (c) 2009 - 2011, Gentle.ro 
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link		http://www.gentle.ro/proiecte/cstrike-regnick/
 * 
 */

/**
 * CStrike Regnick DB class
 * 
 * This is a basic MySQL class. Considering the small size of this project, 
 * for the momment this class is more then enough.
 * 
 * @author www.gentle.ro
 * @version 0.1.1
 */
class Database
{
    /**
     * Database login credentials
     */
    protected   $user,
                $pass,
                $host,
                $name;
    
    /**
     * Database connection resource
     */
    private     $link;
    
    /**
     * Error message holder
     */
    private     $error;
    
    /**
     * Database table columns collate
     */
	private $collate;
	
	/**
     * Database table columns charset
     */
	private $charset;

    /**
     * Last DB result
     */
    private $last_result;
    
    /**
     * Results object
     */
    private $result;

    /**
     * Affected rows by query
     */
    private $rows_count;
    public $insert_id;
    private $rows_affected;
    
    public function __construct($host, $user, $pass, $name)
    {
        // 1. set data
        $this->host     = $host;
        $this->user     = $user;
        $this->pass     = $pass;
        $this->name     = $name;
        $this->cache    = true;
        
        // 2. connect to server
        $this->connect();
        
        // 3. set charset and collate
        $this->set_charset();
        
        // 4. if connected, select DB        
        $this->db_select(); 
    }
    
    public function query($sql)
    {
        if ($sql == '')
            echo 'Invalid MySQL query.';
        
        //DEV-NOTE: This is NOT mysql safe
        return $this->_query($sql);
    }
    
    private function _query($sql)
    {
        if (!$this->is_connected())
            $this->connect();
        
        $this->result   = @mysql_query($sql,$this->link);
        
        if ( $this->error = mysql_error( $this->link ) ) {
            // print last error
			echo '<div class="error">';
            echo '<strong>[MySQL Error]</strong> '. $this->getError();
            echo '</div>';
			return false;
		}
        
        if ( preg_match( '/^\s*(create|alter|truncate|drop) /i', $sql) ) {
			#$return_val = $this->result;
            $this->last_result = $this->result;
		} 
        elseif ( preg_match( '/^\s*(insert|delete|update|replace) /i', $sql ) )
        {
            $this->rows_affected = mysql_affected_rows( $this->link );
            
            // Take note of the insert_id
			if ( preg_match( '/^\s*(insert|replace) /i', $sql ) ) {
				$this->insert_id = mysql_insert_id($this->link);
			}
			// Return number of rows affected
            $this->last_result = $this->rows_affected;
        }
        else
        {
            while ( $row = @mysql_fetch_object( $this->result ) )
            {
                $this->last_result[$this->rows_count] = $row;
                $this->rows_count++;
    		}            
        }
        
        @mysql_free_result( $this->result );
                
        return $this->last_result;
    }
    
    public function num_rows()
    {
        return $this->rows_count;
    }
    /**
	 * Escapes content by reference for insertion into the database, for security
	 *
	 * @uses _real_escape()
	 * @since 0.1.1
	 * @param string $string to escape
	 * @return void
	 */
	function escape_by_ref( &$string ) {
		$string = $this->_escape( $string );
	}
    
    /**
     * Prepares a SQL query for safe execution.
     * 
     * @author Wordpress Team <http://www.wordpress.org>
     * 
     * @since 0.1.1
     * 
     * @param string $query Query statement with sprintf()-like placeholders
     * @param array|mixed $args The array of variables to substitute into the query's placeholders if being called like
	 * 	{@link http://php.net/vsprintf vsprintf()}, or the first variable to substitute into the query's placeholders if
	 * 	being called like {@link http://php.net/sprintf sprintf()}.
	 * @param mixed $args,... further variables to substitute into the query's placeholders if being called like
	 * 	{@link http://php.net/sprintf sprintf()}.
	 * @return null|false|string Sanitized query string, null if there is no query, false if there is an error and string
	 * 	if there was something to prepare
     */
    public function prepare($query = null)
    {
        if ( is_null($query))
            return;

        $args = func_get_args();
		array_shift( $args );
        
        // If args were passed as an array (as in vsprintf), move them up
		if ( isset( $args[0] ) && is_array($args[0]) )
			$args = $args[0];
		$query = str_replace( "'%s'", '%s', $query ); // in case someone mistakenly already singlequoted it
		$query = str_replace( '"%s"', '%s', $query ); // doublequote unquoting
		$query = preg_replace( '|(?<!%)%s|', "'%s'", $query ); // quote the strings, avoiding escaped strings like %%s
		array_walk( $args, array( &$this, 'escape_by_ref' ) );
		return @vsprintf( $query, $args );
    }
    
    /**
     * Sanitize data for safe use
     * 
     * @since 0.1.1
     * 
     * @param string|array $str Data to escape
     * @return string|array
     */
    public function escape($str)
    {
        return $this->_escape($str);
    }
    
    
    /**
     * Sanitize sql for safe use.
     * 
     * @see mysql_real_escape_string()
     * @see mysql_escape_string()
     * @see addslashes()
     * 
     * @since 0.1.1
     * 
     * @param string|array $data String to sanitize
     * @return string|array
     */
    private function _escape($str)
    {
        if (is_array($str))
		{
			foreach($str as $key => $val)
	   		{
				$str[$key] = $this->_escape($val);
	   		}
	   		return $str;
		}

        if (function_exists('mysql_real_escape_string') AND $this->is_connected() )
		{
			$str = mysql_real_escape_string($str, $this->link);
		}
		elseif (function_exists('mysql_escape_string'))
		{
			$str = mysql_escape_string($str);
		} 
		else
		{
			$str = addslashes($str);
		}
        
        return $str;
    }
    
    
    /**
     * Select MySQL database
     */
    private function db_select()
    {
        if(!$this->is_connected())
        {
            $this->show_error('Error: Not connected to MySQL. '.$this->getError() );
        }
        
        if ( !@mysql_select_db($this->name) )
        {
            $this->show_error('Error selecting database '.$this->name);
        }
    }
      
    
    /**
     * Connect to MySQL server
     * 
     * @return true Returns only 1 valie: TRUE on success.
     */
    public function connect()
    {
        // are we already connected to server ?
        if(is_resource($this->link) || @mysql_ping($this->link))
        {
            return true;
        }
                
        $this->link = mysql_connect($this->host, $this->user, $this->pass);
        
        if (!$this->is_connected())
        {
            $this->show_error('Error connecting to MySQL.');
        }
        
        return true;
    }
    
    /**
     * Disconnect from MySQL server
     */
    public function disconnect()
    {
        if ($this->is_connected())
        {
            @mysql_close($this->link);
        }
    }
    
    
    /**
     * Check if exist an active MySQL connection
     * 
     * @return bool False if we are not connected.
     */
    public function is_connected()
    {
        return (is_resource($this->link)) ? true : false;
    }
    
    
    #region Error handler
    /**
     * Fetch the last error
     * 
     * @return string Error message
     */
    public function getError()
    {
        return @mysql_error($this->link);
    }
    
    
    /**
     * Output error(s) and die
     */
    private function show_error($message)
    {
        die('[DB]: '.$message);
    }
    #endregion
    
    
    #region DB charset setup
    /**
     * Set MySQL connection charset
     */
    private function init_charset()
	{
        $this->charset = (defined('DB_CHARSET')) ? DB_CHARSET : 'utf8';
        $this->collate = (defined('DB_COLLATE')) ? DB_COLLATE : 'utf8_general_ci';
	}
    
    /**
     * Prepare data for seeting up MySQL charset and collate
     */
    private function set_charset()
    {
        if (!$this->is_connected())
        {
            $this->show_error('No connection available to set charset.');
        }
        
        $this->init_charset();
        
        if ( function_exists( 'mysql_set_charset' ) && $this->has_cap( 'set_charset', $this->link ) ) 
        {
            mysql_set_charset( $this->charset, $this->link );
        }
        else
        {
            $query = $this->query('SET NAMES %s', $this->charset);
            $query .= $this->query(' COLLATE %s', $this->collate);
            mysql_query($query, $this->link);
        }
    }
    #endregion
    
    
    #region MySQL server info
    /**
     * Retrieve server info
     * 
     * @since 0.1.1
     * 
     * @return string|bool FALSE on error.
     */
    public function server_info()
    {
        if ($this->is_connected())
        {
            return preg_replace( '/[^0-9.].*/', '', mysql_get_server_info( $this->link ));
        }

        return false;
    }
    
    
    /**
	 * Determine if a database supports a particular feature
	 *
     * @author Wordpress Team <http://www.wordpress.org>
	 *
     * @since 0.1.0
	 *
	 * @param string $db_cap the feature
	 * @return bool
	 */
	private function has_cap( $db_cap ) {
		$version = $this->server_info();

		switch ( strtolower( $db_cap ) ) {
			case 'collation' :    // @since 2.5.0
			case 'group_concat' : // @since 2.7
			case 'subqueries' :   // @since 2.7
				return version_compare( $version, '4.1', '>=' );
			case 'set_charset' :
				return version_compare($version, '5.0.7', '>=');
		};

		return false;
	}
    
    
    /**
     * Retrive current system status
     * 
     * @since 0.1.1
     * 
     * @return array|bool FALSE on error.
     */
    public function server_status()
    {
        if ($this->is_connected())
        {
            return explode('  ', mysql_stat($this->link) );
        }

        return false;
    }
    #endregion
        
    
    /**
     * Clean data before vacation :)
     */
    public function __destruct()
    {
        $this->disconnect();
        
        $this->link = null;
    }
}

//TODO: implement cache

?>