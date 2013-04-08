<?php

class Settings_Schema_Task {

    public function __construct()
    {
        Bundle::register('settings');
        Bundle::start('settings');

        Bundle::register('modules');
        Bundle::start('modules');
    }

    public function install()
    {
        $module = Modules\Model\Module::where_slug('settings')->first();
        
        $administration_uri = array(
            'title'       => 'Administration URI', 
            'slug'        => 'administration_uri', 
            'description' => 'Set the application administration URI', 
            'type'        => 'text', 
            'default'     => 'admin', 
            'value'       => 'admin', 
            'options'     => '', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'settings', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $administration_uri = Settings\Model\Setting::create($administration_uri);

        $site_name = array(
            'title'       => 'Site Name', 
            'slug'        => 'site_name', 
            'description' => 'The site name', 
            'type'        => 'text', 
            'default'     => 'Unknown Grid', 
            'value'       => 'Unknown Grid', 
            'options'     => '', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'settings', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $site_name = Settings\Model\Setting::create($site_name);

        $backend_language = array(
            'title'       => 'Backend Language', 
            'slug'        => 'backend_language', 
            'description' => 'Set the application administration language', 
            'type'        => 'select', 
            'default'     => 'en', 
            'value'       => 'en', 
            'options'     => 'func:settings\get_available_languages', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'settings', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $backend_language = Settings\Model\Setting::create($backend_language);

        $frontend_language = array(
            'title'       => 'Frontend Language', 
            'slug'        => 'frontend_language', 
            'description' => 'Frontend Application Language', 
            'type'        => 'hidden', 
            'default'     => 'en', 
            'value'       => 'en', 
            'options'     => 'func:settings\get_available_languages', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'settings', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $frontend_language = Settings\Model\Setting::create($frontend_language);

        $available_languages = array(
            'title'       => 'Available Languages', 
            'slug'        => 'available_languages', 
            'description' => 'List of available languages for the application', 
            'type'        => 'hidden', 
            'default'     => '{"en":"English","br":"Português do Brasil","fr":"Français"}',
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'settings', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $available_languages = Settings\Model\Setting::create($available_languages);

        $records_per_page = array(
            'title'       => 'Records Per Page', 
            'slug'        => 'records_per_page', 
            'description' => 'How many records should we show per page in the admin section?', 
            'type'        => 'select', 
            'default'     => '10', 
            'value'       => '10', 
            'options'     => '{"10":"10","25":"25","50":"50","100":"100"}', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'settings', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $records_per_page = Settings\Model\Setting::create($records_per_page);

        $site_maintenance = array(
            'title'       => 'Maintenance Mode', 
            'slug'        => 'site_maintenance', 
            'description' => 'Lock application in maintenance mode', 
            'type'        => 'select', 
            'default'     => 'no', 
            'value'       => 'no', 
            'options'     => '{"yes":"Yes","no":"No"}', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'settings', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $site_maintenance = Settings\Model\Setting::create($site_maintenance);

        $settings_time_zone = array(
            'title'       => 'Time Zone', 
            'slug'        => 'settings_time_zone', 
            'description' => 'Time zone to be used by the system', 
            'type'        => 'select', 
            'default'     => 'America/Sao_Paulo', 
            'value'       => 'America/Sao_Paulo', 
            'options'     => 'func:\settings\get_available_time_zones', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'settings', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        
        $settings_time_zone = Settings\Model\Setting::create($settings_time_zone);

        $settings_date_format = array(
            'title'       => 'Date Format', 
            'slug'        => 'settings_date_format', 
            'description' => 'How should dates be displayed across the website and administration area? Please refer to: <a href="http://php&#46;net/manual/en/function&#46;date&#46;php" target="_blank">date format</a> from PHP - OR - Using the format of <a href="http://php&#46;net/manual/en/function&#46;strftime&#46;php" target="_blank">strings formatted as date</a> from PHP&#46;', 
            'type'        => 'text', 
            'default'     => 'D d F h:i A', 
            'value'       => 'D d F h:i A', 
            'options'     => '', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'settings', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $settings_date_format = Settings\Model\Setting::create($settings_date_format);

        $application_keywords = array(
            'title'       => 'Application Keywords', 
            'slug'        => 'application_keywords', 
            'description' => 'Set the application keywords', 
            'type'        => 'hidden', 
            'default'     => 'World,Avatar,Virtual,Opensim,Grid', 
            'value'       => 'World,Avatar,Virtual,Opensim,Grid', 
            'options'     => '', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'settings', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $application_keywords = Settings\Model\Setting::create($application_keywords);
    }

    public function uninstall()
    {
        //
        // REMOVE SETTINGS
        // 
        $settings = Settings\Model\Setting::where_module_slug('settings')->get();
        
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
        Bundle::disable('modules');
    }
}