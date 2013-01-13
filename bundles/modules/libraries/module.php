<?php namespace Modules;

use Laravel\Messages;
use Laravel\File;
use Laravel\Session;
//use Laravel\Bundle;
use Laravel\Lang;
use Laravel\View;

class Module {

    /**
     * The module name
     * 
     * @var string
     */
    public $name = '';

    /**
     * The module description
     * 
     * @var string
     */
    public $description = '';

    /**
     * The module handle (slug)
     * 
     * @var [type]
     */
    public $slug;

    /**
     * The module options
     * 
     * @var array
     */
    public $options = array();

    /**
     * The module roles if any 
     * for permissions module
     * 
     * @var array
     */
    public $roles = array();

    /**
     * All required modules
     * @var array
     */
    public $required = array();

    /**
     * All the module and dependencies
     * 
     * @var array
     */
    public $requirements = null;

    /**
     * Module recomended modules for installation
     * 
     * @var array
     */
    public $recommended = array();

    /**
     * Menu items for this module
     * 
     * @var array
     */
    public $menu = array();

    /**
     * The module layer (backend/frontend)
     * 
     * @var [null/string]
     */
    public $layer = null;

    /**
     * Module frontend handle (slug)
     * If the module provides frontend
     * features 
     * 
     * @var [null/string]
     */
    public $frontend_slug = null;

    /**
     * The module version
     * 
     * @var string
     */
    public $version = '0.0.0';

    /**
     * Module core vertion required
     * @var string
     */
    public $core_version;

    /**
     * If the module is a core module
     * 
     * @var boolean
     */
    public $is_core = false;

    /**
     * The module on disk
     * 
     * @var string
     */
    public $path;

    /**
     * Module error messages
     * 
     * @var Laravel\Messages
     */
    public $errors;

    /**
     * If this module pass requirements
     *     
     * @var bool
     */
    public $pass_requirements;

    /**
     * The module install status
     * 
     * @var bool
     */
    public $installed;

    /**
     * The module enable status
     * 
     * @var bool
     */
    public $enabled;

    /**
     * If the module exists 
     * in the modules folder
     * 
     * @var boolean
     */
    public $exist = false;

    /**
     * The type of this module
     * information file (json/yaml/xml)
     * 
     * @var string
     */
    public $file_info_type;

    /**
     * The installer instance
     * 
     * @var Modules\Installer
     */
    public $installer;

    /**
     * Create a new module instance.
     *
     * <code>
     *      // Create a new module instance
     *      $module = new Modules\Module('registration');
     *
     *      // Create a new module instance with a path
     *      $module = new Modules\Module('registration', 'registration/extended');
     *
     *      // Create a new module instance with a full path
     *      $module = new Modules\Module('registration', 'path: /var/www/project/registration');
     * </code>
     *
     * @param  string  $module
     * @param  string  $path
     * @return void
     */
    public function __construct($module_slug, $path = null)
    {
        $this->slug = $module_slug;
        
        $ins = \Laravel\Config::get('installed_modules.'.$module_slug);
        $this->installed = isset($ins) and !empty($ins) ? true : false;
        
        $this->enabled = \Laravel\Bundle::exists($this->slug);

        $this->installer = Installer::make($module_slug);

        // if path starts with "path: "
        // its a custom path
        if( !isset($path) )
        {
            $this->path = path('bundle').$this->slug.DS;
        }
        else
        {
            if (starts_with($path, 'path: '))
            {
                $this->path = substr($path, 6);
            }
            else
            {
                // sets the path to modules
                // folder + path
                // module with different folder name?
                $this->path = path('bundle') . $path;
            }
        }

        // If a session driver has been specified, we will bind an instance of the
        // module error message container. If an error instance
        // exists in the session, we will use that instance.
        if ( ! isset($this->errors))
        {
            if (Session::started() and Session::has('errors'))
            {
                $this->errors = Session::get('errors');
            }
            else
            {
               $this->errors = new Messages;
            }
        }
    }

    /**
     * Create a new module instance.
     *
     * <code>
     *      // Create a new module instance
     *      $module = Modules\Module::make('registration');
     *
     *      // Create a new module instance with a path
     *      $module = Modules\Module::make('registration', 'registration/extended');
     *
     *      // Create a new module instance with a full path
     *      $module = Modules\Module::make('registration', 'path: /var/www/project/registration');
     * </code>
     *
     * @param  string  $module
     * @param  string  $path
     * @return Module
     */
    public static function make($module, $path = null)
    {   
        return new static($module, $path);
    }

    public function validate_requirements()
    {
        if(isset($this->requirements))
        {
            return $this->requirements;
        }

        if(!$this->is_valid())
        {
            return false;
        }
        
        foreach ($this->required as $requirement) 
        {
            $req = static::make($requirement['name']);
            if($req->is_valid())
            {
                if($this->installer->check_version($req->version, $requirement['version'], '>='))
                {
                    if($req->installed)
                    {
                        if($req->enabled)
                        {
                            $this->requirements[$req->slug]['success'] = true;
                            $this->requirements[$req->slug]['message'] = 'ok';
                            $this->requirements[$req->slug]['module']  = $req;
                            
                            if(!empty($req->required))
                            {
                                if(!isset($this->requirements[$req->slug]))
                                {
                                    $req->validate_requiriments();
                                }
                            }
                        }
                        else
                        {
                            $this->requirements[$req->slug]['success'] = false;
                            $this->requirements[$req->slug]['message'] = 'Module ['.$this->name.'] requires module ['.$req->name.']. ['.$req->name.'] module is not enabled.';
                            $this->requirements[$req->slug]['module']  = $req;
                        }
                    }
                    else
                    {
                        $this->requirements[$req->slug]['success'] = false;
                        $this->requirements[$req->slug]['message'] = 'Module ['.$this->name.'] requires module ['.$req->name.']. ['.$req->name.'] module is not installed';
                        $this->requirements[$req->slug]['module']  = $req;
                    }
                }
                else
                {
                    $this->requirements[$req->slug]['success'] = false;
                    $this->requirements[$req->slug]['message'] = 'Module ['.$this->name.'] requires ['.$req->name.'] module version ['.$requirement['version'].']. Installed version of ['.$req->name.'] module is ['.$req->version.']';
                    $this->requirements[$req->slug]['module']  = $req;
                }
            }
            else
            {
                $this->requirements[$req->slug]['success'] = false;
                $this->requirements[$req->slug]['message'] = 'Module ['.$this->name.'] requires module ['.$requirement['name'].']. ['.$requirement['name'].'] module was not found.';
                $this->requirements[$req->slug]['module']  = $req;
            }
        }

        return $this->requirements;
    }

    public function pass_requirements()
    {
        $result = true;
        
        if(!isset($this->requirements))
        {
            $this->validate_requirements();
        }
        
        if(!empty($this->requirements))
        {
            foreach ($this->requirements as $m_slug => $value) 
            {
                if($value['success'] === false)
                {
                    $result = false;
                    $this->errors->add($this->slug, $value['message']);
                }
            }
        }
        return $result;
    }

    /**
    * Checks if the module has dependencies
    * if trying to disable it
    * 
    * @return array
    */
    public function has_dependencies()
    {
        $m_slug = $this->slug;
        $all_modules_path = glob(path('bundle').'*', GLOB_ONLYDIR);
        $dependencies = array();
        
        foreach ($all_modules_path as $module_path) 
        {
            $module_name = basename($module_path);
            $module      = static::make($module_name);

            if($module->is_valid())
            {
                foreach ($module->required as $req) 
                {
                    $exist = array_first($req, function($k, $v) use ($m_slug) { return $v == $m_slug; });
                    if(isset($exist) and $m_slug !== $module->slug)
                    {
                        if($module->enabled)
                        {
                            $dependencies[$module_name] = $module;
                        }
                    }
                }
            }
        }
        return $dependencies;
    }

    public function get_all()
    {
        $modules_folders = glob(path('bundle').'*', GLOB_ONLYDIR);
        
        $modules = array();

        foreach ($modules_folders as $mod) 
        {
            $module_name = basename($mod);
            $module      = Module::make($module_name);
            
            if($module->is_valid() !== false)
            {
                $modules[$module_name] = $module;
            }
        }

        return $modules;
    }

    public static function check_info_file($path, $ext = 'json')
    {
        $json_module_info = json_decode(File::get($path), true);
            
        if( !isset($json_module_info) or empty($json_module_info) )
        {
            $this->errors->add('module', 'The information file for module ['.$this->slug.'] is malformed');
            return false;
        }
        return true;
    }

    /**
     * Checks if the module is valid
     * and can be loaded
     * 
     * @return Module
     */
    public function is_valid()
    {
        $info_file_path = '';

        if(!file_exists($this->path))
        {
            $this->errors->add($this->slug, 'Module ['.$this->slug.'] was not found');
            return false;
        }

        $info_files = glob($this->path.'info.*');
        if(empty($info_files))
        {
            $this->errors->add($this->slug, 'The information file for ['.$this->slug.'] module is missing');
            return false;
        }

        $info_file_path = $info_files['0'];

        $this->file_info_type = file_extension($info_file_path);

        // create method to decode
        // other types of info files
        $json_module_info = json_decode(File::get($info_file_path), true);
            
        if( !isset($json_module_info) or empty($json_module_info) )
        {
            $this->errors->add('module', 'The information file for module ['.$this->slug.'] is malformed');
            return false;
        }

        foreach($json_module_info as $property => $value) 
        {
            if(property_exists($this, $property))
            {
                $this->$property = $value;
            }
        }
        $this->exist = true;
        return $this;
    }

    /**
     * Returns a string containing 
     * all required module names
     * 
     * @return string comma separated module names
     */
    public function requirements_to_string()
    {
        $result = array();
        if(!empty($this->required))
        {
            foreach ($this->required as $key => $value) 
            {
                if( !empty($value) )
                {
                    foreach ($value as $k => $v) 
                    {
                        $version = $value['version'];
                        if($k == 'name')
                        {
                            $m = static::make($v);
                            if($m->is_valid())
                            {
                                if($m->installed)
                                {
                                    if($m->enabled)
                                    {
                                        if($this->installer->check_version($m->version, $version, '>='))
                                        {
                                            $result[] = View::make('modules::backend.partials.module_requirements_popup')
                                                                ->with('module_rq', $this)
                                                                ->with('module', $m)
                                                                ->with('class', 'green')
                                                                ->with('message', $this->name .' module requires '.$m->name.' module, and it passes all requirements.')
                                                                ->render();
                                        }
                                        else
                                        {
                                            $result[] = View::make('modules::backend.partials.module_requirements_popup')
                                                            ->with('module_rq', $this)
                                                            ->with('module', $m)
                                                            ->with('class', 'orange')
                                                            ->with('message', $this->name .' module requires '.$m->name.' module version '.$version.', and currently you have installed version '.$m->version.'.')
                                                            ->render();
                                        }
                                    }
                                    else
                                    {
                                        $result[] = View::make('modules::backend.partials.module_requirements_popup')
                                                            ->with('module_rq', $this)
                                                            ->with('module', $m)
                                                            ->with('class', 'orange')
                                                            ->with('message', $this->name .' module requires '.$m->name.' module, and it must be enabled.')
                                                            ->render();
                                    }
                                }
                                else
                                {
                                    $result[] = View::make('modules::backend.partials.module_requirements_popup')
                                                            ->with('module_rq', $this)
                                                            ->with('module', $m)
                                                            ->with('class', 'orange')
                                                            ->with('message', $this->name .' module requires '.$m->name.' module, and it must be installed.')
                                                            ->render();
                                }
                            }
                            else
                            {
                                $result[] = View::make('modules::backend.partials.module_requirements_popup')
                                                            ->with('module_rq', $this)
                                                            ->with('module_slug', $v)
                                                            ->with('class', 'red')
                                                            ->with('message', $this->name .' module requires '.$v.' module, and it was not found in the modules folder.')
                                                            ->render();
                            }
                        }
                    }
                }
            }

            return '<small>Requirements: ('.implode(', ',  $result).')</small>';
        }
        else
        {
            return '';
        }
    }

    public function decode($property)
    {
        if($this->file_info_type == 'json')
        {
            $options = array();
            if( isset($this->{$property}) and 
                ! empty($this->{$property}) and 
                is_array($this->{$property}))
            {
                $options = $this->{$property};
            }
            else
            {
                if($property == 'options')
                {
                    $options['handles'] = $this->slug;
                }
            }

            return json_encode($options);
        }
    }

    public function encode($property, $as = 'array')
    {
    }
}