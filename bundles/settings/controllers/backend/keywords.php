<?php

class Settings_Backend_Keywords_Controller extends Admin_Controller {

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
        $this->data['section_bar_active'] = Lang::line('settings::lang.App Keywords')->get(ADM_LANG);
        $this->data['keywords'] = Config::get('settings::core.application_keywords');
        return $this->theme->render('settings::backend.keywords.index', $this->data);
    }

    public function put_update()
    {
        $keywords = Input::get('application_keywords');
        Settings\Config::set('settings::core.application_keywords', $keywords, true);
        $this->data['message'] = __('settings::lang.Application keywords were successfully updated')->get(ADM_LANG);
        $this->data['message_type'] = 'success';
        return Redirect::back()->with($this->data);
    }

    public function get_autocomplete()
    {
        if(Input::get('csrf_token') != Session::token())
        {
            return '[]';
        }
        
        $word = Input::get('term');
        if(isset($word) and !empty($word))
        {
            $keywords = explode(',', Config::get('settings::core.application_keywords'));
            return array_search_string($keywords, $word, true);
        }
    }
}