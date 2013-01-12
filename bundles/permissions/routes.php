<?php

Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('permissions::backend.permissions@index');
});

Route::get(ADM_URI.'/(:bundle)/(:num)/edit', function($parameter)
{
    return Controller::call('permissions::backend.permissions@edit', array($parameter));
});

Route::put(ADM_URI.'/(:bundle)/(:num)', function($parameter)
{
    return Controller::call('permissions::backend.permissions@update', array($parameter));
});