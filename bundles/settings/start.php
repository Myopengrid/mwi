<?php

Autoloader::namespaces(array(
    'Settings\Model' => Bundle::path('settings').'models',
    'Settings'       => Bundle::path('settings').'libraries',
));

if(!defined('ADM_URI')) define('ADM_URI', Config::get('settings::core.administration_uri'));
define("ADM_LANG", Config::get('settings::core.backend_language'));
define("APP_LANG", Config::get('application.language'));
Config::set('application.language', Config::get('settings::core.frontend_language'));

/*
|--------------------------------------------------------------------------
| Load Settings Module Helpers
|--------------------------------------------------------------------------
|
| Load all settings functions that will be available globaly in 
| the application
|
*/
include(__DIR__.DS.'helpers.php');
