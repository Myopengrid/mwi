<?php

class Email_Backend_Email_Email_Controller extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->data['section_bar'] = array(
            __('email::lang.Settings')->get(ADM_LANG)          => URL::base().'/'.ADM_URI.'/email',
            __('email::lang.Send Email')->get(ADM_LANG)        => URL::base().'/'.ADM_URI.'/email/new',
            __('email::lang.Email Templates')->get(ADM_LANG)  => URL::base().'/'.ADM_URI.'/email/template',
            __('email::lang.Add Template')->get(ADM_LANG)      => URL::base().'/'.ADM_URI.'/email/template/new',
        );

        $this->data['bar'] = array(
            'title'       => __('email::lang.Email')->get(ADM_LANG),
            'url'         => URL::base().'/'.ADM_URI.'/email',
            'description' => __('email::lang.Let users manage email module settings and create email templates')->get(ADM_LANG),
        );
    }

    public function get_index()
    {
        $this->data['setting_section'] = Settings\Model\Setting::where_module_slug('email')->order_by('order', 'asc')->get();

        $this->data['section_bar_active'] = __('email::lang.Settings')->get(ADM_LANG);//'Settings';
        return $this->theme->render('email::email.index', $this->data);
    }

    // Save settings
    public function put_update()
    {
        $rules = array(
            'mail_protocol' => 'required|mail_protocol',
        );

        $validation = Email\Validator::make(Input::all(), $rules)->speaks(ADM_LANG);

        if ($validation->passes())
        {
            $mail_settings = $validation->attributes;
            
            foreach ($mail_settings as $slug => $value)
            {
                $slug = trim($slug);
                $value = trim($value);
                // Lets update runtime configurations.
                $setting = Config::get('settings::core.'.$slug);
                if($setting != null)
                {
                    Config::set('settings::core.'.$slug, $value);
                }

                // Update database configurations
                $affected = Settings\Model\Setting::where('module_slug', '=', 'email')
                                            ->where('slug', '=', $slug)
                                            ->update(array('value' => $value));
            }
            
            $this->data['message'] = __('email::lang.Email settings were successfully updated')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
            return Redirect::to(ADM_URI.'/email')->with($this->data);

        }
        else
        {
            return Redirect::back()->with_errors($validation->errors)->with_input();
        }
    }

    // new Send Email
    public function get_new()
    {
        $this->data['section_bar_active'] = __('email::lang.Send Email')->get(ADM_LANG);
        
        $this->data['groups_dropdown'] = null;
        if(Bundle::exists('groups'))
        {
            $this->data['groups_dropdown'] = Groups\Model\Group::all();
        }
        $this->data['email_templates'] = Email\Model\Template::all();
        return $this->theme->render('email::email.new', $this->data);
    }

    // create send email
    public function post_create()
    {
        $rules = array(
            'template' => 'required|send_email',
        );

        $validation = Email\Validator::make(Input::all(), $rules)->speaks(ADM_LANG);

        if ($validation->passes())
        {
            // Get email service type
            $email_protocol = Config::get('settings::core.mail_protocol');

            $email_address = '';

            if(isset($email_protocol) and !empty($email_protocol))
            {
                switch ($email_protocol) {
                    case 'mail':
                    case 'sendmail':
                        $email_address = Config::get('settings::core.server_email');
                        break;
                    case 'smtp':
                        $email_address = Config::get('settings::core.mail_smtp_user');
                        break;
                    default:
                        \Session::flash('message', __('email::lang.Your email protocol settings are invalid')->get(ADM_LANG));
                        \Session::flash('message_type', 'error');
                        return Redirect::to(ADM_URI.'/email/new');
                        break;
                }
            }
            
            

            $email_type = $validation->attributes['email_type'] == 'html' ? true : false;

            $mailer = new Email\Message;

            // new xblade to parse the email template
            $xblade = new Xblade();
            $xblade->scopeGlue(':');

            // data to be passed to email template
            
            $data['url']['base']           = URL::base();
            $data['settings']['site_name'] = Config::get('settings::core.site_name');
            $data['request']['ip']         = Request::ip();
            $data['request']['user_agent'] = implode(', ', Request::header('user-agent'));
            $data['request']['languages']  = implode(', ', Request::languages());

            foreach ($validation->attributes['email_list'] as $email) 
            {
                $data['user'] = Users\Model\User::where_email($email)->first();
                
                $mailer::to($email)
                    ->from($email_address)
                    ->subject($xblade->parse($validation->attributes['subject'], $data))
                    ->body($xblade->parse($validation->attributes['email_body'], $data))
                    ->html($email_type)
                    ->send();
            }

            \Session::flash('message', __('email::lang.Email was successfully sent')->get(ADM_LANG));
            \Session::flash('message_type', 'success');
            return Redirect::to(ADM_URI.'/email/new');
        }
        else
        {
            return Redirect::to(ADM_URI.'/email/new')->with_input()->with_errors($validation->errors);
        }
    }

    // Used by ajax to create users drop
    // down when selecting/filtering users
    // to send an email 
    public function post_get_users()
    {

        $status = Input::get('status');
        $group = Input::get('group');

        $users = Users\Model\User::select('*');

        if(isset($group) and $group != '0' and !empty($group))
        {
            $users->where('group_id', '=', $group);
        }
        if(isset($status) and $status != '0' and !empty($status))
        {
            $users->where('status', '=', $status);
        }

        $users = $users->get(array('email', 'avatar_first_name', 'avatar_last_name'));

        $response = array();
        // We need to build the array for response
        foreach ($users as $user)
        {
            $response[$user->email] = $user->avatar_first_name.' '.$user->avatar_last_name;
        }

        return json_encode($response);
    }
}