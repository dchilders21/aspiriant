<?php
defined("ABSPATH") or die("");
if (!defined('DUPLICATOR_PRO_VERSION')) exit; // Exit if accessed directly

/**
 * Lightweight abstraction layer for common simple database routines
 *
 * Standard: PSR-2
 *
 * @package SC\DupPro\DB
 *
 */

class DUP_PRO_DB extends wpdb
{
	public static $remove_placeholder_escape_exists = null;

	public static function init()
	{
		global $wpdb;

		self::$remove_placeholder_escape_exists = method_exists($wpdb, 'remove_placeholder_escape');
	}

    /**
     * Get the requested MySQL system variable
     *
     * @param string $variable The database variable name to lookup
     *
     * @return string the server variable to query for
     */
    public static function getVariable($variable)
    {
        global $wpdb;
        $row = $wpdb->get_row("SHOW VARIABLES LIKE '{$variable}'", ARRAY_N);
        return isset($row[1]) ? $row[1] : null;
    }

    /**
     * Gets the MySQL database version number
     *
     * @param bool $full    True:  Gets the full version if available (i.e 10.2.3-MariaDB)
     *                      False: Gets only the numeric portion i.e. (5.5.6 -or- 10.1.2)
     *
     * @return false|string 0 on failure, version number on success
     */
    public static function getVersion($full = false)
    {
		global $wpdb;

        if ($full) {
            $version = self::getVariable('version');
        } else {
            $version = preg_replace('/[^0-9.].*/', '', self::getVariable('version'));
        }

		//Fall-back for servers that have restricted SQL for SHOW statement
		//Note: For MariaDB this will report something like 5.5.5 when it is really 10.2.1.
		//This mainly is due to mysqli_get_server_info method which gets the version comment
		//and uses a regex vs getting just the int version of the value.  So while the former
		//code above is much more accurate it may fail in rare situations
		if (empty($version)) {
			$version = $wpdb->db_version();
		}

        return empty($version) ? 0 : $version;
    }
	
	/**
     * Try to return the mysqldump path on Windows servers
	 *
     * @return boolean|string
     */
	public static function getWindowsMySqlDumpRealPath() {
		if(function_exists('php_ini_loaded_file'))
		{
			$get_php_ini_path = php_ini_loaded_file();
			if(file_exists($get_php_ini_path))
			{
				$search = array(
					dirname(dirname($get_php_ini_path)).'/mysql/bin/mysqldump.exe',
					dirname(dirname(dirname($get_php_ini_path))).'/mysql/bin/mysqldump.exe',
					dirname(dirname($get_php_ini_path)).'/mysql/bin/mysqldump',
					dirname(dirname(dirname($get_php_ini_path))).'/mysql/bin/mysqldump',
				);
				
				foreach($search as $mysqldump)
				{
					if(file_exists($mysqldump))
					{
						return str_replace("\\","/",$mysqldump);
					}
				}				
			}
		}
		
		unset($search);
		unset($get_php_ini_path);

		return false;
	}

    /**
     * Returns the mysqldump path if the server is enabled to execute it
	 *
     * @return boolean|string
     */
    public static function getMySqlDumpPath($is_executeble = true)
    {
        $global = DUP_PRO_Global_Entity::get_instance();
		
        //Is shell_exec possible
        if (!DUP_PRO_Shell_U::isShellExecEnabled()) {
            return false;
        }

        $custom_mysqldump_path = (strlen($global->package_mysqldump_path)) ? $global->package_mysqldump_path : '';

        //Common Windows Paths
        if (DUP_PRO_U::isWindows()) {
            $paths = array(
                $custom_mysqldump_path,
            	DUP_PRO_DB::getWindowsMySqlDumpRealPath(),
                'C:/xampp/mysql/bin/mysqldump.exe',
                'C:/Program Files/xampp/mysql/bin/mysqldump',
                'C:/Program Files/MySQL/MySQL Server 6.0/bin/mysqldump',
                'C:/Program Files/MySQL/MySQL Server 5.5/bin/mysqldump',
                'C:/Program Files/MySQL/MySQL Server 5.4/bin/mysqldump',
                'C:/Program Files/MySQL/MySQL Server 5.1/bin/mysqldump',
                'C:/Program Files/MySQL/MySQL Server 5.0/bin/mysqldump',
            );
        }
        //Common Linux Paths
        else {

            $paths = array(
                $custom_mysqldump_path,
                '/usr/local/bin/mysqldump',
                '/usr/local/mysql/bin/mysqldump',
                '/usr/mysql/bin/mysqldump',
                '/usr/bin/mysqldump',
                '/opt/local/lib/mysql6/bin/mysqldump',
                '/opt/local/lib/mysql5/bin/mysqldump',
                '/opt/local/lib/mysql4/bin/mysqldump',
            );

            //add possible executeable path if that exists instead of empty string
            $mysqldump = `which mysqldump`;
            if (DUP_PRO_U::isExecutable($mysqldump)) {
				$paths[]     = (!empty($mysqldump)) ? $mysqldump : '';
			}

            $mysqldump = dirname(`which mysql`)."/mysqldump";
            if (DUP_PRO_U::isExecutable($mysqldump)) {
				$paths[]    = (!empty($mysqldump)) ? $mysqldump : '';
			}

        }

        // Find the one which works
        foreach ($paths as $path) {
			if (file_exists($path)) {
				if ($is_executeble) {
					if (DUP_PRO_U::isExecutable($path)) return $path;
				}
				else {
					// This is option for validation check proccess only
					return $path;
				}
			}
		}

		unset($paths);

        return false;
    }

    /**
     * Returns all collation types that are assigned to the tables in
	 * the current database.  Each element in the array is unique
	 *
	 * @param array $excludeTables A list of tables to exclude from the search
	 *
     * @return array	Returns an array with all the collation types being used
     */
	public static function getTableCollationList($excludeTables)
	{
		global $wpdb;
		$collations = array();

		try {
			$query = $wpdb->get_results("SHOW TABLE STATUS FROM `{$wpdb->dbname}`");

			foreach($query  as $key => $row) {
				if (! in_array($row->Name, $excludeTables)) {
					if (! empty($row->Collation))
						$collations[] = $row->Collation;
				}
			}
			
			$collations = array_unique($collations, SORT_STRING);
			$collations = array_values($collations);
			return $collations;
			
		} catch (Exception $ex) {
			return $collations;
		}
	}

	/**
	 * Returns the correct database build mode PHP, MYSQLDUMP, PHPCHUNKING
	 *
	 * @return string	Returns a string with one of theses three values PHP, MYSQLDUMP, PHPCHUNKING
	 */
	public static function getBuildMode()
	{
        $global = DUP_PRO_Global_Entity::get_instance();

		if ($global->package_mysqldump) {
			return 'MYSQLDUMP';
		} else if($global->package_php_chunking) {
			return 'PHPCHUNKING';
		} else {
			return 'PHP';
		}
	}

	/**
     * Returns an escaped sql string
	 *
	 * @param string	$sql				The sql to escape
	 * @param bool		$removePlaceholderEscape	Patch for how the default WP function works.
	 *
     * @return boolean|string
	 * @also see: https://make.wordpress.org/core/2017/10/31/changed-behaviour-of-esc_sql-in-wordpress-4-8-3/
     */
    public static function escSQL($sql, $removePlaceholderEscape = false)
    {
		global $wpdb;

		$removePlaceholderEscape = $removePlaceholderEscape && self::$remove_placeholder_escape_exists;

		if ($removePlaceholderEscape) {
			return $wpdb->remove_placeholder_escape(@esc_sql($sql));
		} else {
			return @esc_sql($sql);
		}

    }
    
}

DUP_PRO_DB::init();