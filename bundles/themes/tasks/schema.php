<?php

class Themes_Schema_Task {

    public function __construct()
    {
        Bundle::register('themes');
        Bundle::start('themes');

        Bundle::register('settings');
        Bundle::start('settings');

        Bundle::register('modules');
        Bundle::start('modules');
    }

    public function install()
    {   
        $module = Modules\Model\Module::where_slug('themes')->first();

        $backend = array(

            'name'           => 'Base Backend Theme',
            'slug'           => 'base_bke',
            'description'    => 'Basic backend application theme',
            'layout'         => 'admin',
            'layout_css'     => '',
            'layout_js'      => '',
            'layout_default' => File::get(dirname(__FILE__).DS.'data'.DS.'backend_layout.html'),
            'layout_content' => File::get(dirname(__FILE__).DS.'data'.DS.'backend_layout.html'),
            'author'         => 'Jefferson Costella',
            'version'        => '1.0.0',
            'url'            => '#',
            'layer'          => 'backend',
            'is_default'     => '1',
            'is_core'        => '1',
            'enabled'        => '1',
        );
        $backend = Themes\Model\Theme::create($backend);

        $frontend = array(

            'name'           => 'Base Frontend Theme',
            'slug'           => 'base_fte',
            'description'    => 'Basic frontend application theme',
            'layout'         => 'application',
            'layout_css'     => '',
            'layout_js'      => '',
            'layout_default' => File::get(dirname(__FILE__).DS.'data'.DS.'frontend_layout.html'),
            'layout_content' => File::get(dirname(__FILE__).DS.'data'.DS.'frontend_layout.html'),
            'author'         => 'Jefferson Costella',
            'version'        => '1.0.0',
            'url'            => '#',
            'layer'          => 'frontend',
            'is_default'     => '1',
            'is_core'        => '1',
            'enabled'        => '1',
        );
        $frontend = Themes\Model\Theme::create($frontend);

        $frontend_theme = array(
            'title'       => 'Base Frontend Theme', 
            'slug'        => 'frontend_theme', 
            'description' => 'Basic application frontend theme', 
            'type'        => 'hidden', 
            'default'     => 'base_fte', 
            'value'       => 'base_fte', 
            'options'     => '', 
            'class'       => '',
            'is_gui'      => '1', 
            'module_slug' => 'themes', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $frontend_theme = Settings\Model\Setting::create($frontend_theme);

        $frontend_layout = array(
            'title'       => 'Frontend Layout', 
            'slug'        => 'frontend_layout', 
            'description' => 'Application frontend layout', 
            'type'        => 'hidden', 
            'default'     => 'application', 
            'value'       => 'application', 
            'options'     => '',
            'section'     => '',
            'validation'  => '',
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'themes', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $frontend_layout = Settings\Model\Setting::create($frontend_layout);

        $backend_layout = array(
            'title'       => 'Backend Layout', 
            'slug'        => 'backend_layout', 
            'description' => 'Administration Layout', 
            'type'        => 'hidden', 
            'default'     => 'admin', 
            'value'       => 'admin', 
            'options'     => '',
            'section'     => '',
            'validation'  => '', 
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'themes', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $backend_layout = Settings\Model\Setting::create($backend_layout);

        $backend_theme = array(
            'title'       => 'Backend base theme', 
            'slug'        => 'backend_theme', 
            'description' => 'Application backend basic theme', 
            'type'        => 'hidden', 
            'default'     => 'base_bke', 
            'value'       => 'base_bke', 
            'options'     => '',
            'section'     => '',
            'validation'  => '', 
            'class'       => '', 
            'is_gui'      => '1', 
            'module_slug' => 'themes', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $backend_theme = Settings\Model\Setting::create($backend_theme);
        
    }

    public function uninstall()
    {
        //
        // REMOVE THEME
        // 
        $bke = Themes\Model\Theme::where_slug('base_bke')->first();
        if(isset($bke) and !empty($bke))
        {
            $bke->delete();
        }

        $fte = Themes\Model\Theme::where_slug('base_fte')->first();
        if(isset($fte) and !empty($fte))
        {
            $fte->delete();
        }

        //
        // REMOVE THEME SETTINGS
        // 
        $settings = Settings\Model\Setting::where_module_slug('themes')->get();
        
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
        Bundle::disable('themes');
        Bundle::disable('settings');
        Bundle::disable('modules');
    }
}