<?php namespace Permissions;

use Permissions\Response;
use Permissions\Model\Permission;

class Check {

    public static $user;

    public static $controller;
    
    public static $bundle;
    
    public static $action;
    
    public static $method;
    
    public static $is_ajax;

    public static $route;

    public static $user_group;

    public function __construct($requester, $controller)
    {
        self::$route = $requester;

        self::$controller   = $controller;
        self::$bundle       = $requester->bundle;
        self::$action       = $requester->controller_action;
        self::$method       = $requester->method;
        self::$is_ajax      = \Request::ajax();
    }

    public static function group_has_role($module, $role)
    {
        // User with id 1 should be administrator
        if(self::$user->id == 1)
        {
            return true;
        }
        
        // Get the user Group
        if( !isset(self::$user->group_id))
        {
            return false;
        }

        self::$user_group = \Groups\Model\Group::find(self::$user->group_id);

        if( !isset(self::$user_group) or empty(self::$user_group))
        {
            //Group was not found
            return false;
        }

        if(self::$user_group->slug == 'admin')
        {
            // this is an administrator
            return true;
        }
        
        $permission = Permission::where_group_id(self::$user->group->id)
                                  ->where_module_name($module)->first();

        if( !isset($permission) or empty($permission))
        {
            // The role is not even in the permissions table
            return false;
        }

        $roles = json_decode($permission->roles, true);

        if( !isset($roles) or empty($roles))
        {
            //failed to decode json from permissions table
            return false;
        }

        return $find_role = array_filter($roles, function($roles) use($role)
        {
            if($roles['slug'] == $role and $roles['value'] == true )
            {
                return true;
            }
            return false;
        });
    }

    public static function can($user, $module = null, $role = null, $redirect_to = '', $message = '')
    {
        self::$user = $user;

        if(empty($message))
        {
            $message = 'This user has no granted permissions to access this area.';
        }

        // Module was not provided 
        // lets try to guess it
        $controller_parts = explode('.', self::$route->controller);
        if($module === null)
        {
            if(!isset(self::$route->bundle))
            {
                $module = $controller_parts['1'];
            }
            else
            {
                $module =  self::$route->bundle;
            }
        }
        // Role was not provided 
        // lets try to guess it
        if($role === null)
        {
            // layer_module_controller_method_action
            $role = $controller_parts['0'].'_'.$module.'_'.$controller_parts[count($controller_parts)-1].'_'.strtolower(self::$method).'_'.self::$action;

        }
        
        // Debug for permissions
        if(\Config::get('error.detail'))
        {
            \Log::error(self::$user->avatar_first_name.' '. self::$user->avatar_last_name.' is trying to ' .$role. ' in the module ' . $module);
        }

        $is_allowed = self::group_has_role($module, $role);

        $response = new Result();
        $response->is_allowed = $is_allowed;
        $response->module = $module;
        
        if(self::$is_ajax and ! $is_allowed )
        {
            //return json_encode(array('error' => ($message ? $message : lang('cp_access_denied')) ));
        }
        elseif( ! $is_allowed )
        {
            $response->message = $message;
            $response->message_type = 'warn';
            
            if(empty($redirect_to))
            {
                $response->username = self::$user->avatar_first_name.' '.self::$user->avatar_last_name;
                if(isset(self::$user_group->name))
                {
                    $response->group = self::$user_group->name;
                }
                else
                {
                    $response->group = 'Unknown';
                }
                $response->role = $role;
                
                return $response;
               
            }
            return $response;
        }
        else
        {
            return $response;
        }
    }
}