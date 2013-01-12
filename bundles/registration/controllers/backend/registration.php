<?php

class Registration_Backend_Registration_Controller extends Admin_Controller {
    

    public function __construct()
    {
        parent::__construct();
        $this->data['section_bar'] = array(
            Lang::line('registration::lang.Settings')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/registration',
        );

        $this->data['bar'] = array(
            'title' => Lang::line('registration::lang.Registration')->get(ADM_LANG),
            'url' => URL::base().'/'.ADM_URI.'/registration',
            'description' => Lang::line('registration::lang.Let users register to the website')->get(ADM_LANG),
        );
    }
    
    public function get_index()
    {
        $this->data['settings'] = Settings\Model\Setting::where('module_slug', '=', 'registration')
                                                            ->order_by('order', 'asc')
                                                            ->get();
        
        $this->data['section_bar_active'] = Lang::line('registration::lang.Settings')->get(ADM_LANG);
        
        return $this->theme->render('registration::backend.index', $this->data);
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
        
        $this->data['message'] = Lang::line('registration::lang.Registration settings were successfully updated')->get(ADM_LANG);
        $this->data['message_type'] = 'success';

        return Redirect::back()->with($this->data);

    }
}