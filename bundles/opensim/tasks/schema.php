<?php

class Opensim_Schema_Task {

    public function __construct()
    {
        Bundle::register('settings');
        Bundle::start('settings');
    }

    public function install()
    {   
        $module = Modules\Model\Module::where_slug('opensim')->first();

        $passes_db_settings = array(
            'title'       => 'Passes Database Settings', 
            'slug'        => 'passes_db_settings', 
            'description' => 'If database settings are ok it should be set to 1 do not change this manually', 
            'type'        => 'hidden', 
            'default'     => '0', 
            'value'       => '0', 
            'options'     => '', 
            'section'     => 'opensim_db_settings',
            'validation'  => '',
            'class'       => '', 
            'is_gui'      => '0', 
            'module_slug' => 'opensim', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $passes_db_settings = Settings\Model\Setting::create($passes_db_settings);

        $database_driver = array(
            'title'       => 'Database Driver', 
            'slug'        => 'database_driver', 
            'description' => 'The database type used by robust&#46;', 
            'type'        => 'select', 
            'default'     => 'mysql', 
            'value'       => 'mysql', 
            'options'     => '{"mysql":"Mysql"}', 
            'section'     => 'opensim_db_settings',
            'validation'  => '',
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'opensim', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $database_driver = Settings\Model\Setting::create($database_driver);

        $database_host = array(
            'title'       => 'Database Host', 
            'slug'        => 'database_host', 
            'description' => 'Robust database hostname&#46;', 
            'type'        => 'text', 
            'default'     => '127.0.0.1', 
            'value'       => '127.0.0.1', 
            'options'     => '', 
            'section'     => 'opensim_db_settings',
            'validation'  => 'required',
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'opensim', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $database_host = Settings\Model\Setting::create($database_host);

        $database_name = array(
            'title'       => 'Database Name', 
            'slug'        => 'database_name', 
            'description' => 'Robust database name&#46; If sqlite database is used enter here the path to the database&#46;', 
            'type'        => 'text', 
            'default'     => 'opensim', 
            'value'       => 'opensim', 
            'options'     => '', 
            'section'     => 'opensim_db_settings',
            'validation'  => 'required|alpha_dash',
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'opensim', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $database_name = Settings\Model\Setting::create($database_name);

         $database_user = array(
            'title'       => 'Database User', 
            'slug'        => 'database_user', 
            'description' => 'The username to connect to robust database&#46;', 
            'type'        => 'text', 
            'default'     => 'opensim', 
            'value'       => 'opensim', 
            'options'     => '', 
            'section'     => 'opensim_db_settings',
            'validation'  => 'required',
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'opensim', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $database_user = Settings\Model\Setting::create($database_user);

        $database_password = array(
            'title'       => 'Database Password', 
            'slug'        => 'database_password', 
            'description' => 'Password to connect to robust database&#46;', 
            'type'        => 'password', 
            'default'     => '', 
            'value'       => '', 
            'options'     => '', 
            'section'     => 'opensim_db_settings',
            'validation'  => '',
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'opensim', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $database_password = Settings\Model\Setting::create($database_password);

        $database_table_prefix = array(
            'title'       => 'Table Prefix', 
            'slug'        => 'database_table_prefix', 
            'description' => 'The robust database table prefix&#46 Leave empty if no prefix is used&#46;', 
            'type'        => 'text', 
            'default'     => '', 
            'value'       => '', 
            'options'     => '', 
            'section'     => 'opensim_db_settings',
            'validation'  => 'alpha_dash',
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'opensim', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $database_table_prefix = Settings\Model\Setting::create($database_table_prefix);

        $database_port = array(
            'title'       => 'Database Port', 
            'slug'        => 'database_port', 
            'description' => 'The robust database port&#46;', 
            'type'        => 'text', 
            'default'     => '3306', 
            'value'       => '3306', 
            'options'     => '', 
            'section'     => 'opensim_db_settings',
            'validation'  => '',
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'opensim', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $database_port = Settings\Model\Setting::create($database_port);

        $opensim_home_location = array(
            'title'       => 'Home Location', 
            'slug'        => 'opensim_home_location', 
            'description' => 'Set home location for new accounts', 
            'type'        => 'select', 
            'default'     => '', 
            'value'       => '', 
            'options'     => 'func:opensim\\get_regions', 
            'section'     => 'opensim_settings',
            'validation'  => '',
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'opensim', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $opensim_home_location = Settings\Model\Setting::create($opensim_home_location);

        $opensim_position_location = array(
            'title'       => 'Home Position', 
            'slug'        => 'opensim_position_location', 
            'description' => 'Set start position for the avatar&#46; It should be a vector &ltx,y,z&gt where the avatar will land&#46;', 
            'type'        => 'text', 
            'default'     => '<128,128,50>', 
            'value'       => '<128,128,50>', 
            'options'     => '', 
            'section'     => 'opensim_settings', 
            'validation'  => 'required|vector', 
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'opensim', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $opensim_position_location = Settings\Model\Setting::create($opensim_position_location);

        $opensim_remove_old_regions = array(
            'title'       => 'Remove Old Regions', 
            'slug'        => 'opensim_remove_old_regions', 
            'description' => 'Remove regions last seen with more days then specified&#46;', 
            'type'        => 'select', 
            'default'     => '0', 
            'value'       => '0', 
            'options'     => '{"0":"Disabled","1":"1 Day","7":"7 Days","15":"15 Days","30":"30 Days"}', 
            'section'     => 'opensim_settings', 
            'validation'  => '', 
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'opensim', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $opensim_remove_old_regions = Settings\Model\Setting::create($opensim_remove_old_regions);
    }

    public function uninstall()
    {
        //
        // REMOVE OPENSIM SETTINGS
        // 
        $settings = Settings\Model\Setting::where_module_slug('opensim')->get();
        
        if(isset($settings) and !empty($settings))
        {
            foreach ($settings as $setting) 
            {
                $setting->delete();
            }
        }
        
    }

    public function __destruct()
    {
        Bundle::disable('settings');
    }
}