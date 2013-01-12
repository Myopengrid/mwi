<?php

// EMAIL TEMPLATE CONTROLLER
Route::get(ADM_URI.'/(:bundle)/template', function()
{
    return Controller::call('email::backend.email.template@index');
});

Route::get(ADM_URI.'/(:bundle)/template/new/(:num?)', function($parameter = null)
{
    return Controller::call('email::backend.email.template@new', array($parameter));
});

Route::post(ADM_URI.'/(:bundle)/template', function()
{
    return Controller::call('email::backend.email.template@create');
});

Route::get(ADM_URI.'/(:bundle)/template/(:num)/edit', function($parameter)
{
    return Controller::call('email::backend.email.template@edit', array($parameter));
});

Route::put(ADM_URI.'/(:bundle)/template/(:num)', function($parameter)
{
    return Controller::call('email::backend.email.template@update', array($parameter));
});

Route::delete(ADM_URI.'/(:bundle)/template/(:num)', function($parameter)
{
    return Controller::call('email::backend.email.template@destroy', array($parameter));
});

// EMAIL CONTROLLER
Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('email::backend.email.email@index');
});

Route::put(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('email::backend.email.email@update');
});

Route::get(ADM_URI.'/(:bundle)/new', function()
{
    return Controller::call('email::backend.email.email@new');
});

Route::post(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('email::backend.email.email@create');
});

// Used by ajax to create users drop
// down when selecting/filtering users
// to send an email 
Route::post(ADM_URI.'/(:bundle)/get_users', function()
{
    return Controller::call('email::backend.email.email@get_users');
});

// Route::post(ADM_URI.'/(:bundle)/order', function()
// {
//     return Controller::call('email::backend.email@order');
// });

 




// Route::any(ADM_URI.'/(:bundle)', function()
// {
//     return Controller::call('email::backend.email@index');
// });

// Route::any(ADM_URI.'/(:bundle)/(:any)/(:any?)', function($method, $parameter = null)
// {
//     return Controller::call("email::backend.email@{$method}", array($parameter) );
// });