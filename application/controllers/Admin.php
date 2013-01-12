<?php

class Admin_Controller extends Base_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->filter('before', 'auth');

        $this->filter('before', 'mwi.admin_controller_start', array($this));

        // $this->filter('before', 'check_rule:admin');

        $this->filter('before', 'csrf')->on('post');

        $this->data['installed_and_active_modules'] = Modules\Model\Module::where('enabled', '=', 1)
                                                              ->get(array('name','menu'));

        if(Bundle::exists('themes'))
        {
            $this->theme->set_theme(Config::get('settings::core.backend_theme'), 'backend');
            $this->theme->set_layout(Config::get('settings::core.backend_layout'), 'backend');
            $this->theme->_theme_data = $this->data;
        }
    }
}