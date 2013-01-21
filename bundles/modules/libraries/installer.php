<?php namespace Modules;

use Laravel\Session;
use Laravel\Messages;
use Laravel\Lang;
use Laravel\Log;
use Laravel\Config;
use Laravel\File;

class Installer
{

    /**
     * Module error messages
     * 
     * @var Laravel\Messages
     */
    public static $errors;

    public function __construct()
    {

        // If a session driver has been specified, we will bind an instance of the
        // module error message container. If an error instance
        // exists in the session, we will use that instance.
        if ( ! isset(static::$errors))
        {
            if (Session::started() and Session::has('errors'))
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

    public static function all($options = array())
    {
        $modules_folders = glob(path('bundle').'*', GLOB_ONLYDIR);
        
        $modules = array();

        if(empty($options)) $options[] = 'non_core';

        $options = array_flip($options);

        foreach ($modules_folders as $mod) 
        {
            $module_name = basename($mod);
            $module      = Module::make($module_name);
            
            if($module->is_valid() !== false)
            {
                if(isset($options['non_core']) and $module->is_core) continue;
                if(isset($options['core']) and !$module->is_core) continue;
                if(isset($options['installed']) and !$module->installed) continue;
                if(isset($options['enabled']) and !$module->enabled) continue;
                
                $modules[$module_name] = $module;
            }
        }

        return $modules;
    }

    public static function upload($zip_file)
    {
        $zip = new \ZipArchive;

        $res = $zip->open($zip_file['tmp_name']);
        if($res === TRUE) 
        {
            $module_name_parts = explode('.', $zip_file['name']);

            if(is_array($module_name_parts) and count($module_name_parts) > 0)
            {
                $module_slug = $module_name_parts['0'];

                // Check if bundle folder exists
                if(is_dir(path('bundle').$module_slug))
                {

                    static::$errors->add('installer', 
                        Lang::line('modules::lang.The folder &#34;:module_slug&#34; already exists', 
                            array(
                                'module_slug' => $module_slug, 
                            )
                        )->get(ADM_LANG));

                    $zip->close();
                    return false;
                }

                // Check if zip file contain 
                // an valid module info file
                if(is_array($module_name_parts) and count($module_name_parts) > 0)
                {
                    $tmp = tmpfile();
                    $module_info = stream_get_meta_data($tmp);

                    file_put_contents($module_info['uri'], $zip->getFromName(strtolower($module_name_parts['0']) .DS. 'info.json'));

                    if( !Module::check_info_file($module_info['uri']))
                    {
                        static::$errors->add('installer', 
                        Lang::line('modules::lang.The zip file does not contain a valid module info file')->get(ADM_LANG));

                        $zip->close();
                        return false;
                    }
                }

                $zip->extractTo(path('bundle'));
                $zip->close();
                return true;  
            }
            else
            {
                static::$errors->add('installer', 'Invalid module slug');
                $zip->close();
                return false;
            }
        }
        else
        {
            static::$errors->add('installer', 'Failed to uncompress the module zip file.');
            return false;
        }
    }
    
    public static function install($module_slug)
    {

        if( empty($module_slug))
        {
            static::$errors->add('installer', 'Module slug is invalid ['.$module_slug.']');
            return false;
        }

        $module = Module::make($module_slug);
        
        if( !$module->is_valid() )
        {
            static::$errors = $module->errors;
            return false;
        }

        if($module->pass_requirements())
        {
            $new_bundle = new Model\Module;
            
            $new_bundle->name        = $module->name;
            $new_bundle->slug        = $module->slug;
            $new_bundle->description = isset($module->description) ? $module->description : '';
            $new_bundle->version     = $module->version ;
            $new_bundle->is_frontend = isset($module->is_frontend) ? $module->is_frontend : 0;
            $new_bundle->is_backend  = isset($module->is_backend) ? $module->is_backend : 0;
            $new_bundle->is_core     = isset($module->is_core) ? 1 : 0;
            $new_bundle->required    = $module->decode('required');
            $new_bundle->recommended = $module->decode('recommended');
            $new_bundle->options     = $module->decode('options'); 
            $new_bundle->roles       = $module->decode('roles');
            $new_bundle->menu        = $module->decode('menu');
            $new_bundle->enabled     = 0;
            

            if(static::migrate($module->slug))
            {
                //clean any message from migration
                ob_get_clean();

                // Remove the module from the config array
                // updates information if we are in the same
                // request eg: (ajax)
                Config::set('installed_modules.'.$module->slug, $module->slug);

                // Save DB
                $new_bundle->save();
                
                if( !static::schema('install', $module->slug) )
                {
                    //clean any message from schema
                    ob_get_clean();

                    // If it fail to install data remove
                    // Config
                    $installed_modules = Config::get('installed_modules');
                    unset($installed_modules[$module_slug]);
                    Config::set('installed_modules', $installed_modules);

                    // DB
                    $new_bundle->delete();

                    return false;
                }

                // 
                // Publish module assets if any
                //
                static::publish($module->slug);

                
                return true;
            }
            else
            {
                Log::error('Failed to run migrations for module ['.$module_slug.'].');
                static::$errors->add('bundle', 'Failed to run migrations for module ['.$module_slug.'].');
                return false;
            }
        }
        else
        {
            static::$errors = $module->errors;
            Log::error('Failed to install module ['.$module->name.'] requeriments pending. Please fix those before proceed. Ok? If you are not ok with it, there is no much I can do about id muchacho. :(');
            static::$errors->add('installer', 'Failed to install module ['.$module->name.'] requeriments pending. Please fix those before proceed. Ok? If you are not ok with it, there is no much I can do about it muchacho. :(');
            return false;
        }
    }

    public static function uninstall($module_slug)
    {
        if( empty($module_slug))
        {
            static::$errors->add('installer', 'Failed to uninstall module ['.$module_slug.']');
            return false;
        }

        $module = Model\Module::where('slug', '=', $module_slug)->first();
        if(isset($module))
        {
            if( static::schema('uninstall', $module->slug) )
            {
                //clean any message from schema
                ob_get_clean();

                if(static::migrate($module->slug, 'rollback'))
                {
                    //clean any message from migration
                    ob_get_clean();

                    // Remove the module from the config array
                    // updates information if we are in the same
                    // request eg: (ajax)
                    $installed_modules = Config::get('installed_modules');
                    unset($installed_modules[$module_slug]);
                    Config::set('installed_modules', $installed_modules);

                    // Remove from DB
                    $module->delete();

                    return true;
                }
                else
                {
                    static::$errors->add('installer', 'Failed to rollback migrations for module ' . $module_slug . '.');
                    return false;
                }
            }
            else
            {
                static::$errors->add('installer', 'Failed to uninstall data schema for module ' . $module_slug . '.');
                return false;
            }
        }
        else
        {
            static::$errors->add('installer', 'Module ' . $module_slug . ' was not found.');
            return false;
        }
    }

    public static function enable($module_slug)
    {
        $module = Module::make($module_slug);
        
        if(!$module->is_valid())
        {
            static::$errors = $module->errors;
            return false;
        }

        $db_module = Model\Module::where('slug', '=', $module_slug)->first();
        if(isset($db_module) and !empty($db_module))
        {
            $db_module->enabled = 1;
            $db_module->save();
            
            \Bundle::register($module_slug, json_decode($db_module->options, true));
            \Bundle::start($module_slug);
            
            return true;
        }
        else
        {
            static::$errors->add('installer', 'Module ['.$module_slug.'] must be installed.');
            return false;
        }
    }

    public static function disable($module_slug)
    {
        $module = Module::make($module_slug);
        
        if(!$module->is_valid())
        {
            static::$errors = $module->errors;
            return false;
        }

        $dependencies = $module->has_dependencies();
        
        if(empty($dependencies))
        {
            $db_module = Model\Module::where('slug', '=', $module_slug)->first();
            if(isset($db_module) and !empty($db_module))
            {
                $db_module->enabled = 0;
                $db_module->save();

                \Bundle::disable($module_slug);
                
                return true;
            }
            else
            {
                static::$errors->add('installer', 'Module ['.$module_slug.'] must be installed.');
                return false;
            }
        }
        else
        {
            foreach ($dependencies as $dependency_slug => $module) 
            {
               static::$errors->add('installer', 'Module ['.$module_slug.'] cannot be disabled. Please disable ' . $dependency_slug . ' module first.' );
            }
            return false;
        }
    }

    public static function upgrade()
    {
    }

    public static function remove($module_slug)
    {
        if(isset($module_slug) and !empty($module_slug))
        {
            // Remove bundle files
            if(\File::rmdir(path('bundle').$module_slug) === null)
            {
                static::$errors->add('installer', 'Module ['.$module_slug.'] was successfully removed.');
                //
                // Remove published assets
                // 
                if(File::rmdir(path('public').'bundles'.DS.$module_slug) === null)
                {
                    static::$errors->add('installer', 'Module assets for module ['.$module_slug.'] were successfully removed.');
                    return true;
                }
                else
                {
                    static::$errors->add('installer', 'Failed to remove assets for module ['.$module_slug.'].');
                    return false;       
                }
            }
            else
            {
                static::$errors->add('installer', 'Failed to remove module ['.$module_slug.'].');
                return false;
            }
        }
        else
        {
            static::$errors->add('installer', 'Failed to remove module, invalid module slug');
            return false;
        }
    }

    public static function migrate($module_slug, $action = 'run')
    {
        require path('sys').'cli/dependencies'.EXT;
        
        try
        {
            $migrations_path = path('bundle').$module_slug.'/migrations/'; 
            if(\File::exists($migrations_path))
            {
                $migration_files = glob($migrations_path.'*.php');
                if( !empty($migration_files))
                {
                    if($action == 'run')
                    {
                        \Bundle::register($module_slug);
                        $custom_tables = \Laravel\CLI\Command::run(array('migrate', $module_slug));
                        \Bundle::disable($module_slug);
                        return true;
                    }

                    if($action == 'rollback')
                    {
                        \Bundle::register($module_slug);
                        $custom_tables = \Laravel\CLI\Command::run(array('migrate:rollback', $module_slug));
                        \Bundle::disable($module_slug);
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
            static::$errors->add('installer', 'Failed to run migrations.');
            return false;
        }
    }

    public static function schema($action, $module_slug)
    {
        try
        {
            // Does the schema task file exists?
            $schema_path = path('bundle').$module_slug.DS.'tasks'.DS.'schema'.EXT; 
            
            if(\File::exists($schema_path))
            {

                include_once $schema_path;
                // Does the class exists?
                $class = \Str::title($module_slug.'_Schema_Task');
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
                        static::$errors->add('installer', 'Failed to run data schema for module '.$module_slug.'. Schema action ['.$action .'] not found.');
                    }
                }
                else
                {
                    static::$errors->add('installer', 'Failed to run data schema for module '.$module_slug.'. Schema class ['.$class .'] not found.');
                }
            }

            // we dont have task schema to run
            return true;
        }
        catch (\Exception $e)
        {
            static::$errors->add('installer', $e->getMessage());
            return false;
        }
    }

    public static function publish($module_slug)
    {
        require path('sys').'cli/dependencies'.EXT;
        
        try
        {
            $module_assets_path = path('bundle').$module_slug.'/public/'; 
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
            static::$errors->add('installer', 'Failed to publish assets for module ['.$module_slug.'].');
            return false;
        }
    }

    public static function check_version($installed_version, $required_version, $operator = '>=')
    {
        return version_compare($installed_version, $required_version, $operator);
    }
}