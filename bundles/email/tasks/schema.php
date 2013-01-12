<?php

class Email_Schema_Task {

    public function __construct()
    {
        Bundle::register('settings');
        Bundle::start('settings');
        Bundle::register('modules');
        Bundle::start('modules');
    }

    public function install()
    {
        $module = Modules\Model\Module::where_slug('email')->first();
        
        $mail_protocol = array(
            'title'       => 'Mail Protocol', 
            'slug'        => 'mail_protocol', 
            'description' => 'Select desired email protocol', 
            'type'        => 'select', 
            'default'     => 'mail', 
            'value'       => 'mail', 
            'options'     => '{"mail":"Mail","sendmail":"Sendmail","smtp":"SMTP"}', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '',
            'is_gui'      => '1', 
            'module_slug' => 'email', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $mail_protocol = Settings\Model\Setting::create($mail_protocol);

        $sendmail_path = array(
            'title'       => 'Sendmail Path', 
            'slug'        => 'mail_sendmail_path', 
            'description' => 'Path to server sendmail binary', 
            'type'        => 'text', 
            'default'     => '/usr/sbin/sendmail', 
            'value'       => '/usr/sbin/sendmail', 
            'options'     => '', 
            'class'       => 'conf-sendmail', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'email', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $sendmail_path = Settings\Model\Setting::create($sendmail_path);

        $smtp_hostname = array(
            'title'       => 'SMTP Host Name', 
            'slug'        => 'mail_smtp_host', 
            'description' => 'The host name of your smtp server', 
            'type'        => 'text', 
            'default'     => '', 
            'value'       => '', 
            'options'     => '', 
            'class'       => 'conf-sendmail', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'email', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $smtp_hostname = Settings\Model\Setting::create($smtp_hostname);

        $smtp_password = array(
            'title'       => 'SMTP Password', 
            'slug'        => 'mail_smtp_pass', 
            'description' => 'SMTP Password', 
            'type'        => 'password', 
            'default'     => '', 
            'value'       => '', 
            'options'     => '', 
            'class'       => 'conf-smtp', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'email', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $smtp_password = Settings\Model\Setting::create($smtp_password);

        $smtp_port = array(
            'title'       => 'SMTP Port', 
            'slug'        => 'mail_smtp_port', 
            'description' => 'SMTP port number', 
            'type'        => 'text', 
            'default'     => '', 
            'value'       => '', 
            'options'     => '', 
            'class'       => 'conf-smtp', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'email', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $smtp_port = Settings\Model\Setting::create($smtp_port);

        $smtp_username = array(
            'title'       => 'SMTP User Name', 
            'slug'        => 'mail_smtp_user', 
            'description' => 'SMTP user name', 
            'type'        => 'text', 
            'default'     => '', 
            'value'       => '', 
            'options'     => '', 
            'class'       => 'conf-smtp', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'email', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $smtp_username = Settings\Model\Setting::create($smtp_username);

        $admin_email = array(
            'title'       => 'Server E-mail', 
            'slug'        => 'server_email', 
            'description' => 'All e-mails to users will come from this e-mail address', 
            'type'        => 'text', 
            'default'     => 'admin@localhost.com', 
            'value'       => 'admin@localhost.com', 
            'options'     => '', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'settings', 
            'module_id'   => $module->id, 
            'order'       => '999', 
        );
        $admin_email = Settings\Model\Setting::create($admin_email);
    }

    public function uninstall()
    {
        //
        // REMOVE SETTINGS
        // 
        $settings = Settings\Model\Setting::where_module_slug('email')->get();
        
        if(isset($settings) and !empty($settings))
        {
            foreach ($settings as $setting) 
            {
                $setting->delete();
            }
        }

        $emal_admin_new_user = Settings\Model\Setting::where_slug('email_admin_on_registration')->first();
        if(isset($settings) and !empty($settings))
        {
            $emal_admin_new_user->delete();
        }
    }

    public function __destruct()
    {
        Bundle::disable('settings');
        Bundle::disable('modules');
    }
}