<?php

$adm_uri = Config::get('settings::core.administration_uri');
// INDEX
Route::any($adm_uri.'/(:bundle)', function()
{
    return Controller::call('settings::backend.settings@index');
});

// KEYWORDS
Route::get($adm_uri.'/(:bundle)/keywords', function()
{
    return Controller::call('settings::backend.keywords@index');
});
Route::put($adm_uri.'/(:bundle)/keywords', function()
{
    return Controller::call('settings::backend.keywords@update');
});
Route::get($adm_uri.'/(:bundle)/keywords/autocomplete/(:any?)', function($params = '')
{
    return Controller::call('settings::backend.keywords@autocomplete', array($params));
});


// UPDATE SETTINGS
Route::put($adm_uri.'/(:bundle)', function()
{
    return Controller::call('settings::backend.settings@update');
});

// UPDATE LANGUAGE
Route::put($adm_uri.'/(:bundle)/set_language', function()
{
    return Controller::call('settings::backend.settings@set_language');
});