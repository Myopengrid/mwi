<?php

class Admin_Backend_Admin_Controller extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_index()
    {
        $this->data['bar'] = array(
            'title'       => __('admin::lang.Admin')->get(ADM_LANG),
            'url'         => URL::base().'/'.ADM_URI,
            'description' => __('admin::lang.Provides the admin dashboard')->get(ADM_LANG),
        );

        return $this->theme->render('admin::backend.index', $this->data);
    }
}