<?php

Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('users::backend.users@index');
});

Route::get(ADM_URI.'/(:bundle)/new', function()
{
    return Controller::call('users::backend.users@new');
});

Route::get(ADM_URI.'/(:bundle)/(:num)/edit', function($parameter)
{
    return Controller::call('users::backend.users@edit', array($parameter));
});

Route::put(ADM_URI.'/(:bundle)/(:num)', function($parameter)
{
    return Controller::call('users::backend.users@update', array($parameter));
});

Route::post(ADM_URI.'/(:bundle)/action', function()
{
    return Controller::call('users::backend.users@action');
});

Route::post(ADM_URI.'/(:bundle)', function()
{
    if (Request::ajax()) // Ajax Filter
    {    
         return Controller::call('users::backend.users@index');
    }
    else                // Create
    {
        return Controller::call('users::backend.users@create');
    }
});

Route::delete(ADM_URI.'/(:bundle)/(:num)', function($parameter)
{
    return Controller::call('users::backend.users@destroy', array($parameter));
});