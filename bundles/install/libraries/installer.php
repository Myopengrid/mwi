<?php namespace Install;

use Laravel\Config;
use Laravel\Session;
use Laravel\Database as DB;
use Laravel\Bundle;

use Laravel\Database\Schema;
use Laravel\Database\Connectors\MySQL;
use Laravel\Database\Connectors\Postgres;
use Laravel\Database\Connectors\SQLite;
use Laravel\Database\Connectors\SQLServer;

use Laravel\Str;
use Laravel\File;
use Laravel\Log;
use Laravel\Messages;

class Installer {
    

    public static $php_version;

    public static $mysql_server_version;
    public static $mysql_client_version;
    public static $gd_version;
    public static $curl_version;

    public static $apache_version;

    public static $requeriments = array();
    public static $recomended = array();
    public static $requeriments_passed = false;

    public static $errors;

    public function __construct()
    {
        if( !isset(static::$errors) )
        {
            if(Session::started() and Session::has('errors'))
            {
                static::$errors = Session::get('errors');
            }
            else
            {
               static::$errors = new Messages;
            }
        }
    }

    public static function make()
    {
        return new static();
    }

    public static function set_app_key($arguments = array())
    {
        $key = Str::random(array_get($arguments, 0, 32));

        // Set application config file key
        $config_path = path('app').'config'.DS.'application'.EXT;
        $config = File::get($config_path);
        $newConfig = str_replace("'key' => '',", "'key' => '{$key}',", $config, $count);
        if(isset($newConfig) and $newConfig != '')
        {
            if ($count > 0)
            {
                File::put($config_path, $newConfig);
                Log::info('App configuration updated with secure key');
            }
        }
        else
        {
            Log::error('App configuration secure was not updated with secure key. A key already exists.');
        }
    }

    public static function set_app_index()
    {
        // Set application config file index.php
        $config_path = path('app').'config'.DS.'application'.EXT;
        $config = File::get($config_path);
        $newConfig = str_replace("'index' => 'index.php',", "'index' => '',", $config, $count);
        if(isset($newConfig) and $newConfig != '')
        {
            if ($count > 0)
            {
                File::put($config_path, $newConfig);
                Log::info('App configuration \'index\' => \'index.php\' was set to \'index\' => \'\' (mod_rewrite).');
            }
        }
        else
        {
            Log::error('Failed to update app index.php configuration.');
        }
    }

    public static function set_app_installed()
    {
        // Set application config file index.php
        $config_path = path('app').'config'.DS.'application'.EXT;
        $config = File::get($config_path);
        $newConfig = str_replace("'installed' => false,", "'installed' => true,", $config, $count);
        if(isset($newConfig) and $newConfig != '')
        {
            if ($count > 0)
            {
                File::put($config_path, $newConfig);
                Log::info('App flag installed set');
            }
        }
        else
        {
            Log::error('App flag installed failed.');
        }
    }

    public static function is_complete()
    {
        return Config::get('application.installed');
    }

    ////////////////////////////////////////////////
    //////////// STEP 1 FUNCTIONS //////////////////
    ////////////////////////////////////////////////

    public static function check_server($data = null)
    {
        // Check php 5.3
        if(!self::php_acceptable('5.3'))
            return false;

        // uses the FileInfo library to detect files' mime-types
        if(!self::fileinfo_acceptable())
            return false;

        //uses the Mcrypt library for encryption and hash generation
        if(!self::mcrypt_acceptable())
            return false;

        // Check Mysql
        // SQLite, MySQL, PostgreSQL, or SQL Server PDO drivers.
        if(!self::database_acceptable('mysql'))
            return false;

        // Check Zlib
        if(!self::zlib_acceptable())
            return false;

        // Check Json
        if(!self::json_acceptable())
            return false;

        // We should be good
        return true;
    }

    public static function check_requeriments()
    {
        // Check php 5.3
        self::$requeriments_passed = self::php_acceptable('5.3');

        // uses the FileInfo library to detect files' mime-types
        self::$requeriments_passed = self::fileinfo_acceptable();

        //uses the Mcrypt library for encryption and hash generation
        self::$requeriments_passed = self::mcrypt_acceptable();

        // Check Mysql
        // SQLite, MySQL, PostgreSQL, or SQL Server PDO drivers.
        self::$requeriments_passed = self::database_acceptable('mysql');

        // Check Zlib
        self::$requeriments_passed = self::zlib_acceptable();

        // Check Json
        self::$requeriments_passed = self::json_acceptable();
    }

    public static function check_recomended()
    {
        // Check php 5.3
        self::gd_acceptable();

        // uses the FileInfo library to detect files' mime-types
        self::curl_acceptable();

        //uses the Mcrypt library for encryption and hash generation
        self::xmlrpc_acceptable();
    }

    public static function php_acceptable($version = null)
    {
        
        // Set the PHP version
        //self::$php_version = phpversion();
        self::$php_version = preg_replace('/[^0-9\.]/','', phpversion());
        // Is this version of PHP greater than minimum version required?
        $passed =  ( version_compare(PHP_VERSION, $version, '>=') ) ? TRUE : FALSE;
        if($passed)
        {
            self::$requeriments['php'] = array(
                'title'       => 'PHP',
                'type'        => 'Core',
                'version'     => phpversion(),
                'requeriment' => 'Sagui requires PHP version '.$version.' or higher',
                'report'      => 'Your server have Php installed',
                'status'      => $passed,
                'passed'      => $passed,
            ); 
        }
        else
        {
            self::$requeriments['php'] = array(
                'title'       => 'PHP',
                'type'        => 'Required',
                'version'     => phpversion(),
                'requeriment' => 'Sagui requires PHP version '.$version.' or higher',
                'report'      => 'Your server don\'t have required Php version',
                'status'      => $passed,
                'passed'      => $passed,
            ); 
        }
        
        return $passed;
    }

    public static function fileinfo_acceptable($version = null)
    {
        $passed = extension_loaded('fileinfo');
        if($passed === true)
        {
            self::$requeriments['fileinfo'] = array(
                'title'       => 'FileInfo',
                'type'        => 'Required',
                'version'     => 'N/A',
                'requeriment' => 'Allows retrieval of information regarding a vast majority of file types. This information may include dimensions, quality, length etc.',
                'report'      => 'Your server have fileinfo enabled',
                'status'      => $passed,
                'passed'      => $passed,
            ); 
        }
        else
        {
            self::$requeriments['fileinfo'] = array(
                'title'       => 'FileInfo',
                'type'        => 'Required',
                'version'     => 'N/A',
                'requeriment' => 'Allows retrieval of information regarding a vast majority of file types. This information may include dimensions, quality, length etc.',
                'report'      => 'Your server don\'t have fileinfo installed/enabled. Please install or enable FileInfo extension',
                'status'      => $passed,
                'passed'      => $passed,
            ); 
        }
        return $passed;
    }

    public static function mcrypt_acceptable($version = null)
    {
        $passed = extension_loaded('mcrypt');
        if($passed === true)
        {
            self::$requeriments['mcrypt'] = array(
                'title'       => 'Mcrypt',
                'type'        => 'Required',
                'version'     => 'N/A',
                'requeriment' => 'Sagui uses the Mcrypt library for encryption and hash generation.',
                'report'      => 'Your server have mcrypt enabled',
                'status'      => $passed,
                'passed'      => $passed,
            ); 
        }
        else
        {
            self::$requeriments['mcrypt'] = array(
                'title'       => 'Mcrypt',
                'type'        => 'Required',
                'version'     => 'N/A',
                'requeriment' => 'Sagui uses the Mcrypt library for encryption and hash generation.',
                'report'      => 'Your server don\'t have Mcrypt installed/enabled. Please install or enable Mcrypt extension',
                'status'      => $passed,
                'passed'      => $passed,
            ); 
        }
        return $passed;
    }

    public static function database_acceptable($type = 'mysql')
    {
        if($type == 'mysql')
        {
            $passed = function_exists('mysql_connect');
            if($passed === true)
            {
                self::$requeriments['database'] = array(
                    'title'       => 'MySQL',
                    'type'        => 'Required',
                    'version'     => 'Not checked yet',
                    'requeriment' => 'Sagui requires access to a MySQL database running version 5.0 or higher.',
                    'report'      => 'Your server have Mysql enabled',
                    'status'      => $passed,
                    'passed'      => $passed,
                ); 
            }
            else
            {
                self::$requeriments['database'] = array(
                    'title'       => 'MySQL',
                    'type'        => 'Required',
                    'version'     => 'Not checked yet',
                    'requeriment' => 'Sagui requires access to a MySQL database running version 5.0 or higher.',
                    'report'      => 'Your server don\'t have Mysql installed/enabled. Please install or enable Mysql extension',
                    'status'      => $passed,
                    'passed'      => $passed,
                ); 
            }
            return $passed;
        }
    }

    public static function zlib_acceptable($version = null)
    {
        $passed = extension_loaded('zlib');
        if($passed === true)
        {
            self::$requeriments['zlib'] = array(
                'title'       => 'Zlib',
                'type'        => 'Required',
                'version'     => 'N/A',
                'requeriment' => 'Offers the option to transparently compress your pages on-the-fly, if the requesting browser supports this. Sagui require Zlib in order to unzip and install themes and modules.',
                'report'      => 'Your server have Zlib enabled',
                'status'      => $passed,
                'passed'      => $passed,
            ); 
        }
        else
        {
            self::$requeriments['zlib'] = array(
                'title'       => 'Zlib',
                'type'        => 'Required',
                'version'     => 'N/A',
                'requeriment' => 'Offers the option to transparently compress your pages on-the-fly, if the requesting browser supports this. Sagui require Zlib in order to unzip and install themes and modules.',
                'report'      => 'Your server don\'t have Zlib installed/enabled. Please install or enable Zlib extension',
                'status'      => $passed,
                'passed'      => $passed,
            ); 
        }
        return $passed;
    }

    public static function json_acceptable($version = null)
    {
        $passed = extension_loaded('json');
        if($passed === true)
        {
            self::$requeriments['json'] = array(
                'title'       => 'Json',
                'type'        => 'Required',
                'version'     => 'N/A',
                'requeriment' => 'Sagui require Json in order to encode and decode JavaScript Object Notation (JSON) data.',
                'report'      => 'Your server have Json enabled',
                'status'      => $passed,
                'passed'      => $passed,
            ); 
        }
        else
        {
            self::$requeriments['json'] = array(
                'title'       => 'Json',
                'type'        => 'Required',
                'version'     => 'N/A',
                'requeriment' => 'Sagui require Json in order to encode and decode JavaScript Object Notation (JSON) data.',
                'report'      => 'Your server don\'t have Json installed/enabled. Please install or enable Json extension',
                'status'      => $passed,
                'passed'      => $passed,
            ); 
        }
        return $passed;
    }

    public static function curl_acceptable($version = 1)
    {
        $passed = function_exists('curl_init');
        if($passed)
        {
            $curl_info = curl_version();
            
            $curl_version = preg_replace('/[^0-9\.]/','', $curl_info['version']);

            // If the GD version is at least 1.0 return TRUE, else FALSE
            $passed_version = ($curl_version >= $version) ? TRUE : FALSE;

            if($passed_version)
            {
                self::$requeriments['curl'] = array(
                    'title'       => 'Curl',
                    'type'        => 'Recomended',
                    'version'     => $curl_info['version'],
                    'requeriment' => 'Allows you to connect and communicate to many different types of servers with many different types of protocols.',
                    'report'      => 'Your server have Curl enabled',
                    'status'      => $passed_version,
                    'passed'      => $passed_version,
                );
                return $passed_version;
            }
            else
            {
                self::$requeriments['curl'] = array(
                    'title'       => 'Curl',
                    'type'        => 'Recomended',
                    'version'     => $curl_info['version'],
                    'requeriment' => 'Allows you to connect and communicate to many different types of servers with many different types of protocols.',
                    'report'      => 'Your server have Curl enabled, but need to update to at least version ' . $version,
                    'status'      => $passed_version,
                    'passed'      => $passed_version,
                );
                return $passed_version;
            }
        }
        else
        {
            // Curl is not installed
            self::$requeriments['curl'] = array(
                'title'       => 'Curl',
                'type'        => 'Recomended',
                'version'     => 'Not checked',
                'requeriment' => 'Allows you to connect and communicate to many different types of servers with many different types of protocols.',
                'report'      => 'Your server don\'t have Curl enabled',
                'status'      => $passed,
                'passed'      => $passed,
            );
            return $passed;
        }
    }

    public static function gd_acceptable($version = 1)
    {
        $passed = function_exists('gd_info');
        if($passed)
        {
            $gd_info = gd_info();
            
            $gd_version = preg_replace('/[^0-9\.]/','', $gd_info['GD Version']);

            // If the GD version is at least 1.0 return TRUE, else FALSE
            $passed_version = ($gd_version >= $version) ? TRUE : FALSE;

            if($passed_version)
            {
                self::$requeriments['gd'] = array(
                    'title'       => 'GD',
                    'type'        => 'Recomended',
                    'version'     => $gd_info['GD Version'],
                    'requeriment' => 'Used to create and manipulate image files in a variety of different image formats, including GIF, PNG, JPEG, WBMP, and XPM.',
                    'report'      => 'Your server have GD enabled',
                    'status'      => $passed_version,
                    'passed'      => $passed_version,
                );
                return $passed_version;
            }
            else
            {
                self::$requeriments['gd'] = array(
                    'title'       => 'GD',
                    'type'        => 'Recomended',
                    'version'     => $curl_info['version'],
                    'requeriment' => 'Used to create and manipulate image files in a variety of different image formats, including GIF, PNG, JPEG, WBMP, and XPM.',
                    'report'      => 'Your server have GD enabled, but need to update to at least version ' . $version,
                    'status'      => $passed_version,
                    'passed'      => $passed_version,
                );
                return $passed_version;
            }
        }
        else
        {
            // GD is not installed
            self::$requeriments['gd'] = array(
                'title'       => 'GD',
                'type'        => 'Recomended',
                'version'     => 'Not checked',
                'requeriment' => 'Used to create and manipulate image files in a variety of different image formats, including GIF, PNG, JPEG, WBMP, and XPM.',
                'report'      => 'Your server don\'t have GD enabled',
                'status'      => $passed,
                'passed'      => $passed,
            );
            return $passed;
        }
    }

    public static function xmlrpc_acceptable($version = null)
    {
        $passed = extension_loaded('xmlrpc');
        if($passed === true)
        {
            self::$requeriments['xmlrpc'] = array(
                'title'       => 'Xml-Rpc',
                'type'        => 'Recomended',
                'version'     => 'N/A',
                'requeriment' => 'Support for creating both Xml-Rpc clients and servers.',
                'report'      => 'Your server have Xml-Rpc enabled',
                'status'      => $passed,
                'passed'      => $passed,
            ); 
        }
        else
        {
            self::$requeriments['xmlrpc'] = array(
                'title'       => 'Xml-Rpc',
                'type'        => 'Recomended',
                'version'     => 'N/A',
                'requeriment' => 'Support for creating both Xml-Rpc clients and servers.',
                'report'      => 'Your server don\'t have Xml-Rpc installed/enabled.',
                'status'      => $passed,
                'passed'      => $passed,
            ); 
        }
        return $passed;
    }

    ////////////////////////////////////////////////
    //////////// STEP 2 FUNCTIONS //////////////////
    ////////////////////////////////////////////////

    public static function test_db_connection($credentials)
    {
        $db = null;
        switch ($credentials['driver'])
        {
            case 'sqlite':
                $db = new \Laravel\Database\Connectors\SQLite;
                break;

            case 'mysql':
                $db = new \Laravel\Database\Connectors\MySQL;
                break;

            case 'pgsql':
                $db = new \Laravel\Database\Connectors\Postgres;
                break;

            case 'sqlsrv':
                $db = new \Laravel\Database\Connectors\SQLServer;
                break;
        }
        
        try
        {
            $db->connect($credentials);
            return true;
        }
        catch (Exception $e)
        {
            static::$errors->add('app_installer', $e);
            return false;
        }
    }

    public static function required_database_version($type = 'mysql', $version = '5.0')
    {
        // Server version
        if ($type == 'mysql')
        {
            // Retrieve the database settings from the session
            $server     = Session::get('mysql_hostname').':'.Session::get('mysql_port');
            $username   = Session::get('mysql_username');
            $password   = Session::get('mysql_password');

            if(empty($port) or !is_integer($port))
            {
                $port = 3306;
            }

            if(!empty($server) and !empty($username) and !empty($password))
            {
                // Connect to MySQL
                if ( $db = @mysql_connect($server, $username, $password) )
                {
                    //self::$mysql_server_version = @mysql_get_server_info($db);
                    $mysql_server_version = preg_replace('/[^0-9\.]/','', @mysql_get_server_info($db));

                    // Close the connection
                    @mysql_close($db);

                    // If the MySQL server version is at least version 5 return TRUE, else FALSE
                    return ($mysql_server_version >= $version) ? TRUE : FALSE;
                }
                else
                {
                    @mysql_close($db);
                    return false;
                }
            }
            return false;
        }
        
        if($type == 'posertgere')
        {
            return false;
        }
    }

    ////////////////////////////////////////////////
    //////////// STEP 3 FUNCTIONS //////////////////
    ////////////////////////////////////////////////

    public static function is_really_writable($file)
    {
        // FIX THIS
        // This is only to 
        // by pass permissions on
        // windows machines with 
        // microsoft weird ACL crap
        if(DS === '\\')
        {
            return TRUE;
        }

        // If we're on a Unix server with safe_mode off we call is_writable
        if (DS === '/' && (bool) @ini_get('safe_mode') === FALSE)
        {
            return is_writable($file);
        }

        /* For Windows servers and safe_mode "on" installations we'll actually
         * write a file then read it. Bah...
         */

        if(is_dir($file))
        {
            $file = rtrim($file, DS).DS.md5(mt_rand(1,100).mt_rand(1,100));
            if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
            {
                return FALSE;
            }

            fclose($fp);
            @chmod($file, DIR_WRITE_MODE);
            @unlink($file);
            return TRUE;
        }
        elseif ( ! is_file($file) OR ($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
        {
            return FALSE;
        }

        fclose($fp);
        return TRUE;
    }


    ////////////////////////////////////////////////
    //////////// STEP 4 FUNCTIONS //////////////////
    ////////////////////////////////////////////////

    public static function gen_uuid()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
    }

    public static function validate_uuid($uuid)
    {
        return (bool)preg_match('#^[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}$#', $uuid);
    }

    public static function get_core_modules_list()
    {
        $core_modules_list = glob(path('bundle').'*', GLOB_ONLYDIR);

        $core_modules = array();

        foreach($core_modules_list as $core_module_path)   
        {
            if(is_dir($core_module_path))
            {
                $module_name = basename($core_module_path);
                $core_modules[$module_name] = $core_module_path;
            }  
        }
        unset($core_modules_list);
        return $core_modules;
    }

    public static function install()
    {
        $database_settings = Config::get("database.connections.default");
        
        $core_modules = self::get_core_modules_list();
        
        require path('sys').'cli'.DS.'dependencies'.EXT;

        $mig = \Laravel\CLI\Command::run(array('migrate:install'));

        if(!empty($core_modules))
        {
            //copy every module to the bundles folder
            foreach ($core_modules as $module => $module_path)
            {
                static::migrate($module);
            }

            Bundle::register('modules');
            Bundle::start('modules');
            foreach ($core_modules as $module => $module_path)
            {
                $mod = \Modules\Module::make($module)->is_valid();

                $new_bundle = new \Modules\Model\Module;
                $new_bundle->name        = $mod->name;
                $new_bundle->slug        = $mod->slug;
                $new_bundle->description = isset($mod->description) ? $mod->description : '';
                $new_bundle->version     = $mod->version ;
                $new_bundle->is_frontend = isset($mod->is_frontend) ? $mod->is_frontend : 0;
                $new_bundle->is_backend  = isset($mod->is_backend) ? $mod->is_backend : 0;
                $new_bundle->is_core     = isset($mod->is_core) ? 1 : 0;;
                $new_bundle->required    = $mod->decode('required');
                $new_bundle->recommended = $mod->decode('recommended');
                $new_bundle->options     = $mod->decode('options'); 
                $new_bundle->roles       = $mod->decode('roles');
                $new_bundle->menu        = $mod->decode('menu');
                $new_bundle->enabled     = 1;
                $new_bundle->save();
            }
            Bundle::disable('modules');

            foreach ($core_modules as $module => $module_path)
            {
                static::schema('install', $module);

                // 
                // Publish module assets if any
                //
                static::publish($module);
            }
        }

        return true;
    }

    public static function migrate($module_slug, $action = 'run')
    {
        require path('sys').'cli'.DS.'dependencies'.EXT;
        
        try
        {
            $migrations_path = path('bundle').$module_slug.DS.'migrations'.DS; 
            if(File::exists($migrations_path))
            {
                $migration_files = glob($migrations_path.'*.php');
                if( !empty($migration_files))
                {
                    if($action == 'run')
                    {
                        Bundle::register($module_slug);
                        $custom_tables = \Laravel\CLI\Command::run(array('migrate', $module_slug));
                        Bundle::disable($module_slug);
                        return true;
                    }

                    if($action == 'rollback')
                    {
                        Bundle::register($module_slug);
                        $custom_tables = \Laravel\CLI\Command::run(array('migrate:rollback', $module_slug));
                        Bundle::disable($module_slug);
                        return true;
                    }
                    
                    Log::error('Failed to run migrations for module '.$module_slug.'. Migration command ['.$action .'] is invalid.');
                    static::$errors->add('installer', 'Failed to run migrations for module '.$module_slug.'. Migration command ['.$action .'] is invalid.');
                    return false;
                }
            }
            return true;
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            //static::$errors->add('installer', 'Failed to run migrations.');
            return false;
        }
    }

    public static function schema($action, $module_slug)
    {
        try
        {
            // Does the schema task file exists?
            $schema_path = path('bundle').$module_slug.DS.'tasks'.DS.'schema'.EXT; 
            
            if(\Laravel\File::exists($schema_path))
            {

                include_once $schema_path;
                // Does the class exists?
                $class = Str::title($module_slug.'_Schema_Task');
                if(class_exists($class))
                {
                    $schema_class = new $class;
                    // The action is callable?
                    if(is_callable(array($schema_class, $action)))
                    {
                        $schema_class->$action();
                        return true;
                    }
                    else
                    {
                        Log::error('Failed to run data schema for module '.$module_slug.'. Schema action ['.$action .'] not found.');
                    }
                }
                else
                {
                    Log::error('Failed to run data schema for module '.$module_slug.'. Schema class ['.$class .'] not found.');
                }
            }

            // we dont have task schema to run
            return true;
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            return false;
        }
    }

    public static function publish($module_slug)
    {
        require path('sys').'cli'.DS.'dependencies'.EXT;
        
        try
        {
            $module_assets_path = path('bundle').$module_slug.DS.'public'.DS; 
            if(\File::exists($module_assets_path))
            {
                \Bundle::register($module_slug);
                $publish_cmd = \Laravel\CLI\Command::run(array('bundle:publish', $module_slug));
                \Bundle::disable($module_slug);
                return true;
            }
            return true;
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            Log::error('Failed to publish assets for module ['.$module_slug.'].');
            return false;
        }
    }

    /**
     * @param   string $database The name of the database
     *
     * Writes the database file based on the provided database settings
     */
    public static function write_db_file($database_info)
    {
        extract($database_info);

        // Open the template file
        $template   = file_get_contents(path('bundle').'install'.DS.'file_templates'.DS.'database.tpl');

        $replace = array(
            '__DRIVER__'    => $driver,
            '__HOSTNAME__'  => $host,
            '__USERNAME__'  => $username,
            '__PASSWORD__'  => $password,
            '__DATABASE__'  => $database,
            '__PORT__'      => $port,
            '__PREFIX__'    => $prefix,
        );

        // Replace the __ variables with the data specified by the user
        $new_file   = str_replace(array_keys($replace), $replace, $template);
        
        // Open the database.php file, show an error message in case this returns false
        $handle     = @fopen(path('app').'config'.DS.'database'.EXT,'w+');

        // Validate the handle results
        if ($handle !== false)
        {
            return @fwrite($handle, $new_file);
        }

        return false;
    }

    public static function write_file($source, $destination, $options = array())
    {
        $new_file = null;
        
        $template = @file_get_contents($source);

        if( ! empty($options) )
        {
            $new_file   = str_replace(array_keys($options), $options, $template);
        }
        else
        {
            $new_file = $template;
        }

        $handle = @fopen($destination, 'w+');

        // Validate the handle results
        if ( $handle !== false )
        {
            return @fwrite($handle, $new_file);
        }

        return false;
    }

    ////////////////////////////////////////////////
    //////////// STEP 5 FUNCTIONS //////////////////
    ////////////////////////////////////////////////
    
    public static function server_aceptable($version = 1)
    {
        
        $apache_version = apache_get_version();
            
            self::$apache_version = preg_replace('/[^0-9\.]/','', $apache_version);

            // If the GD version is at least 1.0 return TRUE, else FALSE
            return (self::$apache_version >= $version) ? TRUE : FALSE;
    }
}