<?php namespace Themes;

use Laravel\Asset;
use Laravel\View;
use Laravel\Log;
use Settings;

class Theme {
    /**
     * Theme name;
     *
     * @var string
     */
    public $_theme_name;
    
    /**
     * Theme metadata set
     * 
     * @var array
     */
    public $_theme_metadata = array();
    
    /**
     * Theme dataset
     * 
     * @var array
     */
    public $_theme_data = array();

    /**
     * Location : theme base path
     *
     * @var string
     */
    public $_theme_base_path;

    /**
     * Location : relative path
     *
     * @var string
     */
    public $_theme_path = 'themes';

    /**
     * Public location for modules assets : relative path
     *
     * @var string
     */
    public $_modules_asset_path = 'bundles';

    /**
     * Location : absolute path
     *
     * @var string
     */
    public $_theme_absolute_path;
    
    /**
     * Set layout
     * 
     * @var string
     */
    public $_layout = 'application';
    
    /**
     * Default layout
     * 
     * @var string
     */
     // public $_default_layout = "application";

    /**
     * Theme partials
     *
     * @var array
     */
    public $_theme_partials;

    /**
     * Config preferences of the theme
     * @var array
     */
    private $_config;

    /**
     * Page Title
     */
    private $_title;

    private $_layer;

    /**
     * create a new Theme object instance
     *
     * @param string    $theme
     * @param string    $config 
     * @return void
     */
    public function __construct($theme_name, $config = null)
    {
        $this->_theme_name = $theme_name;

        $this->_theme_base_path = path('base');

        if($config !== NULL)
        {
            $this->init($config);
        }
    }
    
    /**
     * Initialize the preferences
     *
     * @param array     $config
     * @return void
     */
    public function init($config = array())
    {
        foreach ($config as $key => $val)
        {
            if($key == 'theme' AND $val != '')
            {
                $this->set_theme($val);
                continue;
            }
            
            if($key == 'theme_path')
            {

                if(starts_with($val, 'path: '))
                {
                    $path = substr($val, 6);
                    
                    $this->_theme_path          = $path; // .DS.$this->_theme_name;
                    $this->_theme_absolute_path = $this->_theme_base_path.$this->_theme_path;
                    continue;
                }
                else
                {
                    $this->_theme_path          = $this->_theme_name;
                    $this->_theme_absolute_path = path('public').'themes'.DS.$this->_theme_path;
                    continue;
                }
            }

            $this->{'_'.$key} = $val;
        }
    }
    
    /**
     * Sets the theme we want to use
     *
     * @param string    $theme
     * @param string    $path
     * @return void
     */
    public function set_theme($theme, $layer = 'frontend', $path = '', $save = false)
    {
        $this->_theme_name = $theme;

        $this->_layer = ($layer == 'frontend') ? 'frontend' : 'backend';

        if($save)
        {
            // Update settings theme
            Settings\Config::set('settings::core.'. $this->_layer .'_theme', $theme);
        }

        if(starts_with($path, 'path: '))
        {
            $path = substr($path, 6);
            
            $this->_theme_path          = $path; // .DS.$this->_theme_name;
            $this->_theme_absolute_path = $this->_theme_base_path.$this->_theme_path;
        }
        else
        {
            $this->_theme_path          = $this->_theme_name;
            $this->_theme_absolute_path = path('public').'themes'.DS.$this->_theme_path;
        }

        return $this;
    }
    
    /**
     * set Title of the page
     *
     * @param string    $title
     * @return void
     */
    public function title($title) 
    {
        $this->_title = $title;
        return $this;
    }

    
    /**
     * Set the theme layout to use
     * 
     * @param string    $layout
     * @return void
     */
    public function set_layout($layout, $layer = 'frontend', $save = false)
    {
        $this->_layout = $layout;

        $this->_layer = 'frontend';

        if($layer != 'frontend')
        {
            $this->_layer = 'backend';
        }
        

        if($save)
        {
            // Update settings theme
            \Settings\Config::set('settings::core.'. $this->_layer .'_layout', $layout);
        }

        return $this;
    }
    

    /**
     * Register composer event to a view
     * 
     * for registering a composer to a theme partial
     *
     *  <code>
     *
     *      $theme->composer('menu', function($view){
     *          
     *          $view->with('theme_menu', "This is loaded from composer of the theme.");  
     *      });
     *      
     *  </code>
     *
     * @param  string|array  $view
     * @param  Closure       $composer
     * @return void
     */
    public function composer($views, $composer) 
    {

        $views = (array)$views;

        $theme_name = $this->_theme_name;
        $theme_path_relative = $this->_theme_path.DS.$theme_name ;
        $theme_path_absolute = $this->_theme_base_path . $theme_path_relative;

        $theme_partials_path_absolute =  $theme_path_absolute . DS. 'partials';

        foreach ($views as $view) 
        {
            if(file_exists($tpath = $theme_partials_path_absolute.DS.$view.EXT))
            {
                $view = "path: " . $tpath; //View::make("path: " . $tpath);
            } 
            elseif(file_exists($tpath = $theme_partials_path_absolute.DS.$view.BLADE_EXT)) 
            {
                $view = "path: " . $tpath; //View::make("path: " . $tpath);
            }
          
            View::composer($view, $composer);
        }

        return $this;
    }

    /**
     * Sets theme partials of the theme
     *
     * @param string    $partial
     * @param string    $path
     */
    public function set_partial($partial, $data = array())
    {
        $this->_theme_partials['partials'][$partial] = $data;
        return $this;
    }
    
    
    /**
     * Add a line to the end of the $theme['metadata'] string.
     */
    public function append_metadata($line)
    {
        $this->_theme_metadata[] = $line;
        return $this;
    }
    
    /**
     * Add a line to the start of the $theme['metadata'] string.
     */
    public function prepend_metadata($string)
    {
        array_unshift($this->_theme_metadata, $string);
    }
    
    /**
     * Add asset to this view and automaticaly checks
     * by its extention
     * 
     * <code>
     *
     *      // Adds an single asset
     *      $theme->add_asset('jquery.js');
     *
     *      // Add asset with dependencies
     *      $theme->add_asset('jquery.js', null, array('jquery-ui'));
     *
     *      // Add asset with custom path
     *      $theme->add_asset('jquery.js', 'admin/extra');
     *
     *      // Add asset and specify its container
     *      $theme->add_asset('jquery.js', null, array(), 'footer');
     *      
     * </code>
     * 
     * @param string $filename     Name of the asset
     * @param string $path         Path to the asset
     * @param array  $dependencies The dependencies if any
     * @param string $container    The container name where the asset will be available
     */
    public function add_asset($filename, $path = null, $dependencies = array(), $container = '')
    {
        $asset_type = '';
        if(ends_with($filename, '.js')) { $asset_type = 'js';  }
        if(ends_with($filename,'.css')) { $asset_type = 'css'; }
        
        $theme_path = 'themes/'.$this->_theme_path.'/assets';
        
        $asset_path = null;
        
        if($path !== null)
        {
            $path = trim( $path, '/' );

            if(starts_with($path, 'path: '))
            {
                $asset_path = substr($path, 6);
            }
            elseif((starts_with($path, 'mod: ')))
            {
                $asset_path = $this->_modules_asset_path.'/'.substr($path, 5).'/';
            }
            else
            {
                $asset_path = $theme_path.'/'.$asset_type.'/'.$path.'/';
            }
        }
        else
        {
            $asset_path = $theme_path.'/'.$asset_type.'/';
        }

        if($asset_path !== null)
        {
            if( ! empty($container))
            {
                if( ! empty($dependencies))
                {
                    Asset::container($container)->add($filename, $asset_path.$filename, $dependencies);
                }
                else
                {
                    Asset::container($container)->add($filename, $asset_path.$filename);
                }
            }
            else
            {
                if( ! empty($dependencies))
                {
                    Asset::add($filename, $asset_path.$filename, $dependencies);
                }
                else
                {
                    Asset::add($filename, $asset_path.$filename);
                }
            } 
        }

        return $this;
    }

    /**
     * Render the theme view
     *
     * @param string    $view   Path to load the view
     * @param array     $data   Data to be availabe in the view
     * @return Laravel\View Object
     */
    public function render($page, $data = null)
    {
        // LOAD LAYOUT
        $this->_layout        = ( ! empty($this->_layout)) ? $this->_layout : 'application';
        $layout_path_absolute = $this->_theme_absolute_path .DS. 'layouts' . DS ;
        $layout_file_path     = $layout_path_absolute.$this->_layer.DS.$this->_layout;
        $has_layout           = $this->_view_exists($layout_file_path);
        
        $view = null;
        
        //themes/layouts/[layer]/layout.php
        if(is_string($has_layout) and ! empty($has_layout))
        {
            $view = View::make($has_layout, $data);
        }
        
        //themes/layouts/layout.php
        $layout_file_path     = $layout_path_absolute.$this->_layout;
        $has_layout           = $this->_view_exists($layout_file_path);
        if(is_string($has_layout) and ! empty($has_layout))
        {
            $view = View::make($has_layout, $data);
        }
        
        if(is_null($view))
        {
            // No layout file was found
            // fallback to application basic layout
            Log::error('Template file [' . $this->_layout . '] for theme ['.$this->_theme_name.'] was not found. Rendering basic layout.');
            $view = View::make('layouts.'.$this->_layout);
        }
        // LOAD LAYOUT
        
        //stack process
        $this->_init_theme_func($this->_theme_absolute_path);
        
        // SCRIPTS STYLES AND METADATA
        $scripts = Asset::scripts();
        $styles = Asset::styles();
        
        $this->prepend_metadata($styles);
        $this->prepend_metadata($scripts);
        
        $data['page_metadata'] = $this->_metadata();
        $data['page_title'] = $this->_title;
        // SCRIPTS STYLES AND METADATA

        // THERE IS PARTIALS PRELOADED FROM CONTROLLER
        $this->_load_partials($view);
        // THERE IS PARTIALS PRELOADED FROM CONTROLLER
        
        // SHARE DATA
        $data = array_merge($this->_theme_data, $data);
        $this->_share_data($data, $view);
        // SHARE DATA

        // LOAD MAIN CONTENT
        $bundle_parts = \Bundle::parse($page);

        $bundle_name = $bundle_parts['0'] == 'application' ? '' : $bundle_parts['0'].DS;
        
        $partial_path = str_replace('.', DS, $bundle_parts['1']);
        
        $theme_view_folder = $this->_theme_absolute_path.DS.'views'.DS;

        // Check for a custom path
        $has_view = $this->_view_exists(str_replace('.', DS, $page));
        if($has_view)
        {
            $view->nest('theme_content', $has_view, $data);
        }

        // Check on the themes views folder
        $has_view = $this->_view_exists($theme_view_folder.$bundle_name.$page);
        if($has_view)
        {
            $view->nest('theme_content', $has_view, $data);
        }

        // Check on custom folder (fallback for all themes)
        $has_view = $this->_view_exists(path('public').'custom'.DS.$bundle_name.$page);
        if($has_view)
        {
            $view->nest('theme_content', $has_view, $data);
        }

        // bundles folder
        $has_view = $this->_view_exists($page);
        if($has_view)
        {
            $view->nest('theme_content', $has_view, $data);
        }

        // try to load from application views folder
        $has_view = $this->_view_exists('views'.DS.$page);
        if($has_view)
        {
            $view->nest('theme_content', $has_view, $data);
        }
        // LOAD MAIN CONTENT
        
        // If there are listeners to the dom event, we'll pass them
        // the dom so they can modify it according to their needs, which
        // allows easy attachment/removal of elements.
        if (\Event::listeners("themes.render.view: {$page}"))
        {
            $result = \Event::until("themes.render.view: {$page}", array($view));

            if ( ! is_null($result))
            {
                if($result instanceof \DOMDocument)
                {
                    $view = $result->saveHTML();
                }
                elseif(is_string($result))
                {
                    $view = $result;
                }
                elseif(is_array($result))
                {
                    // array to dom
                    $view = '<!DOCTYPE html>';
                    $view .= Dom::arrayToDOMDocument($result, 'html')->saveHTML();
                }
                else
                {
                    throw new \Exception("View response is invalid.");
                }
            }     
        }

        return $view;
    }

    /**
     * returns view object with nested partial views
     * 
     * @param object $view
     * @return void Object by reference
     */
    private function _load_partials(&$view)
    {
        if( ! empty($this->_theme_partials['partials']))
        {
            $partials = $this->_theme_partials['partials'];

            foreach($partials as $partial => $data)
            {
                $bundle_parts = \Bundle::parse($partial);

                //the partial name will be allways the last
                //index of the array
                // partials.footer
                // admin::partials.footer
                $path_parts = explode('.', $bundle_parts['1']);
                $partial_name = 'partial_'.$path_parts[count($path_parts)-1];

                $rendered_partial = $this->render_partial($partial, $data);
                
                $view->nest($partial_name, $rendered_partial->view, $data);
            }
        }
    }

    /**
     * Return partial view rendered
     * 
     * @param string $partial
     * @param array $data
     */
    public function render_partial($partial, $data = array())
    {
        if (\Event::listeners("themes.render.partial: {$partial}"))
        {
            $result = \Event::until("themes.render.partial: {$partial}", array($view));

            if ( ! is_null($result))
            {
                if($result instanceof \DOMDocument)
                {
                    $view = $result->saveHTML();
                }
                elseif(is_string($result))
                {
                    $view = $result;
                }
                elseif(is_array($result))
                {
                    // array to dom
                    $view = '<!DOCTYPE html>';
                    $view .= Dom::arrayToDOMDocument($result, 'html')->saveHTML();
                }
                else
                {
                    // trow exception
                }
            }     
        }

        $data = $this->_share_data($data);

        $bundle_parts = \Bundle::parse($partial);

        $bundle_name = $bundle_parts['0'] == 'application' ? '' : $bundle_parts['0'].DS;
        
        $partial_path = str_replace('.', DS, $bundle_parts['1']);
        
        $theme_view_folder = $this->_theme_absolute_path.DS.'views'.DS;
        
        // Check for a custom path
        $has_partial = $this->_view_exists(str_replace('.', DS, $partial));
        if($has_partial)
        {
            return View::make($has_partial, $data);
        }

        // Check on the themes views folder
        $has_partial = $this->_view_exists($theme_view_folder.$bundle_name.'partials'.DS.$partial_path);
        if($has_partial)
        {
            return View::make($has_partial, $data);
        }

        // Check on custom folder (fallback for all themes)
        $has_partial = $this->_view_exists(path('public').'custom'.DS.'views'.DS.$bundle_name.'partials'.DS.$partial_path);
        if($has_partial)
        {
            return View::make($has_partial, $data);
        }

        // bundles folder
        $has_partial = $this->_view_exists($partial);
        if($has_partial)
        {
            return View::make($has_partial, $data);
        }

        // try to load from application views folder
        $has_partial = $this->_view_exists('partials'.DS.$partial_path);
        if($has_partial)
        {
            return View::make($has_partial, $data);
        }
        
        Log::error('Failed to render partial from '.$partial);
        
        throw new \Exception("Failed to render partial. Partial [$partial] doesn't exist. ");
    }

    /**
     * Share the data with views and partials
     * @param  array        $data data to be shared between views
     * @param  Laraval\View $view View to share data with  
     * @return mixed        If view was passed return the view with 
     *                      the shared data. data array otherwise
     */
    private function _share_data($data = array(), &$view = null)
    {
        if(empty($data) and $data !== null)
        {
            $data = array_merge($this->_theme_data, $data);
        }
        
        if($data === null)
        {
            $data = array();
        }
        
        if( ! empty($data))
        {
            if(isset($data['except']))
            {
                $except = array_flip($data['except']);
                unset($data['except']);
                foreach ($this->_theme_data as $key => $value) 
                {
                    if( ! isset($except[$key]))
                    {
                        $data[$key] = $value;
                    }
                }
            }
            if(isset($data['only']))
            {
                $only = array_flip($data['only']);
                unset($data['only']);
                foreach ($this->_theme_data as $key => $value) 
                {
                    if(isset($only[$key]))
                    {
                        $data[$key] = $value;
                    }
                }
            }
        }

        if( ! is_null($view))
        {
            foreach ($data as $key => $param)
            {
                $view::share($key, $param);
            }
            return $view;
        }
        else
        {
            return $data;
        }
    }

    /**
     * Veryfies if view file exists
     * @param  string $view_path String containing the view path
     * @return mixed             The full view path if view was found, null otherwise
     */
    private function _view_exists($view_path)
    {
        $result = null;
        
        // Look for a custom path
        if(file_exists($path = $view_path.BLADE_EXT))
        {
            $result = 'path: '.$path;
        }
        if(file_exists($path = $view_path.EXT))
        {
            $result = 'path: '.$path;
        }

        // We still can't find this view
        // lets look for a bundle view
        // or application folder view
        if($result == null)
        {
            $view_path = View::exists($view_path, true);
            if($view_path)
            {
                $result = 'path: '.$view_path;
            }
        }

        return $result;
    }
    
    /**
     * call theme_function 
     * 
     * @param string $path
     * @return void
     */
    private function _init_theme_func($path)
    {
        //theme_function
        $theme_function = "theme_" . $this->_theme_name;
        
        //load theme_function file
        $theme_function_file = $path .DS. 'theme_function.php';

        if(file_exists($theme_function_file))
        {
            require_once($theme_function_file);

            if(function_exists($theme_function))
            {
                call_user_func($theme_function, $this);
            }
        }
    }
    
    
    /**
     * return Metadata as string
     * 
     * @return string
     */
    private function _metadata()
    {
        if(is_array($this->_theme_metadata))
        {
            $string = implode(" ", $this->_theme_metadata);
            return $string;
        }

        return FALSE;
    }

    public function render_administration_top_menu($adm_menu_items = array(), $path = null)
    {
        // the path will be used if passed in the method call
        // for a specific path in the themes folder
        
        $main_links = array(
                'content'   => array(),
                'design'    => array(),
                'users'     => array(),
                'modules'   => array(),
                'settings'  => array(),
        );
        
        $all_modules_menus = array();
        
        foreach ($adm_menu_items as $module)
        {
            $menu = json_decode($module->menu, true);
            if($menu != null)
            {
                foreach ($menu as $item) 
                {
                    $menu_slug = strtolower($item['main_menu']);
                    $main_links[$menu_slug][] = $item;
                }
            }
        }
        
        // LOOK IN THE THEME FOLDERS FIRST
        $theme_name = $this->_theme_name;
        $theme_path_relative = $this->_theme_path . DS . $theme_name;
        $theme_path_absolute = $this->_theme_base_path . $theme_path_relative;
        $theme_partials_absolute = $theme_path_absolute .DS. 'partials'.DS;
        
        if (file_exists($tpath = $theme_partials_absolute.'admin_nav'. EXT))
        {
            return  View::make("path: " . $tpath, $this->_theme_data)->with('main_links', $main_links);
        }
        elseif (file_exists($tpath = $theme_partials_absolute.'admin_nav'. BLADE_EXT))
        {
            return View::make("path: " . $tpath, $this->_theme_data)->with('main_links', $main_links);
        }
        
        // IF NOT FOUND IN THE THEMES FOLDER RETURN FROM THEMES
        if (file_exists($tpath = \Bundle::path('themes').'views'.DS.'backend'.DS.'partials'.DS.'admin_nav'. EXT))
        {
            return View::make('themes::backend.partials.admin_nav', $this->_theme_data)->with('main_links', $main_links);
        }
        elseif (file_exists($tpath = \Bundle::path('themes').'views'.DS.'backend'.DS.'partials'.DS.'admin_nav'. BLADE_EXT))
        {
            return View::make('themes::backend.partials.admin_nav', $this->_theme_data)->with('main_links', $main_links);
        }
    }

    public function render_admin_sub_menu_items($menuArray, $class = '', $path = null)
    {
        // the path will be used if passed in the method call
        // for a specific path in the themes folder

        if(is_array($menuArray) and !empty($menuArray))
        {

            $theme_name = $this->_theme_name;
            $theme_path_relative = $this->_theme_path . DS . $theme_name;
            $theme_path_absolute = $this->_theme_base_path . $theme_path_relative;
            $theme_partials_absolute = $theme_path_absolute .DS. 'partials'.DS;

            // LOOK IN THE THEME FOLDERS FIRST
            if (file_exists($tpath = $theme_partials_absolute.'admin_nav_sub_items'. EXT))
            {
                return  View::make("path: " . $tpath)
                        ->with('items', $menuArray)
                        ->with('class', $class)
                        ->with('theme', $this);
            }
            elseif (file_exists($tpath = $theme_partials_absolute.'admin_nav_sub_items'. BLADE_EXT))
            {
                return View::make("path: " . $tpath)
                        ->with('items', $menuArray)
                        ->with('class', $class)
                        ->with('theme', $this);
            }
            
            // IF NOT FOUND IN THE THEMES FOLDER RETURN FROM THEMES
            if (file_exists($tpath = \Bundle::path('themes').'views'.DS.'backend'.DS.'partials'.DS.'admin_nav_sub_items'. EXT))
            {
                return View::make('themes::backend.partials.admin_nav_sub_items')
                        ->with('items', $menuArray)
                        ->with('class', $class)
                        ->with('theme', $this);
            }
            elseif (file_exists($tpath = \Bundle::path('themes').'views'.DS.'backend'.DS.'partials'.DS.'admin_nav_sub_items'. BLADE_EXT))
            {
                return View::make('themes::backend.partials.admin_nav_sub_items')
                        ->with('items', $menuArray)
                        ->with('class', $class)
                        ->with('theme', $this);
            }
            
        }
    }

    public function dom_to_array($root)
    {
        $result = array();

        if ($root->hasAttributes())
        {
            $attrs = $root->attributes;

            foreach ($attrs as $i => $attr)
                $result[$attr->name] = $attr->value;
        }

        $children = $root->childNodes;
        if(isset($children))
        {
            if ($children->length == 1)
            {
                $child = $children->item(0);

                if ($child->nodeType == XML_TEXT_NODE)
                {
                    $result['_value'] = $child->nodeValue;

                    if (count($result) == 1)
                        return $result['_value'];
                    else
                        return $result;
                }
            }
            $group = array();

            for($i = 0; $i < $children->length; $i++)
            {
                $child = $children->item($i);

                if (!isset($result[$child->nodeName]))
                    $result[$child->nodeName] = $this->dom_to_array($child);
                else
                {
                    if (!isset($group[$child->nodeName]))
                    {
                        $tmp = $result[$child->nodeName];
                        $result[$child->nodeName] = array($tmp);
                        $group[$child->nodeName] = 1;
                    }

                    $result[$child->nodeName][] = $this->dom_to_array($child);
                }
            }
        }
        return $result;
    } 
}

/**
* Theme Helper Functions
* 
*/

/**
* Renders a partial of a theme or load a laravel view
*/
function render_partial($partial, $data = array())
{
    $theme = \IoC::resolve('Theme');
    return $theme->render_partial($partial, $data);
}

function render_admin_top_menu($partial, $data = array())
{
    $theme = \IoC::resolve('Theme');
    return $theme->render_administration_top_menu($partial, $data);
}

function render_admin_sub_menu_items($menuArray, $class = '', $path = null)
{
    $theme = \IoC::resolve('Theme');
    return $theme->render_admin_sub_menu_items($menuArray, $class, $path);
}

function add_asset($filename, $path = null, $dependencies = array(), $container = '')
{
    $theme = \IoC::resolve('Theme');
    return $theme->add_asset($filename, $path, $dependencies, $container);
}