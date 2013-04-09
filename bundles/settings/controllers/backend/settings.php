<?php

class Settings_Backend_Settings_Controller extends Admin_Controller {

    function __construct()
    {
        parent::__construct();

        $this->data['bar'] = array(
            'title'       => Lang::line('settings::lang.Settings')->get(ADM_LANG),
            'url'         => URL::base() .'/'.ADM_URI.'/settings',
            'description' => Lang::line('settings::lang.Allows administrators to update settings such as site name, e-mail messages, etc')->get(ADM_LANG),
            'buttons'     => array(),
        );

        $this->data['section_bar'] = array(
            Lang::line('settings::lang.Settings')->get(ADM_LANG)    => URL::base().'/'.ADM_URI.'/settings',
            Lang::line('settings::lang.App Keywords')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/settings/keywords',
        );
    }

    public function get_index()
    {
        $this->data['section_bar_active'] = Lang::line('settings::lang.Settings')->get(ADM_LANG);

        $this->data['settings'] = Settings\Model\Setting::where('module_slug', '=', 'settings')
                                    ->order_by('order', 'asc')
                                    ->get();

        return $this->theme->render('settings::backend.index', $this->data);
    }

    public function get_keywords()
    {
        return 'hey';
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

        // If we are saving settings for other module
        // other then settings module this value will
        // not be sent
        $adm_uri = Input::get('administration_uri');
        if(isset($adm_uri) and !empty($adm_uri))
        {
            Session::put('ADM_URI', Input::get('administration_uri'));
        }
        else
        {
            Session::put('ADM_URI', ADM_URI);
        }
        
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
        
        $this->data['message'] = Lang::line('settings::lang.Settings were successfully updated')->get(ADM_LANG);
        $this->data['message_type'] = 'success';

        // if we are changing the administration
        // uri it must be from settings module
        // redirect there with the new uri
        $redirect = Input::get('administration_uri');
        if(isset($redirect) and !empty($redirect))
        {
            return Redirect::to(URL::base().'/'.Session::get('ADM_URI').'/settings')->with($this->data);
        }
        // we are not changing the administration
        // uri, it must be from another module
        // redirect back to the module 
        return Redirect::back()->with($this->data);
    }

    public function put_set_language()
    {
        Settings\Config::set('settings::core.backend_language', Input::get('lang'), true);
        return Redirect::to(Input::get('redirect_to'));
    }
}