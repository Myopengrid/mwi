<?php

class Splashscreen_Backend_Splashscreen_Controller extends Admin_Controller {
    
    public function __construct()
    {
        parent::__construct();

        $this->data['bar'] = array(
            'title'       => Lang::line('splashscreen::lang.Splash Screen')->get(ADM_LANG),
            'url'         => URL::base() .'/'.ADM_URI.'/splashscreen',
            'description' => Lang::line('splashscreen::lang.Allows administrators to update settings for the viewer splash screen')->get(ADM_LANG),
            'buttons'     => array(),
        );

        $this->data['section_bar'] = array(
            Lang::line('splashscreen::lang.Settings')->get(ADM_LANG)    => URL::base().'/'.ADM_URI.'/splashscreen',
            Lang::line('splashscreen::lang.Logo And Backgrounds')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/splashscreen/images_backgrounds',
            Lang::line('splashscreen::lang.Flash News')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/splashscreen/flash_news',
            Lang::line('splashscreen::lang.New Flash News')->get(ADM_LANG)           => URL::base().'/'.ADM_URI.'/splashscreen/flash_news/new',
        );
    }

    public function get_index()
    {
        $this->data['section_bar_active'] = Lang::line('splashscreen::lang.Settings')->get(ADM_LANG);
        
        $this->data['settings'] = Settings\Model\Setting::where_module_slug('splashscreen')->order_by('order', 'asc')->get();

        // Get all sections from settings
        $sections = array();
        foreach($this->data['settings'] as $setting) 
        {
            if(isset($setting->section) and !empty($setting->section))
            {
                if(!isset($sections[$setting->section]))
                {
                    $sections[$setting->section] = $setting->section;
                }
            }
        }
        $this->data['sections'] = $sections;

        return $this->theme->render('splashscreen::backend.splashscreen.index', $this->data);
    }

    public function put_update()
    {
        $post_data = Input::get('order');
        
        if(isset($post_data) and !empty($post_data))
        {
            $order_items = explode(',', $post_data);
            foreach ($order_items as $order_position => $slug)
            {
                $affected = Settings\Model\Setting::where_slug($slug)
                               ->update(array('order' => $order_position));
            }
            return;
        }


        $settings = Input::all();
        
        foreach ($settings as $slug => $value)
        {
            // Update runtime configurations.
            $setting = Config::get('settings::core.'.$slug);
            if($setting != null)
            {
                Config::set('settings::core.'.$slug, $value);
            }
            // Update database configurations
            $affected = Settings\Model\Setting::where_slug($slug)
                                                ->update(array('value' => $value));
        }
        
        $this->data['message'] = Lang::line('splashscreen::lang.Settings were successfully updated')->get(ADM_LANG);
        $this->data['message_type'] = 'success';

        // redirect back to the module 
        return Redirect::back()->with($this->data);
    }
}