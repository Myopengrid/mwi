<?php

Autoloader::namespaces(array(
    'Permissions\Model'  => Bundle::path('permissions').'models'.DS,
    'Permissions'        => Bundle::path('permissions').'libraries'.DS,
));

Route::filter('mwi.admin_controller_start', function($controller)
{
    $permissions = new \Permissions\Check(\Request::route(), $controller);
    
    if( !Bundle::exists('auth'))
    {
        return;
    }
    
    // Fix route bundle if 
    // its an administration route
    $uri = Request::route()->uri;
    $uri_parts = explode('/', $uri);
    // check if is set 
    // check if first part is administration uri 
    // check if is not only the dashboard http://site.com/[admin]
    if(isset($uri_parts['0']) and $uri_parts['0'] = ADM_URI and count($uri_parts) > 1)
    {
        unset($uri_parts['0']);
        $uri = implode('/', $uri_parts);
        Request::route()->bundle = Bundle::handles($uri);
        $controller->bundle = Request::route()->bundle;
    }

    $result = $permissions::can(Auth::user());
    if(isset($result))
    {
        if( ! $result->is_allowed )
        {
            if (Request::ajax())
            {
                return 'not permited';
            }
            else
            {
                return \Response::error('401', get_object_vars($result));
            }
        }
    }
    else
    {
        if (Request::ajax())
        {
            return View::make('permissions::partials.ajax_not_authorized_page_details')->render();
        }
        else
        {
            $data = array(
                'username' => Auth::user()->avatar_first_name.' '.Auth::user()->avatar_last_name,
                'group'    => Auth::user()->group->name,
                'role'     => 'unknown'
            );
            return \Response::error('401', $data);
        }
    }
});