<?php

class Public_Controller extends Base_Controller {

    function __construct()
    {
        parent::__construct();

        if(Bundle::exists('themes'))
        {
            $this->theme->set_theme(Config::get('settings::core.frontend_theme', 'frontend'));
            $this->theme->set_layout(Config::get('settings::core.frontend_layout'));
        }

        $maintenance = Config::get('settings::core.site_maintenance');
        
        if($maintenance == 'yes')
        {
            $this->data['site_name'] = Config::get('settings::core.site_name');
            echo $this->theme->render('admin::frontend.maintenance', $this->data);
            die();   
        }

        $this->filter('before', 'mwi.public_controller_start', array($this));
        
        $this->filter('before', 'csrf')->on('post');
        
        
    }
}