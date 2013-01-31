<?php

/*
|--------------------------------------------------------------------------
| Event listner for new user registration
|--------------------------------------------------------------------------
|
| If email confirmation is enabled send an email to Admin when a new 
| user registers
|
*/
Event::listen('registration.user_signup', function($user)
{
    $send_email_to_admin = Config::get('settings::core.email_admin_on_registration');
    if($send_email_to_admin == 'yes')
    {
        // new xblade to parse the email template
        $xblade = new Xblade();
        $xblade->scopeGlue(':');

        // data to be passed to email template
        $data['user']                  = $user;
        $data['url']['base']           = URL::base();
        $data['settings']['site_name'] = Config::get('settings::core.site_name');
        $data['request']['ip']         = Request::ip();
        $data['request']['user_agent'] = implode(', ', Request::header('user-agent'));
        $data['request']['languages']  = implode(', ', Request::languages());

        // get email template based on settings
        $email_address = Config::get('settings::core.server_email');
        $template_id   = Config::get('settings::core.admin_registration_email_template');
        $email_data    = Email\Model\Template::find($template_id);

        //send email to Website Administrator
        Email\Message::to($email_address)
                     ->from($email_address)
                     ->subject($xblade->parse($email_data->subject, $data))
                     ->body($xblade->parse($email_data->body, $data))
                     ->html($email_data->type)
                     ->send();
    }
});

/*
|--------------------------------------------------------------------------
| Registration Codes Listner
|--------------------------------------------------------------------------
|
| Listner to remove password reset codes older then 24h
|
*/
Event::listen('app.cron', function()
{
    $yesterday = date('Y-m-d H:i:s', strtotime("-1 day", time()));
    $old_codes = \Registration\Model\Code::where('created_at', '<', $yesterday)->get();
    foreach ($old_codes as $code) 
    {
        $code->delete();
    }
});