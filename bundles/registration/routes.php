<?php

Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('registration::backend.registration@index');
});


// Account activation link
Route::get('(:bundle)/activate/(:any)', function($activation_code)
{
    return Controller::call('registration::frontend.registration@activate', array($activation_code));
});

Route::put(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('registration::backend.registration@update');
});

// shows password reset form
Route::get('(:bundle)/pwreset', function()
{
    return Controller::call('registration::frontend.registration@pwreset');
});

// handles submit of password reset form
Route::post('(:bundle)/pwreset', function()
{
    return Controller::call('registration::frontend.registration@pwreset');
});

// handles link sent to email to reset password
// registration/reset_pass/{{ user:id }}/{{ forgotten_password_code }}
Route::get('(:bundle)/reset_pass/(:num)/(:any)', function($user_id, $code)
{
    return Controller::call('registration::frontend.registration@reset_pass', array($user_id, $code));
});

Route::post('(:bundle)/reset_pass', function()
{
    return Controller::call('registration::frontend.registration@reset_pass');
});



Route::get('/signup', function()
{
    return Controller::call('registration::frontend.registration@index');
});

Route::post('/signup', function()
{
    return Controller::call('registration::frontend.registration@create');
});