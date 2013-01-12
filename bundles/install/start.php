<?php

Autoloader::namespaces(array(
    'Install' => Bundle::path('install').'libraries',
));

if(!Session::has('adm_lang'))
{
    Session::put('adm_lang', 'us');
}

/*
|--------------------------------------------------------------------------
| Set Application Security Key
|--------------------------------------------------------------------------
|
| The application security key is used by the encryption and cookie classes 
| to generate secure encrypted strings and hashes. At this poing we will 
| make sure that every installation contain an unique key with at least 32 
| characters of random gibberish.
|
*/

$app_installed = Config::get('application.installed');
$key_installed = Session::get('key_installed');
if( !$app_installed or !$key_installed)
{
    Install\Installer::set_app_key();
    Session::put('key_installed', true);
}

if(!defined('ADM_URI')) define('ADM_URI', 'admin');