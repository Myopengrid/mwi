<?php

class Private_Controller extends Base_Controller {

    function __construct()
    {
        parent::__construct();
        
        if(Bundle::exists('themes'))
        {
            $this->theme->set_theme(Config::get('settings::core.frontend_theme'));
            $this->theme->set_layout(Config::get('settings::core.frontend_layout'));
        }

        $maintenance = Config::get('settings::core.site_maintenance');
        if($maintenance == 'yes')
        {
            echo View::make('admin::frontend.maintenance');
            die();   
        }

        $this->filter('before', 'mwi.private_controller_start', array($this));

        $this->filter('before', 'auth');
        
        $this->filter('before', 'csrf')->on('post');
    }
}