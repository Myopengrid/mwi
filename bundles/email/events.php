<?php

Event::listen('send_email', function($email, $parser = null, $data = array()){
    
    if(!isset($parser))
    {
        $parser = new Xblade();
        $parser->scopeGlue(':');
    }

    $to_email = null;
    if(!isset($email['to_email']))
    {
        Log::error("Error sending email event. Email must have a recipient email addres. [to_email]");
        return false;
    }
    else
    {
        $to_email = $email['to_email'];
    }

    $from_email = null;
    if(!isset($email['from_email']))
    {
        $from_email = Config::get('settings::core.server_email');
    }
    else
    {
        $from_email = $email['from_email'];
    }

    if(!isset($email['type']) or $email['type'] != 'text')
    {
        $email['type'] = 'html';
    }
    else
    {
        $email['type'] = 'text';
    }

    // Data to be passed to email template
    $data['url']['base']           = URL::base();
    $data['settings']['site_name'] = Config::get('settings::core.site_name');
    $data['request']['ip']         = Request::ip();

    // Not all requests have user agent or languages
    //$data['request']['user_agent'] = implode(', ', Request::header('user-agent'));
    //$data['request']['languages']  = implode(', ', Request::languages());

    if(empty($email['subject'])) return;
    if(empty($email['message'])) return;
    if(empty($to_email)) return;

    $subject = $parser->parse($email['subject'], $data);
    $body    = $parser->parse($email['message'], $data);
    
    //send email to user
    $email = Email\Message::to($to_email)
                 ->from($from_email)
                 ->subject($subject)
                 ->body($body)
                 ->html($email['type'])
                 ->send();
});