<?php

Route::any('login', function() {

    return Controller::call('auth::frontend.auth@login', array());

});

Route::get('logout', function() {

    return Controller::call('auth::frontend.auth@logout', array());

});

/* Filter for Admin and Private areas */
Route::filter('auth', function()
{
    if ( Auth::guest() ) 
    {
        Session::put('last_page', URL::current());
        
        return Redirect::to('login');
    }
});
