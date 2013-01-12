<?php

Autoloader::namespaces(array(
    'Groups\Model' => Bundle::path('groups').'models',
    'Groups'       => Bundle::path('groups').'libraries',
));


Route::filter('after', function($response)
{
    if ( Request::ajax() && Request::method() !== 'GET' )
    {
        $response->header('Cache-Control: no-cache', 'must-revalidate');
    }
});