<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Get Filenames by partial name
 *
 * Reads the specified directory and builds an array containing the filenames.  
 * Any sub-folders contained within the specified path are read as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	array	array of file partial names to include in results
 * @return	array
 */	
function get_filenames_by_partial_name($source_dir, $extensions)
{
    $_filedata = array();
    
    $realdir = realpath($source_dir);
    
    if($realdir === false)
        throw new InvalidArgumentException('Directory not exists or not accessible: "'.$source_dir.'"');
        
    $r_dir_iter = new RecursiveDirectoryIterator($realdir,
        RecursiveDirectoryIterator::CURRENT_AS_FILEINFO |
        RecursiveDirectoryIterator::SKIP_DOTS
    );
    $recur_iter = new RecursiveIteratorIterator($r_dir_iter);
    
    foreach($recur_iter as $finfo)
	{
        $basename       = $finfo->getBasename();

        array_walk($extensions, function($str) use ($basename, &$_filedata) { 
            if (strstr($basename, $str) !== false)
            {
                $_filedata[] = $basename;
            }
        });
        
    }

    return $_filedata;
}

/**
 * Get Filenames by Extension
 *
 * Reads the specified directory and builds an array containing the filenames.  
 * Any sub-folders contained within the specified path are read as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	array	array of file types to include in results
 * @return	array
 */	
function get_filenames_by_extension($source_dir, $extensions)
{
    static $_filedata = array();
    $realdir = realpath($source_dir);
    
    if($realdir === false)
        throw new InvalidArgumentException('Directory not exists or not accessible: "'.$source_dir.'"');
        
    $r_dir_iter = new RecursiveDirectoryIterator($realdir,
        RecursiveDirectoryIterator::CURRENT_AS_FILEINFO |
        RecursiveDirectoryIterator::SKIP_DOTS
    );
    $recur_iter = new RecursiveIteratorIterator($r_dir_iter);
    
    foreach($recur_iter as $finfo)
	{
        $basename       = $finfo->getBasename();
        $extensionpos   = strrpos($basename, '.');
		
        if($extensionpos !== false)
        {
            $extension = substr($basename, $extensionpos+1);
            
            if (in_array($extension, $extensions))
            {
                $_filedata[] = $basename;
            }
        }
        
        
    }
    
    return $_filedata;
}

/* End of file MY_file_helper.php */
/* Location: ./application/helpers/MY_file_helper.php */