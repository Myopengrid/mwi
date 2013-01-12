<?php

use Install\Installer;

class Install_Frontend_Ajax_Controller extends Base_Controller {
    
    public function __construct()
    {
        parent::__construct();
        \Config::set('error.detail', false);
    }
    
    public function post_confirm_database()
    {
        $rules = array(
            'driver' => 'required|db_type|test_db_connection',
        );

        $validation = Install\Validator::make(Input::all(), $rules)->speaks(Session::get('adm_lang'));

        if($validation->passes())
        {
            $response['success'] = 'true';
            $response['message'] = __('install::lang.Database validation passed successfully')->get(Session::get('adm_lang'));
        }
        else
        {
            $response['success'] = 'false';
            $response['message'] = $validation->errors->first();
        }
        
        return Response::make(json_encode($response))
                            ->header('Cache-Control', 'no-cache, must-revalidate')
                            ->header('Expires', 'Mon, 01 Jan 2000 00:00:00 GMT')
                            ->header('Content-Type', 'application/json');
    }

    public function post_change_lang()
    {
        $lang = Input::get('lang');
        if(isset($lang) and !empty($lang))
        {
            Session::put('adm_lang', $lang);
        }
        
        return '';
    }
}