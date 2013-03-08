<?php

class Opensim_Backend_Settings_Controller extends Admin_Controller {
    

    public function __construct()
    {
        parent::__construct();

        $this->data['bar'] = array(
            'title'       => Lang::line('opensim::lang.Opensim')->get(ADM_LANG),
            'url'         => URL::base().'/'.ADM_URI.'/opensim',
            'description' => Lang::line('opensim::lang.Provide a webinterface to manage opensim virtual worlds')->get(ADM_LANG),
        );

        $this->data['section_bar'] = array(
            Lang::line('opensim::lang.Settings')->get(ADM_LANG)          => URL::base().'/'.ADM_URI.'/opensim/settings',
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
        }

        $this->data['settings'] = Settings\Model\Setting::where_section('opensim_settings')->get();
        $this->data['section_bar_active'] = Lang::line('opensim::lang.Settings')->get(ADM_LANG);
        return $this->theme->render('opensim::backend.settings.index', $this->data);
    }

    public function get_database()
    {
        $this->data['settings'] = Settings\Model\Setting::where_module_slug('opensim')->where_section('opensim_db_settings')->get();
        $this->data['section_bar_active'] = Lang::line('opensim::lang.Database Settings')->get(ADM_LANG);
        return $this->theme->render('opensim::backend.settings.index', $this->data);
    }

    public function put_update()
    {
        $settings = Input::all();

        $raw_rules = Settings\Model\Setting::where_in('slug', array_keys($settings))->where_module_slug('opensim')->get();
        
        $rules = array();
        foreach ($raw_rules as $setting) 
        {
            if(isset($setting->validation) and !empty($setting->validation))
            {
                $rules[$setting->slug] = $setting->validation;
            }
        }

        $validation = \Opensim\Validator::make($settings, $rules)->speaks(ADM_LANG);

        if($validation->fails())
        {
            return Redirect::back()->with_errors($validation->errors);
        }

        if(isset($settings['database_driver']))
        {
            $credentials = array(
                'driver'   => $settings['database_driver'],
                'host'     => $settings['database_host'],
                'database' => $settings['database_name'],
                'username' => $settings['database_user'],
                'password' => $settings['database_password'],
                'charset'  => 'utf8',
                'prefix'   => $settings['database_table_prefix'],
                'port'     => $settings['database_port'],
            );

            $db = null;
        
            switch ($credentials['driver'])
            {
                case 'sqlite':
                    $db = new \Laravel\Database\Connectors\SQLite;
                    break;

                case 'mysql':
                    $db = new \Laravel\Database\Connectors\MySQL;
                    break;

                case 'pgsql':
                    $db = new \Laravel\Database\Connectors\Postgres;
                    break;

                case 'sqlsrv':
                    $db = new \Laravel\Database\Connectors\SQLServer;
                    break;
            }

            \Config::set('error.detail', false);
            \Event::listen('500', function($message) 
            {
                \Settings\Config::set('settings::core.passes_db_settings', 0, true);
                $this->data['message'] = $message;
                $this->data['message_type'] = 'error';
                $this->data['settings'] = Settings\Model\Setting::where_module_slug('opensim')->where_section('opensim_db_settings')->get();
                $this->data['section_bar_active'] = Lang::line('opensim::lang.Database Settings')->get(ADM_LANG);
                $content = $this->theme->render('opensim::backend.settings.index', $this->data);
                echo $content;
                die();
            });

            // If we get an exception when
            // trying to connect to the db
            // the event above will catch it
            // and return our view with the 
            // error message
            $db->connect($credentials);
            
            // Set database flag
            Settings\Config::set('settings::core.passes_db_settings', 1, true);

            foreach ($settings as $slug => $value)
            {
                // Update database configurations
                if( !empty($value) )
                {
                    $affected = Settings\Model\Setting::where_slug($slug)->where_module_slug('opensim')
                                                        ->update(array('value' => $value));
                }
            }

            $this->data['message'] = Lang::line('opensim::lang.Opensim settings were successfully updated')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
            return Redirect::back()->with($this->data);
        }
        else
        {
            $db_is_ready = Config::get('settings::core.passes_db_settings');

            if((bool)$db_is_ready)
            {
                foreach ($settings as $slug => $value)
                {
                    // Update database configurations
                    if( strlen($value) > 0 )
                    {
                        $affected = Settings\Model\Setting::where_slug($slug)->where_module_slug('opensim')
                                                            ->update(array('value' => $value));
                    }
                }
                $this->data['message'] = Lang::line('opensim::lang.Opensim settings were successfully updated')->get(ADM_LANG);
                $this->data['message_type'] = 'success';
                return Redirect::back()->with($this->data);
            }
            else
            {
                Session::flash('message_type', 'error');
                Session::flash('message', Lang::line('opensim::lang.Your opensim database needs to be configured!')->get(ADM_LANG));
                return Redirect::back()->with($this->data);
            }
        }
    }
}