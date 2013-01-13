<?php

class Opensim_Backend_Opensim_Controller extends Admin_Controller {
    
    public function __construct()
    {
        parent::__construct();

        $this->data['bar'] = array(
            'title'       => Lang::line('opensim::lang.Opensim')->get(ADM_LANG),
            'url'         => URL::base().'/'.ADM_URI.'/opensim',
            'description' => Lang::line('opensim::lang.Provide a webinterface to manage opensim virtual worlds')->get(ADM_LANG),
        );

        $this->data['section_bar'] = array(
            Lang::line('opensim::lang.Settings')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/opensim/settings',
            Lang::line('opensim::lang.Database Settings')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/opensim/settings/database',
            Lang::line('opensim::lang.Regions')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/opensim/regions',
        );
    }

    public function get_index()
    {
        $db_is_ready = Config::get('settings::core.passes_db_settings');

        if( !(bool)$db_is_ready )
        {
            Session::flash('message_type', 'error');
            Session::flash('message', Lang::line('opensim::lang.Your opensim database needs to be configured!')->get(ADM_LANG));
            return Redirect::to(ADM_URI.'/opensim')->with($this->data);
        }

        $this->data['settings'] = Settings\Model\Setting::where_section('opensim_settings')->get();
        $this->data['section_bar_active'] = Lang::line('opensim::lang.Settings')->get(ADM_LANG);
        return $this->theme->render('opensim::backend.opensim.index', $this->data);
    }
}