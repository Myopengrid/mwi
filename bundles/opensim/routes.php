<?php

// OpenSim Controller
Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('opensim::backend.opensim@index');
});

// OpenSim Settings Controller
Route::get(ADM_URI.'/(:bundle)/settings', function()
{
    return Controller::call('opensim::backend.settings@index');
});

Route::put(ADM_URI.'/(:bundle)/settings', function()
{
    return Controller::call('opensim::backend.settings@update');
});
// Ajax regions list
Route::post(ADM_URI.'/(:bundle)/regions', function()
{
    if (Request::ajax())
    {
        return Controller::call('opensim::backend.regions@ajax');
    }
});
// View Region Details
Route::get(ADM_URI.'/(:bundle)/regions/(:any?)/edit', function($region_uuid)
{
    return Controller::call('opensim::backend.regions@edit', array($region_uuid));
});

// Delete Region
Route::delete(ADM_URI.'/(:bundle)/regions/(:any?)', function($region_uuid)
{
    return Controller::call('opensim::backend.regions@destroy', array($region_uuid));
});


// Database
Route::get(ADM_URI.'/(:bundle)/settings/database', function()
{
    return Controller::call('opensim::backend.settings@database');
});

Route::put(ADM_URI.'/(:bundle)/settings/database', function()
{
    return Controller::call('opensim::backend.settings@update_db');
});

// Regions
Route::get(ADM_URI.'/(:bundle)/regions', function()
{
    return Controller::call('opensim::backend.regions@index');
});