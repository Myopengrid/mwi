<?php

Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('modules::backend.modules@index');
});

Route::put(ADM_URI.'/(:bundle)/enable/(:any)', function($parameter)
{
    return Controller::call('modules::backend.modules@enable', array($parameter));
});

Route::put(ADM_URI.'/(:bundle)/disable/(:any)', function($parameter)
{
    return Controller::call('modules::backend.modules@disable', array($parameter));
});

Route::delete(ADM_URI.'/(:bundle)/(:any)', function($parameter)
{
    return Controller::call('modules::backend.modules@uninstall', array($parameter));
});

Route::post(ADM_URI.'/(:bundle)/(:any)', function($parameter)
{
    return Controller::call('modules::backend.modules@install', array($parameter));
});

Route::get(ADM_URI.'/(:bundle)/upload', function()
{
    return Controller::call('modules::backend.modules@upload');
});

Route::post(ADM_URI.'/(:bundle)/upload', function()
{
    return Controller::call('modules::backend.modules@upload');
});

Route::post(ADM_URI.'/(:bundle)/remove/(:any)', function($parameter)
{
    return Controller::call('modules::backend.modules@remove', array($parameter));
});

Route::get(ADM_URI.'/(:bundle)/core', function()
{
    return Controller::call('modules::backend.modules@core');
});