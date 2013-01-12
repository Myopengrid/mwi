<?php
// FRONTEND THEMES
Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('themes::backend.themes@index');
});

// BACKEND THEMES
Route::get(ADM_URI.'/(:bundle)/backend', function()
{
    return Controller::call('themes::backend.themes@backend');
});

// NOT INSTALLED THEMES
Route::get(ADM_URI.'/(:bundle)/not_installed', function()
{
    return Controller::call('themes::backend.themes@not_installed');
});

// UPLOAD THEMES FORM
Route::get(ADM_URI.'/(:bundle)/upload', function()
{
    return Controller::call('themes::backend.themes@upload');
});

// UPLOAD THEMES
Route::post(ADM_URI.'/(:bundle)/upload', function()
{
    return Controller::call('themes::backend.themes@upload');
});

// EDIT THEME
Route::get(ADM_URI.'/(:bundle)/(:num)/edit', function($parameters)
{
    return Controller::call('themes::backend.themes@edit', array($parameters));
});

// UPDATE THEME
Route::put(ADM_URI.'/(:bundle)/(:num)', function($parameters)
{
    return Controller::call('themes::backend.themes@update', array($parameters));
});

// SET DEFAULT THEME
Route::put(ADM_URI.'/(:bundle)/set_default', function()
{
    return Controller::call('themes::backend.themes@set_default');
});

// INSTALL THEME
Route::post(ADM_URI.'/(:bundle)/install/(:any)', function($parameters)
{
    return Controller::call('themes::backend.themes@install', array($parameters));
});

// DELETE THEME
Route::delete(ADM_URI.'/(:bundle)/(:any?)', function($parameters)
{
    return Controller::call('themes::backend.themes@destroy', array($parameters));
});