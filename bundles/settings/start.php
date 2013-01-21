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
| Time Zone
|--------------------------------------------------------------------------
|
| Sets application time zone
|
*/
$time_zone = Config::get('settings::core.settings_time_zone');
if(!isset($time_zone) or empty($time_zone))
{
    $time_zone = 'America/Sao_Paulo';
}
date_default_timezone_set($time_zone);

/*
|--------------------------------------------------------------------------
| Date Format
|--------------------------------------------------------------------------
|
| Sets constant variable for application date format 
|
*/
define("APP_DATE_FORMAT", Config::get('settings::core.settings_date_format'));

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
