<?php

class Base_Controller extends Controller {

    public $restful = true;
    
    public $theme;
    
    public $data = array();
    
    public $validation_rules = array();
    
    public $navigation;
    
    public function __construct()
    {
        parent::__construct();

        $this->data['site_name']        = Config::get('settings::core.site_name');
        $this->data['meta_title']       = '';
        $this->data['meta_description'] = '';
        $this->data['meta_keywords']    = '';

        if(Bundle::exists('themes'))
        {
            $this->theme = IoC::resolve('Theme');
        }
        // @TODO add fallback if themes module for
        // some reason is not installed/enabled

        $this->filter('before', 'mwi.base_controller_start', array($this));
    }

    /**
     * Catch-all method for requests that can't be matched.
     *
     * @param  string    $method
     * @param  array     $parameters
     * @return Response
     */
    public function __call($method, $parameters)
    {
        return Response::error('404');
    }

}