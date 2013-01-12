<?php

// INDEX
Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('navigation::backend.navigation.groups@index');
});

// CREATE
Route::post(ADM_URI.'/(:bundle)/groups', function()
{
    return Controller::call('navigation::backend.navigation.groups@create');
});

// NEW
Route::get(ADM_URI.'/(:bundle)/groups/new', function( $parameter = null )
{
    return Controller::call('navigation::backend.navigation.groups@new', array($parameter));
});

// SHOW

// UPDATE

// DELETE
Route::delete(ADM_URI.'/(:bundle)/groups/(:num)', function($parameter = null)
{
    return Controller::call('navigation::backend.navigation.groups@destroy', array($parameter));
});


/////////////////////////////////
//////////// LINKS //////////////
/////////////////////////////////

// SHOW
Route::get(ADM_URI.'/(:bundle)/links/(:num)', function($parameter)
{
    return Controller::call('navigation::backend.navigation.links@show', array($parameter));
});

Route::get(ADM_URI.'/(:bundle)/links/(:num)/edit', function($parameter)
{
    return Controller::call('navigation::backend.navigation.links@edit', array($parameter));
});

// UPDATE
Route::put(ADM_URI.'/(:bundle)/links/(:num)', function($parameter)
{
    return Controller::call('navigation::backend.navigation.links@update', array($parameter));
});

// NEW
// not following the convention 
// since we need to pass the group
// id where this link will be created
Route::get(ADM_URI.'/(:bundle)/links/new/(:num)', function($parameter)
{
    return Controller::call('navigation::backend.navigation.links@new', array($parameter));
});

// CREATE
Route::post(ADM_URI.'/(:bundle)/links', function()
{
    return Controller::call('navigation::backend.navigation.links@create');
});

// DELETE
Route::delete(ADM_URI.'/(:bundle)/links/(:num)', function($parameter)
{
    return Controller::call('navigation::backend.navigation.links@destroy', array($parameter));
});