<?php namespace Navigation\Model;

use \Eloquent;
use \Laravel\Auth;

class Group extends Eloquent {

    public static $table = 'navigation_groups';

    public function links()
    {
        return $this->has_many('Navigation\Model\Link')
                    ->order_by('order', 'asc');
    }

    public function get_links()
    {
        $links_raw = array();
        foreach ($this->links as $link)
        {
            if($this->passes(explode(',', $link->restricted_to), new Auth))
            {
                // Check the link type to set the wright url
                $url = '#';
                if($link->link_type == 'url' )
                {
                    $url = $link->url;
                }
                if($link->link_type == 'uri' )
                {
                    $url = $link->uri;
                }
                if($link->link_type == 'module' )
                {
                    $url = 'what put here';
                }
                if($link->link_type == 'page' )
                {
                    // If pages module is not installed 
                    // the link_type should not be page
                    // lets test it
                    if(\Bundle::exists('pages'))
                    {
                        if( ! is_null($link->page))
                        {
                            $url = $link->page->slug;
                        }
                        else
                        {
                            $url = '404';
                        }
                        
                    }
                }
                

                $links_raw[$link->id] = array(
                    'id'        => $link->id,
                    'li_id'     => 'link_',
                    'link_type' => $link->link_type,
                    'rel'       => $this->id,
                    'parent_id' => $link->parent,
                    'title'     => $link->title,
                    'url'       => $url,
                    'target'    => $link->target,
                    'class'     => $link->class,
                );
            }
        }

        $links_result = array();
        foreach ($links_raw as $link)
        {
            if (array_key_exists($link['parent_id'], $links_raw))
            {
                // Add this page to the children array of the parent page.
                $links_raw[$link['parent_id']]['children'][] =& $links_raw[$link['id']];
            }

            // This is a root page.
            if ($link['parent_id'] == 0)
            {
                $links_result[] =& $links_raw[$link['id']];
            }
        }
        return $links_result;
    }

    public function passes($link_restrictions, $auth)
    {
        $restriction_count = count($link_restrictions);

        $user = $auth::user();

        // Admin have access to any link
        if(isset($user) and ($user->id == 1 or $user->group->slug == 'admin'))
        {
            return true;
        }

        if( !empty($link_restrictions) )
        {
            if($restriction_count == 1)
            {
                $restriction = $link_restrictions['0'];

                if($restriction == 0)
                {
                    return true;
                }

                if($restriction == -2 and $auth::check())
                {
                    // link for authenticated
                    // users and user is a guest
                    return true;
                }
                
                if($restriction == -1 and !$auth::check())
                {
                    // link for guests and user
                    // is authenticated
                    return true;
                }

                // We have a group restriction
                // check if the user group is set
                // if its not set means the user
                // is a guest
                if( !isset($user->group_id) )
                {
                    return false;
                }

                if($user->group_id == $restriction)
                {
                    return true;
                }
                
                return false;
            }
            else
            {
                if(in_array(-2, $link_restrictions) and $auth::check())
                {
                    // page for authenticated
                    // users and user is a guest
                    return true;
                }
                
                if(in_array(-1, $link_restrictions) and !$auth::check())
                {
                    // page for guests and user
                    // is authenticated
                    return true;
                }
                
                if(in_array(0, $link_restrictions))
                {
                    return true;
                }

                // Multy restrictions
                foreach ($link_restrictions as $restriction) 
                {
                    if( !isset($user->group_id) )
                    {
                        return false;
                    }
                    
                    if($user->group_id == $restriction)
                    {
                        return true;
                    }
                }
                return false;
            }
        }
        else
        {
            // There is no restrictions
            return true;
        }
    }
}