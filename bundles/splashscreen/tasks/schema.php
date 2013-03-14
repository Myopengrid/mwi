<?php

class Splashscreen_Schema_Task {

    public function __construct()
    {
        Bundle::register('settings');
        Bundle::start('settings');

        Bundle::register('modules');
        Bundle::start('modules');

        Bundle::register('splashscreen');
        Bundle::start('splashscreen');
    }

    public function install()
    {
        $module = Modules\Model\Module::where_slug('splashscreen')->first();

        $splashscreen_show_regions_list = array(
            'title'       => 'Show Regions List', 
            'slug'        => 'splashscreen_show_regions_list', 
            'description' => 'Show Regions list block, if no regions are attachet to the grid the block will not show', 
            'type'        => 'select', 
            'default'     => 'yes', 
            'value'       => 'yes', 
            'options'     => '{"yes":"Yes","no":"No"}', 
            'class'       => 'settings', 
            'section'     => 'settings',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_show_regions_list = Settings\Model\Setting::create($splashscreen_show_regions_list);

        $splashscreen_show_flash_news = array(
            'title'       => 'Show Flash News', 
            'slug'        => 'splashscreen_show_flash_news', 
            'description' => 'Show flash news block', 
            'type'        => 'select', 
            'default'     => 'yes', 
            'value'       => 'yes', 
            'options'     => '{"yes":"Yes","no":"No"}', 
            'class'       => 'settings', 
            'section'     => 'settings',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_show_flash_news = Settings\Model\Setting::create($splashscreen_show_flash_news);

        $splashscreen_slug = array(
            'title'       => 'Splash Screen Slug', 
            'slug'        => 'splashscreen_slug', 
            'description' => 'The URI of the splash screen', 
            'type'        => 'text', 
            'default'     => 'splash_screen', 
            'value'       => 'splash_screen', 
            'options'     => '', 
            'class'       => 'settings', 
            'section'     => 'settings',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_slug = Settings\Model\Setting::create($splashscreen_slug);

        $splashscreen_flash_news_handler = array(
            'title'       => 'Flash News Handler', 
            'slug'        => 'splashscreen_flash_news_handler', 
            'description' => 'The handler for flash news', 
            'type'        => 'text', 
            'default'     => 'flash_news', 
            'value'       => 'flash_news', 
            'options'     => '', 
            'class'       => 'settings', 
            'section'     => 'settings',
            'validation'  => 'required', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_flash_news_handler = Settings\Model\Setting::create($splashscreen_flash_news_handler);

        $splashscreen_flash_news_link = array(
            'title'       => 'Flash News Link Target', 
            'slug'        => 'splashscreen_flash_news_link', 
            'description' => 'Set the target of the flash news link', 
            'type'        => 'select', 
            'default'     => '_self', 
            'value'       => '_self', 
            'options'     => '{"_self":"Same Window","_blank":"New Window"}', 
            'class'       => 'settings', 
            'section'     => 'settings',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_flash_news_link = Settings\Model\Setting::create($splashscreen_flash_news_link);

        $splashscreen_effect = array(
            'title'       => 'Splash Screen Effect', 
            'slug'        => 'splashscreen_effect', 
            'description' => 'The effect to be used on the splash screen', 
            'type'        => 'select', 
            'default'     => 'time_of_day', 
            'value'       => 'time_of_day', 
            'options'     => '{"loop_background":"Loop Background","time_of_day":"Time of Day"}', 
            'class'       => 'settings', 
            'section'     => 'settings',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_effect = Settings\Model\Setting::create($splashscreen_effect);

        $splashscreen_effect_time = array(
            'title'       => 'Time of Day Delay', 
            'slug'        => 'splashscreen_effect_time', 
            'description' => 'When using time of day effect, you can set the delay between images (1000 = 1 sec)', 
            'type'        => 'text', 
            'default'     => '15000', 
            'value'       => '15000', 
            'options'     => '', 
            'class'       => 'settings', 
            'section'     => 'settings',
            'validation'  => 'required|integer', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_effect_time = Settings\Model\Setting::create($splashscreen_effect_time);

        $splashscreen_show_grid_status_block = array(
            'title'       => 'Show grid status block', 
            'slug'        => 'splashscreen_show_grid_status_block', 
            'description' => 'Show/Hide grid status box', 
            'type'        => 'select', 
            'default'     => 'yes', 
            'value'       => 'yes', 
            'options'     => '{"yes":"Yes","no":"No"}', 
            'class'       => 'grid_status_block', 
            'section'     => 'grid_status_block',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_show_grid_status_block = Settings\Model\Setting::create($splashscreen_show_grid_status_block);
        
        $splashscreen_grid_status = array(
            'title'       => 'Grid Online', 
            'slug'        => 'splashscreen_grid_status', 
            'description' => 'Set the grid info box status', 
            'type'        => 'select', 
            'default'     => 'Online', 
            'value'       => 'Online', 
            'options'     => '{"online":"Online","offline":"Offline"}', 
            'class'       => 'grid_status_block', 
            'section'     => 'grid_status_block',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_grid_status = Settings\Model\Setting::create($splashscreen_grid_status);

        $splashscreen_show_total_users = array(
            'title'       => 'Show Total Users', 
            'slug'        => 'splashscreen_show_total_users', 
            'description' => 'Show the total users registered', 
            'type'        => 'select', 
            'default'     => 'yes', 
            'value'       => 'yes', 
            'options'     => '{"yes":"Yes","no":"No"}', 
            'class'       => 'grid_status_block', 
            'section'     => 'grid_status_block',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_show_total_users = Settings\Model\Setting::create($splashscreen_show_total_users);

        $splashscreen_show_total_regions = array(
            'title'       => 'Show Total Regions', 
            'slug'        => 'splashscreen_show_total_regions', 
            'description' => 'Show the total regions registered to the grid', 
            'type'        => 'select', 
            'default'     => 'yes', 
            'value'       => 'yes', 
            'options'     => '{"yes":"Yes","no":"No"}', 
            'class'       => 'grid_status_block', 
            'section'     => 'grid_status_block',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_show_total_regions = Settings\Model\Setting::create($splashscreen_show_total_regions);

        $splashscreen_show_active_users = array(
            'title'       => 'Show Total Active Users', 
            'slug'        => 'splashscreen_show_active_users', 
            'description' => 'Show the count of all active users in the last 30 days', 
            'type'        => 'select', 
            'default'     => 'yes', 
            'value'       => 'yes', 
            'options'     => '{"yes":"Yes","no":"No"}', 
            'class'       => 'grid_status_block', 
            'section'     => 'grid_status_block',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_show_active_users = Settings\Model\Setting::create($splashscreen_show_active_users);

        $splashscreen_show_online_users = array(
            'title'       => 'Show Total Online Users', 
            'slug'        => 'splashscreen_show_online_users', 
            'description' => 'Show the count of all users currently online', 
            'type'        => 'select', 
            'default'     => 'yes', 
            'value'       => 'yes', 
            'options'     => '{"yes":"Yes","no":"No"}', 
            'class'       => 'grid_status_block', 
            'section'     => 'grid_status_block',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_show_online_users = Settings\Model\Setting::create($splashscreen_show_online_users);

        $splashscreen_show_top_left_block = array(
            'title'       => 'Show Left Block', 
            'slug'        => 'splashscreen_show_top_left_block', 
            'description' => 'Show the top left message block', 
            'type'        => 'select', 
            'default'     => 'yes', 
            'value'       => 'yes', 
            'options'     => '{"yes":"Yes","no":"No"}', 
            'class'       => 'message_block', 
            'section'     => 'message_block',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_show_top_left_block = Settings\Model\Setting::create($splashscreen_show_top_left_block);

        $splashscreen_top_left_block_title = array(
            'title'       => 'Block Title', 
            'slug'        => 'splashscreen_top_left_block_title', 
            'description' => 'Show the top left message block', 
            'type'        => 'text', 
            'default'     => '', 
            'value'       => 'Lorem ipsum dolor sit amet!!', 
            'options'     => '', 
            'class'       => 'message_block', 
            'section'     => 'message_block',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_top_left_block_title = Settings\Model\Setting::create($splashscreen_top_left_block_title);

        $splashscreen_top_left_block_body = array(
            'title'       => 'Block Text', 
            'slug'        => 'splashscreen_top_left_block_body', 
            'description' => 'Show the top left message block', 
            'type'        => 'textarea', 
            'default'     => '', 
            'value'       => 'Lorem ipsum dolor sit amet! <br /> Lorem ipsum dolor sit amet! <br /> Lorem ipsum dolor sit amet!', 
            'options'     => '', 
            'class'       => 'message_block', 
            'section'     => 'message_block',
            'validation'  => 'required', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_top_left_block_body = Settings\Model\Setting::create($splashscreen_top_left_block_body);



        $splashscreen_show_grid_status_message_block = array(
            'title'       => 'Show Message Block', 
            'slug'        => 'splashscreen_show_grid_status_message_block', 
            'description' => 'Show the gris status message block', 
            'type'        => 'select', 
            'default'     => 'yes', 
            'value'       => 'yes', 
            'options'     => '{"yes":"Yes","no":"No"}', 
            'class'       => 'status_message_block', 
            'section'     => 'status_message_block',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_show_grid_status_message_block = Settings\Model\Setting::create($splashscreen_show_grid_status_message_block);

        $splashscreen_grid_status_message_block_color = array(
            'title'       => 'Block Color', 
            'slug'        => 'splashscreen_grid_status_message_block_color', 
            'description' => 'Set the block color', 
            'type'        => 'select', 
            'default'     => 'green', 
            'value'       => 'green', 
            'options'     => '{"white":"White","green":"Green","yellow":"Yellow","red":"Red"}', 
            'class'       => 'status_message_block', 
            'section'     => 'status_message_block',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_grid_status_message_block_color = Settings\Model\Setting::create($splashscreen_grid_status_message_block_color);

        $splashscreen_grid_status_message_block_title = array(
            'title'       => 'Block Title', 
            'slug'        => 'splashscreen_grid_status_message_block_title', 
            'description' => 'Show the grid status message block', 
            'type'        => 'text', 
            'default'     => '', 
            'value'       => 'Lorem ipsum dolor sit amet!', 
            'options'     => '', 
            'class'       => 'status_message_block', 
            'section'     => 'status_message_block',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_grid_status_message_block_title = Settings\Model\Setting::create($splashscreen_grid_status_message_block_title);

        $splashscreen_grid_status_message_block_body = array(
            'title'       => 'Lorem ipsum dolor sit amet!', 
            'slug'        => 'splashscreen_grid_status_message_block_body', 
            'description' => 'Grid status message block text', 
            'type'        => 'textarea', 
            'default'     => '', 
            'value'       => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 
            'options'     => '', 
            'class'       => 'status_message_block', 
            'section'     => 'status_message_block',
            'validation'  => 'required', 
            'is_gui'      => '1', 
            'module_slug' => 'splashscreen', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $splashscreen_grid_status_message_block_body = Settings\Model\Setting::create($splashscreen_grid_status_message_block_body);

        $welcome_flash_news = array(
            'slug'       => 'welcome',
            'title'      => 'Welcome',
            'message'    => 'Welcome! This is a flash news message!',
            'is_enabled' => 1,
        );
        $welcome_flash_news = \Splashscreen\Model\News::create($welcome_flash_news);
    }

    public function uninstall()
    {
        //
        // REMOVE SPLASHSCREEN
        // 
        $settings = Settings\Model\Setting::where_module_slug('splashscreen')->get();
        
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
        Bundle::disable('splashscreen');
    }
}