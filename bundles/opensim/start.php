<?php

Autoloader::namespaces(array(
    'Opensim\Model' => dirname(__FILE__).DS.'models'.DS,
    'Opensim'       => dirname(__FILE__).DS.'libraries'.DS,
));

/*
|--------------------------------------------------------------------------
| Opensim Database
|--------------------------------------------------------------------------
|
| Load OpenSim database
|
*/
$db_is_ready = Config::get('settings::core.passes_db_settings');

if((bool)$db_is_ready)
{
    $opensim_db_config = Config::get('opensim::database.connections.default');
    Config::set('database.connections.opensim', $opensim_db_config);
}


/*
|--------------------------------------------------------------------------
| Opensim Event Listners
|--------------------------------------------------------------------------
|
| Load opensim listners for application new users and other events
|
*/
include(dirname(__FILE__).DS.'events'.EXT);

/*
|--------------------------------------------------------------------------
| OpenSim Helper
|--------------------------------------------------------------------------
|
| Load OpenSim helper file
|
*/
include(dirname(__FILE__).DS.'helper'.EXT);