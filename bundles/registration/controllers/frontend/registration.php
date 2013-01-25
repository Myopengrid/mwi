<?php

class Registration_Frontend_Registration_Controller extends Public_Controller {
    
    public function get_index()
    {
        $disable_signup = Config::get('settings::core.registration_disabled');
        if($disable_signup == 'yes')
        {
            $this->data['message']      = __('registration::lang.Sign-up is temporarily disabled')->get(APP_LANG);
            $this->data['message_type'] = 'info';
            return Redirect::to('home')->with($this->data);
        }
        
        $this->data['meta_title'] = 'Sign Up';
        return $this->theme->render('registration::frontend.index', $this->data);
    }

    public function post_create()
    {

        $messages = array(
            'unique_avatar_name' => 'This combination of avatar first name and avatar last name has already been taken.',
        );

        $rules = array(
            'email'                 => 'required|email|unique:users',
            'username'              => 'required|unique:users|alpha_dash|max:30|min:3',
            'avatar_first_name'     => 'required|alpha_dash|max:30|min:3|unique_avatar_name',
            'avatar_last_name'      => 'required|alpha_dash|max:30|min:3',
            'password'              => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        );

        Validator::register('unique_avatar_name', function($attribute, $value, $parameters)
        {
            $user = Users\Model\User::where('avatar_first_name', '=', Input::get('avatar_first_name'))
                          ->where('avatar_last_name', '=', Input::get('avatar_last_name'))
                          ->first();

            if(isset($user) and !empty($user))
            {
                return false;
            }

            return true;
        });

        $validation = Validator::make(Input::all(), $rules, $messages);

        if ($validation->passes())
        {
            $group_id = 0;
            
            if(Bundle::exists('groups'))
            {
                $users_group = Groups\Model\Group::where('slug', '=', 'users')->first();
                if(isset($users_group->id) and ctype_digit($users_group->id))
                {
                    $group_id = $users_group->id;
                }
            }

            $new_user         = new Users\Model\User;
            $new_user->status = 'inactive';

            $require_confirmation = Config::get('settings::core.registration_confirmation_required');
            
            if($require_confirmation == 'no')
            {
                $new_user->status = 'active';
            }
            
            $password = Users\Helper::hash_password(Input::get('password'));

            $new_user->uuid              = Mwi_Core::random_uuid();
            $new_user->email             = Input::get('email');
            $new_user->username          = Input::get('username');
            $new_user->avatar_first_name = Input::get('avatar_first_name');
            $new_user->avatar_last_name  = Input::get('avatar_last_name');
            $new_user->group_id          = $group_id;
            $new_user->hash              = $password['hash'];
            $new_user->salt              = $password['salt'];
            $new_user->password          = Hash::make(Input::get('password'));
            $new_user->save();

            Event::fire('registration.user_signup', array($new_user));
            //Event for Robust
            Event::fire('users.created', array($new_user));

            if($require_confirmation == 'no')
            {
                Auth::login($new_user->id);
                
                Event::fire('registration.user_activated', array($new_user));
                //Event for Robust
                Event::fire('users.updated', array($new_user));
                
                $site_name                  = Settings\Config::get('settings::core.site_name');
                $this->data['message']      = Lang::line('registration::lang.welcome_signup', array('site_name' => $site_name))->get(APP_LANG);
                $this->data['message_type'] = 'success';
                
                return Redirect::to('home')->with($this->data);
            }
            else
            {
                // save activation record on database
                $activation_record          = new Registration\Model\Code;
                $activation_record->user_id = $new_user->id;
                $activation_record->code    = Mwi_Core::keygen();
                $activation_record->save();

                // new xblade to parse the email template
                $xblade = new Xblade();
                $xblade->scopeGlue(':');

                // data to be passed to email template
                $data['user']                  = $new_user;
                $data['activation_code']       = $activation_record->code;
                $data['url']['base']           = URL::base();
                $data['settings']['site_name'] = Config::get('settings::core.site_name');

                // get email template based on settings
                $email_address = Config::get('settings::core.server_email');
                $template_id   = Config::get('settings::core.registration_email_template');
                $email_data    = Email\Model\Template::find($template_id);

                //send email to user
                Email\Message::to($new_user->email)
                             ->from($email_address)
                             ->subject($xblade->parse($email_data->subject, $data))
                             ->body($xblade->parse($email_data->body, $data))
                             ->html($email_data->type)
                             ->send();
                
                $this->data['message']      = __('registration::lang.Thank you Please check your email to activate your new account')->get(APP_LANG);
                $this->data['message_type'] = 'success';
                return Redirect::to('home')->with($this->data);
            }

        }
        
        return Redirect::to('signup')->with_errors($validation)->with_input();
    }

    public function get_activate($activation_code)
    {
        $activation = null;
        
        // Finc activation code
        if(isset($activation_code) and !empty($activation_code))
        {
            $activation = Registration\Model\Code::where('code', '=', $activation_code)->first();
        }

        // Validate activation code
        if( is_null($activation) or !isset($activation->user_id) )
        {
            $this->data['message']      = __('registration::lang.A problem occurred while activating your account please contact support')->get(APP_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to('home')->with($this->data);
        }

        // Find  and validate account to activate
        $account = Users\Model\User::find($activation->user_id);
        if( !isset($account) or empty($account) )
        {
            $this->data['message']      = __('registration::lang.A problem occurred while activating your account please contact support')->get(APP_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to('home')->with($this->data);
        }

        // Activate account
        $account->status = 'active';
        $account->save();
        
        //Event for Robust
        Event::fire('users.updated', array($account));

        // Delete activation code
        $activation->delete();

        // Login user after activation
        Auth::login($account->id);

        // Redirect with success message
        $this->data['message']      = __('registration::lang.Your account was successfully activated')->get(APP_LANG);
        $this->data['message_type'] = 'success';
        return Redirect::to('home')->with($this->data);
    }

    public function get_pwreset()
    {
        $this->data['meta_title'] = 'Reset Password';
        return $this->theme->render('registration::frontend.pwreset', $this->data);
    }

    public function post_pwreset()
    {
        $rules = array(
            'email' => 'required|email|account_exists',
        );

        $messages = array(
            'account_exists' => Lang::line('registration::lang.This email was not found.')->get(APP_LANG),
        );

        $account = null;

        Validator::register('account_exists', function($attribute, $value, $parameters) use (&$account)
        {
            $account = Users\Model\User::where('email', '=', Input::get('email'))->first();

            if(isset($account) and !empty($account))
            {
                return true;
            }

            return false;
        });

        $validation = Validator::make(Input::all(), $rules, $messages);

        if ($validation->passes())
        {
            $pwreset_record          = new Registration\Model\Code;
            $pwreset_record->user_id = $account->id;
            $pwreset_record->code    = Mwi_Core::keygen();
            $pwreset_record->save();

            // send password reset email
            // new xblade to parse the email template
            $xblade = new Xblade();
            $xblade->scopeGlue(':');

            // data to be passed to email template
            $data['user']                    = $account;
            $data['forgotten_password_code'] = $pwreset_record->code;
            $data['url']['base']             = URL::base();
            $data['settings']['site_name']   = Config::get('settings::core.site_name');

            // get email template based on settings
            $email_address = Config::get('settings::core.server_email');
            $template_id   = Config::get('settings::core.registration_pwreset_email_template');
            $email_data    = Email\Model\Template::find($template_id);

            // send email to user
            Email\Message::to($account->email)
                         ->from($email_address)
                         ->subject($xblade->parse($email_data->subject, $data))
                         ->body($xblade->parse($email_data->body, $data))
                         ->html($email_data->type)
                         ->send();

            $this->data['message']      = __('registration::lang.An email was sent to you with instructions to reset your password')->get(APP_LANG);
            $this->data['message_type'] = 'success';
            return Redirect::to('home')->with($this->data);
        }

        return Redirect::back()->with_errors($validation)->with_input();
    }

    public function get_reset_pass($user_id, $code)
    {
        $code_record = Registration\Model\Code::where('code', '=', $code)->where('user_id', '=', $user_id)->first();
        
        if(isset($code_record) and !empty($code_record))
        {
            $this->data['user_id'] = $code_record->user_id;
            $this->data['code']    = $code_record->code;

            return $this->theme->render('registration::frontend.reset_pass', $this->data);
        }
        else
        {
            $this->data['message']      = __('registration::lang.Invalid password reset code or it\'s older then 24H')->get(APP_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to('home')->with($this->data);
        }
    }

    public function post_reset_pass()
    {
        $rules = array(
            'user_id'               => 'required',
            'password'              => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        );

        $validation = Validator::make(Input::all(), $rules);

        if ($validation->passes())
        {
             $code_record = Registration\Model\Code::where('code', '=', Input::get('code'))->where('user_id', '=', Input::get('user_id'))->first();
            if( !isset($code_record) or empty($code_record))
            {
                $this->data['message']      = __('registration::lang.An error occurred while updating your password please contact support')->get(APP_LANG);
                $this->data['message_type'] = 'error';
                return Redirect::to('home')->with($this->data);
            }
        
            $pass_was_updated = Registration\User::update_password(Input::get('password'), Input::get('user_id'));

            if($pass_was_updated)
            {
                $code_record->delete();
                
                // send user password change email

                Auth::login($code_record->user_id);

                $this->data['message']      = __('registration::lang.Your password was successfully updated')->get(APP_LANG);
                $this->data['message_type'] = 'success';
            }
            else
            {
                $this->data['message']      = __('registration::lang.An error occurred while updating your password please contact support')->get(APP_LANG);
                $this->data['message_type'] = 'error';
            }

            return Redirect::to('home')->with($this->data);
        }

        return Redirect::to('registration/reset_pass')->with_errors($validation)->with_input();
    }
}