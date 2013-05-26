<?php

class Auth_Frontend_Auth_Controller extends Public_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_login()
    {
        $this->data['meta_title'] = 'Login';
        return $this->theme->render('auth::frontend.login', $this->data);
    }

    public function post_login()
    {
        $messages = array(
            
            'check_login'       => Lang::line('auth::lang.Invalid credentials')->get(APP_LANG),
            'check_user_status' => Lang::line('auth::lang.This account is inactive. Please contact support')->get(APP_LANG),
        );

        Validator::register('check_login', function($attribute, $value, $parameters)
        {
            $credentials = array(
                'username'   => Input::get('username'),
                'password'   => Input::get('password'),
                'remember'   => Input::get('remember'),
            );

            return Auth::attempt($credentials) ? true : false;
        });

        Validator::register('check_user_status', function($attribute, $value, $parameters)
        {
            if(isset(Auth::user()->status) and Auth::user()->status != 'active')
            {
                Auth::logout();
                return false;
            }
            
            return true;
        });

        $rules = array(
            'username' => 'required',
            'password' => 'required|check_login|check_user_status',
        );

        $validation = Validator::make(Input::all(), $rules, $messages);

        if ($validation->fails())
        {
            return Redirect::to('login')->with_input()->with_errors($validation);
        }
        else
        {
            if ( Session::has('last_page') )
            {
                $url = Session::get('last_page');
                Session::forget('last_page');
                return Redirect::to($url);
            }
            else
            {
                return Redirect::to('/');
            }
        }

        return $this->theme->render('auth::frontend.login', $this->data);
    }

    public function get_logout()
    {
        Auth::logout();
        return Redirect::home();
    }
}