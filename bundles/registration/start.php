<?php

Autoloader::namespaces(array(
    'Registration\Model' => Bundle::path('registration').'models'.DS,
    'Registration'       => Bundle::path('registration').'libraries'.DS,
));


/*
|--------------------------------------------------------------------------
| Load Application Helpers file
|--------------------------------------------------------------------------
|
| Load all registration functions available globaly in the application
|
*/
include(__DIR__.DS.'helpers.php');

/*
|--------------------------------------------------------------------------
| Registration Event Listners
|--------------------------------------------------------------------------
|
| Load registration listners for module
|
*/
include(dirname(__FILE__).DS.'events'.EXT);