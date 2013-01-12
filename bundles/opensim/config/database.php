<?php

$opensim_db_settings = Settings\Model\Setting::where_module_slug('opensim')->where_section('opensim_db_settings')->get();

foreach ($opensim_db_settings as $setting) 
{
    $os_db_settings[$setting->slug] = $setting->value;
}
return array(

    'connections' => array(

        'default' => array(
            'driver'   => $os_db_settings['database_driver'],
            'host'     => $os_db_settings['database_host'],
            'database' => $os_db_settings['database_name'],
            'username' => $os_db_settings['database_user'],
            'password' => $os_db_settings['database_password'],
            'charset'  => 'utf8',
            'prefix'   => $os_db_settings['database_table_prefix'],
            'port'     => $os_db_settings['database_port'],
        ),
    ),  
);