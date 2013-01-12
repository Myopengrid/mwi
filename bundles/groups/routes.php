<?php
Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('groups::backend.groups@index');
});

Route::get(ADM_URI.'/(:bundle)/(:num)/edit', function($parameters)
{
    return Controller::call('groups::backend.groups@edit', array($parameters));
});

Route::get(ADM_URI.'/(:bundle)/new', function()
{
    return Controller::call('groups::backend.groups@new');
});

Route::put(ADM_URI.'/(:bundle)/(:num)', function($parameters)
{
    return Controller::call('groups::backend.groups@update', array($parameters));
});

Route::post(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('groups::backend.groups@create');
});

Route::delete(ADM_URI.'/(:bundle)/(:num)', function($parameters)
{
    return Controller::call('groups::backend.groups@destroy', array($parameters));
});

// used by ajax to check if the 
// slug is already in use when 
// creating a new users group
Route::post(ADM_URI.'/(:bundle)/check_slug', function()
{
    return Controller::call('groups::backend.groups@check_slug');
});