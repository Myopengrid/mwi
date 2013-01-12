<?php

Route::get(ADM_URI, function()
{
    return Controller::call('admin::backend.admin@index');
});

Route::get('cron', function()
{
    return Controller::call('admin::backend.cron@index');
});