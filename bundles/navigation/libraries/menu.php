<?php namespace Navigation;

use Laravel\View;

class Menu {
    
    public $theme;

    public function __construct()
    {
        $this->theme = \IoC::resolve('Theme');
    }
    
    public function get_nav($menu = '', $partial_path = null)
    {
        if(\Event::listeners("menu.render.navigation: {$menu}"))
        {
            $result = \Event::until("menu.render.navigation: {$menu}");

            if( ! is_null($result))
            {
                if($result instanceof \DOMDocument)
                {
                    return $result->saveHTML();
                }
                elseif(is_string($result))
                {
                    return $result;
                }
                elseif(is_array($result))
                {
                    return Dom::arrayToDOMDocument($result)->saveHTML();
                }
                else
                {
                    throw new \Exception("Menu event response is invalid. Supported responses are: [DOMDocument], [String], [Array]");
                }
            }     
        }

        $navigation_group = \Navigation\Model\Group::with(array(
                                'links' => function($query)
                                {
                                    $query->order_by('order', 'asc');
                                }))
                                ->where('slug', '=' , $menu)
                                ->first();

        // This navigation group is available?
        if(isset($navigation_group) and !empty($navigation_group))
        {
            $items = $navigation_group->get_links();

            if (\Event::listeners("menu.merge.navigation: {$menu}", array($items)))
            {
                $result = \Event::until("menu.merge.navigation: {$menu}", array($items)); 
                $items = array_merge_recursive($items, $result); 
                sort($items);
            }

            if (\Event::listeners("menu.make.navigation: {$menu}", array($items)))
            {
                $items = \Event::until("menu.make.navigation: {$menu}", array($items));    
            }

            if (\Event::listeners("menu.make.navigation", array($items)))
            {
                $items = \Event::until("menu.make.navigation", array($items));    
            }

            return $this->make($items, '', $partial_path);
        }
        else
        {
            return 'ERROR: The navigation group ['. $menu .'] does not exist.';
        }
    }
    
    public function make($items = array(), $html = '', $partial_path = null)
    {
        foreach ($items as $child)
        {
            $url = empty($child['url']) ? '#' : $child['url'];

            $has_partial = false;

            // if partial path is not null
            // lets try to load from there first
            if( !is_null($partial_path))
            {
                $has_partial = $this->view_exists($partial_path);
                if($has_partial)
                {
                    $html .= View::make($has_partial)
                                ->with('child', $child)
                                ->with('theme', $this)
                                ->with('url', $url);
                    continue;
                }
            }

            // look on themes partial
            $has_partial = $this->view_exists($this->theme->_theme_absolute_path.DS.'views'.DS.'partials'.DS.'menu_li');
            if($has_partial)
            {
                $html .= View::make($has_partial)
                            ->with('child', $child)
                            ->with('theme', $this)
                            ->with('url', $url);
                continue;
            }

            // look on shared folder partials
            $has_partial = $this->view_exists(path('public').'shared'.DS.'views'.DS.'partials'.DS.'menu_li');
            if($has_partial)
            {
                $html .= View::make($has_partial)
                            ->with('child', $child)
                            ->with('theme', $this)
                            ->with('url', $url);
                continue;
            }

            // we did not find the partial
            // load the default provided by
            // navigation module
            $html .= View::make('navigation::partials.menu_li')
                            ->with('child', $child)
                            ->with('theme', $this)
                            ->with('url', $url);
        }

        return $html;
    }

    /**
     * Verifies if view file exists
     * @param  string $view_path String containing the view path
     * @return mixed             The full view path if view was found, null otherwise
     */
    private function view_exists($view_path)
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
}