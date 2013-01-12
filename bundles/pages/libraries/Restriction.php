<?php namespace Pages;

use Laravel\Auth;

class Restriction {
    
    public $default_restrictions = array(
        '-2' => 'Authenticated',
        '-1' => 'Guests',
        '0'  => 'Any',
    );

    public static function passes($page_restrictions, $auth)
    {
        $restriction_count = count($page_restrictions);

        $user = $auth::user();

        // Admin have access to any page
        if(isset($user) and ($user->id == 1 or $user->group->slug == 'admin'))
        {
            return true;
        }

        if( !empty($page_restrictions) )
        {
            if($restriction_count == 1)
            {
                $restriction = $page_restrictions['0'];

                if($restriction == 0)
                {
                    return true;
                }

                if($restriction == -2 and $auth::check())
                {
                    // page for authenticated
                    // users and user is a guest
                    return true;
                }
                
                if($restriction == -1 and !$auth::check())
                {
                    // page for guests and user
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
                // Multy restrictions
                if(in_array(-2, $page_restrictions) and $auth::check())
                {
                    // page for authenticated
                    // users and user is a guest
                    return true;
                }
                
                if(in_array(-1, $page_restrictions) and !$auth::check())
                {
                    // page for guests and user
                    // is authenticated
                    return true;
                }
                
                if(in_array(0, $page_restrictions))
                {
                    return true;
                }

                // Multy restrictions
                foreach ($page_restrictions as $restriction) 
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