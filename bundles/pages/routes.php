<?php
//
// FRONTEND ROUTES
// 
Route::get('/page/(:any)', function($url) 
{
    return Controller::call('pages::frontend.pages@index', array($url));
});

//
// BACKEND ROUTES
// 
// VIEW ALL PAGES
Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('pages::backend.pages@index');
});

// CREATE NEW PAGE
Route::post(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('pages::backend.pages@create');
});

// GET FORM FOR NEW PAGE
Route::get(ADM_URI.'/(:bundle)/new/(:num?)', function( $parameter = null )
{
    return Controller::call('pages::backend.pages@new', array($parameter));
});

// VIEW SPECIFIC PAGE
Route::get(ADM_URI.'/(:bundle)/(:num)', function($parameter = null)
{
    return Controller::call('pages::backend.pages@show', array($parameter));
});


// EDIT SPECIFIC PAGE
Route::get(ADM_URI.'/(:bundle)/(:num)/edit', function($parameter = null)
{
    return Controller::call('pages::backend.pages@edit', array($parameter));
});

// EDIT UPDATE PAGE
Route::put(ADM_URI.'/(:bundle)/(:num)', function($parameter = null)
{
    return Controller::call('pages::backend.pages@update', array($parameter));
});

// DELETE PAGE
Route::delete(ADM_URI.'/(:bundle)/(:num)', function($parameter = null)
{
    return Controller::call('pages::backend.pages@destroy', array($parameter));
});

// PREVIEW PAGE
Route::get(ADM_URI.'/(:bundle)/preview/(:num)', function($parameter = null)
{
    return Controller::call('pages::backend.pages@preview', array($parameter));
});


