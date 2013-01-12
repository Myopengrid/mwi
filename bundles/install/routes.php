<?php

if( !Install\Installer::is_complete() )
{
    // Route for AJAX Requests
    Route::post('install/ajax/(:any?)/(:any?)', function($method = 'index', $parameters = null)
    {
        return Controller::call("install::frontend.ajax@{$method}", array($parameters));
    });

    Route::any('install/(:any?)/(:any?)', function($method = 'index', $parameters = null)
    {
        return Controller::call("install::frontend.install@{$method}", array($parameters));
    });


    Route::get('/', function()
    {
        return Redirect::to('install');
    });
    
    Event::listen('500', function($message)
    {
        if(Request::ajax())
        {
            $response['success'] = 'false';
            $response['message'] = $message;
            
            echo Response::make(json_encode($response))
                                    ->header('Cache-Control', 'no-cache, must-revalidate')
                                    ->header('Expires', 'Mon, 01 Jan 2000 00:00:00 GMT')
                                    ->header('Content-Type', 'application/json');
            exit(1);
        }
    });
}
