<?php

class Splashscreen_Frontend_Splashscreen_Controller extends Public_Controller {
    
    public function get_index()
    {
        
        $opensim_db_is_ready = Config::get('settings::core.passes_db_settings');
        if( !(bool)$opensim_db_is_ready )
        {
            return Redirect::to('404');
        }

        $show_regions_list = Config::get('settings::core.splashscreen_show_regions_list');
        
        $this->data['regions'] = array();
        
        if($show_regions_list == 'yes')
        {
            $this->data['regions'] = Opensim\Model\Os\Region::order_by('last_seen', 'desc')->get();
        }

        $show_grid_status_block = Config::get('settings::core.splashscreen_show_grid_status_block');
        $this->data['grid_status_block'] = array();
        if($show_grid_status_block == 'yes')
        {
            $show_grid_status = Config::get('settings::core.splashscreen_grid_status');
            if($show_grid_status)
            {
                $this->data['grid_status_block']['grid_status'] = Config::get('settings::core.splashscreen_grid_status');

                $show_total_users = Config::get('settings::core.splashscreen_show_total_users');

                if($show_total_users == 'yes')
                {
                    $this->data['grid_status_block']['total_users'] = Opensim\Model\Os\UserAccount::count();
                }

                $show_total_regions = Config::get('settings::core.splashscreen_show_total_regions');
                if($show_total_regions == 'yes')
                {
                    $this->data['grid_status_block']['total_regions'] = Opensim\Model\Os\Region::count();
                }

                $show_active_users = Config::get('settings::core.splashscreen_show_active_users');
                if($show_active_users == 'yes')
                {
                    $this->data['grid_status_block']['active_users'] = DB::connection('opensim')->table('GridUser')
                        ->where('Login', '>=', strtotime("-30 days"))->count();//time()-2592000);
                }

                $show_users_online = Config::get('settings::core.splashscreen_show_online_users');
                if($show_users_online == 'yes')
                {
                    // Because users can get stuck 
                    // we will just count presences
                    // newer then 2 days ago
                    $this->data['grid_status_block']['users_online'] = \DB::connection('opensim')->table('Presence')
                    ->join('GridUser', 'GridUser.UserID', '=', 'Presence.UserID')
                    ->where('Presence.LastSeen', '>', strtotime("-2 days"))
                    ->where('GridUser.Online', '=', 'true')->count(); 
                }
            }
        }

        $show_status_block_message = Config::get('settings::core.splashscreen_show_grid_status_message_block');
        if($show_status_block_message == 'yes')
        {
            $this->data['status_message_block'] = array(
                'color' => Config::get('settings::core.splashscreen_grid_status_message_block_color'),
                'title' => Config::get('settings::core.splashscreen_grid_status_message_block_title'),
                'text'  => Config::get('settings::core.splashscreen_grid_status_message_block_body'),
            );
        }
        
        $show_left_block = Config::get('settings::core.splashscreen_show_top_left_block');
        if($show_left_block == 'yes')
        {
            $this->data['grid_status_block']['status_top_left_block'] = array(
                'title' => Config::get('settings::core.splashscreen_top_left_block_title'),
                'text'  => Config::get('settings::core.splashscreen_top_left_block_body'),
            );
        }

        //Get images for effect
        $this->data['effect'] = Config::get('settings::core.splashscreen_effect');
        $this->data['effect_delay'] = Config::get('settings::core.splashscreen_effect_time');
        
        // Find images for background effect
        if($this->data['effect'] == 'loop_background')
        {
            $background_images_path = path('public').'bundles/splashscreen/img/backgrounds/';
            $background_images = array();
            foreach (glob($background_images_path."*") as $filename)
            {
                $name = explode('/', $filename);
                $background_images[] = URL::base().'/bundles/splashscreen/img/backgrounds/'. $name[count($name)-1];
            }
            $this->data['images'] = $background_images;
        }
        if($this->data['effect'] == 'time_of_day')
        {
            $background_images_path = path('public').'bundles/splashscreen/img/day_time_bkgs/';
            $background_images = array();
            foreach (glob($background_images_path."*") as $filename)
            {
                $name = explode('/', $filename);
                $background_images[] = URL::base().'/bundles/splashscreen/img/day_time_bkgs/'. $name[count($name)-1];
            }
            $this->data['images'] = $background_images;
        }

        // Find logo image
        $logo_path = path('public').'bundles/splashscreen/img/logo/';
        $logo = '';
        foreach (glob($logo_path."logo.*") as $filename)
        {
            $name = explode('/', $filename);
            $logo = $name[count($name)-1];
        }
        $this->data['logo'] = $logo;

        $show_flash_news = Config::get('settings::core.splashscreen_show_flash_news');
        if($show_flash_news == 'yes')
        {
            // We delegate the determination of news to the news loader event
            // so that the developer is free to override the news.
            $news = Event::until('splashscreen.load_news');

            if ( !is_null($news))
            {
                $this->data['news_handler'] = $news['handler'];
                $this->data['news'] = $news;
            }
            else
            {
                $news_handler = Config::get('settings::core.splashscreen_flash_news_handler');
                $this->data['news_handler'] = URL::base().'/'.$news_handler.'/';
                $this->data['news'] = Splashscreen\Model\News::where_is_enabled(1)->order_by('created_at', 'desc')->take(4)->get();
            }

        }
        
        return View::make('splashscreen::frontend.splash_screen', $this->data);
    }
}