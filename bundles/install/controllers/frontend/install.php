<?php

use Install\Installer;

class Install_Frontend_Install_Controller extends Base_Controller {
    
    public $layout = 'install::layouts.install';

    /**
     * Array containing the directories that need to be writeable
     *
     * @access private
     * @var array
     */
    private $writeable_directories = array(
        'storage',
        'bundles',
    );

    /**
     * Array containing the files that need to be writeable
     *
     * @access private
     * @var array
     */
    private $writeable_files = array(
        'application/config/application.php',
        'application/config/database.php',
    );


    public function __construct()
    {
        parent::__construct();
        
        $this->writeable_directories[] = basename(path('public')).'/bundles';
        $this->writeable_directories[] = basename(path('public')).'/themes';

        Asset::add('jquery', 'js/jquery.js')->bundle('install');
        Asset::add('installer', 'js/installer.js')->bundle('install');
        $this->layout->title = 'Sagui - Myopengrid Web Interface Installer';
    }

    public function get_index()
    {
        $lang = Session::get('adm_lang');
        Session::flush();
        Session::put('adm_lang', $lang);
        $this->layout->nav_step = 0;
        Session::put('step_1_passed', false);
        Session::put('step_2_passed', false);
        Session::put('step_3_passed', false);
        Session::put('step_4_passed', false);
        Session::put('step_complete', false);
        $this->layout->content = View::make('install::index');
    }

    public function get_step_1()
    {
        Session::put('step_1_passed', false);
        Session::put('step_2_passed', false);
        Session::put('step_3_passed', false);
        Session::put('step_4_passed', false);
        Session::put('step_complete', false);
        
        $this->layout->nav_step = 1;
        
        Installer::check_requeriments();

        Installer::check_recomended();
        
        $this->layout->content = View::make('install::step_1')
                                    ->with('requeriments', Installer::$requeriments)
                                    ->with('requeriments_passed', Installer::$requeriments_passed);
    }

    public function post_step_1()
    {
        Session::put('step_2_passed', false);
        Session::put('step_3_passed', false);
        Session::put('step_4_passed', false);
        Session::put('step_complete', false);

        if(Installer::check_server())
        {
            Session::put('step_1_passed', true);
            return Redirect::to('install/step_2');
        }
        else
        {
            //there some required items missing
            return Redirect::to('install/step_1');
        }
        
    }
    public function get_step_2()
    {
        Session::put('step_2_passed', false);
        Session::put('step_3_passed', false);
        Session::put('step_4_passed', false);
        Session::put('step_complete', false);

        if(Session::get('step_1_passed') === false)
        {
            return Redirect::to('install/step_1');
        }

        $this->layout->nav_step = 2;

        $this->layout->content = View::make('install::step_2');
    }

    public function post_step_2()
    {

        Session::put('step_2_passed', false);
        Session::put('step_3_passed', false);
        Session::put('step_4_passed', false);
        Session::put('step_complete', false);

        if(Session::get('step_1_passed') === false)
        {
            return Redirect::to('install/step_1');
        }

        $rules = array(
            'driver' => 'required|db_type|test_db_connection',
        );

        $validation = \Install\Validator::make(Input::all(), $rules)->speaks(Session::get('adm_lang'));
        
        if($validation->passes())
        {     
            Session::put('step_2_passed', true);
            
            // Write database file
            Installer::write_db_file(Input::all());
            
            return Redirect::to('install/step_3');
        }
        else
        {
            return Redirect::to('install/step_2')
                            ->with_errors($validation->errors)
                            ->with_input();
        }
    }

    public function get_step_3()
    {
        Session::put('step_3_passed', false);
        Session::put('step_4_passed', false);
        Session::put('step_complete', false);

        if(!Session::get('step_1_passed'))
        {
            return Redirect::to('install/step_1');
        }

        if(!Session::get('step_2_passed'))
        {
            return Redirect::to('install/step_2');
        }

        $this->layout->nav_step = 3;
        $data = new stdClass();
        

        $app_path = explode('/', path('app'));
        foreach ($app_path as $key => $value) 
        {
           if(empty($value))
            unset($app_path[$key]);
        }
        array_pop($app_path);
        $app_path = implode('/', $app_path);
        
        // Get the write permissions for the folders
        foreach($this->writeable_directories as $dir)
        {
            // Try CHMOD if the operation is permited will to automatically
            @chmod('/'.$app_path.'/'.$dir, 0777);
            $permissions['directories'][$dir] = Installer::is_really_writable('/'.$app_path.'/'.$dir);
        }

        foreach($this->writeable_files as $file)
        {
            // Try CHMOD if the operation is permited will to automatically
            @chmod('/'.$app_path.'/'.$file, 0666);
            $permissions['files'][$file] = Installer::is_really_writable('/'.$app_path.'/'.$file);
        }

        // we are asking to read file1
        // but if mod_rewrite is enable it will 
        // return file2 content (true) the .htaccess file is 
        // in the public folder /bundles/install/test_mod_rewrite/.htaccess
        $file = @file_get_contents(URL::base().'/bundles/install/test_mod_rewrite/file1.txt');

        if($file == 'true')
        {
            Installer::set_app_index();
        }


        // If all permissions are TRUE, go ahead
        $data->step_passed = ! in_array(FALSE, $permissions['directories']) && !in_array(FALSE, $permissions['files']);
        
        Session::put('step_3_passed', $data->step_passed);

        // Skip Step 2 if it passes
        // if ($data->step_passed)
        // {
        //     Session::put('step_3_passed', true);
            
        //     return Redirect::to('admin/installer/step_4');
        // }

        $this->layout->content = View::make('install::step_3')->with('permissions', $permissions)->with('all_passed', $data->step_passed);
    }

    public function post_step_3()
    {
        Session::put('step_4_passed', false);
        Session::put('step_complete', false);

        if(!Session::get('step_1_passed'))
        {
            return Redirect::to('install/step_1');
        }

        if(!Session::get('step_2_passed'))
        {
            return Redirect::to('install/step_2');
        }

        if(!Session::get('step_3_passed'))
        {
            return Redirect::to('install/step_3');
        }

        return Redirect::to('install/step_4');
    }

    public function get_step_4()
    {
        if(!Session::get('step_1_passed'))
        {
            return Redirect::to('install/step_1');
        }

        if(!Session::get('step_2_passed'))
        {
            return Redirect::to('install/step_2');
        }
        if(!Session::get('step_3_passed'))
        {
            return Redirect::to('install/step_3');
        }
        
        $this->layout->nav_step = 4;
        
        $new_uuid = Installer::gen_uuid();

        $this->layout->content = View::make('install::step_4')
                                        ->with('uuid', $new_uuid);
    }

    public function post_step_4()
    {
        if(!Session::get('step_1_passed'))
        {
            return Redirect::to('install/step_1');
        }

        if(!Session::get('step_2_passed'))
        {
            return Redirect::to('install/step_2');
        }
        if(!Session::get('step_3_passed'))
        {
            return Redirect::to('install/step_3');
        }


        $messages = array(
            'check_uuid' => 'The uuid provided is invalid.',
        );

        Validator::register('check_uuid', function($attribute, $value, $parameters)
        {
            return Installer::validate_uuid($value);
        });

        $rules = array(
            'uuid'              => 'required|check_uuid',
            'user_name'         => 'required|alpha_dash',
            'avatar_first_name' => 'required|alpha_dash',
            'avatar_last_name'  => 'required|alpha_dash',
            'email'             => 'required|email',
            'password'          => 'required',
        );

        $validation = Validator::make(Input::all(), $rules, $messages)->speaks(Session::get('adm_lang'));

        if ($validation->passes())
        {
            Session::put('uuid',              Input::get('uuid'));
            Session::put('user_name',         Input::get('user_name'));
            Session::put('avatar_first_name', Input::get('avatar_first_name'));
            Session::put('avatar_last_name',  Input::get('avatar_last_name'));
            Session::put('email',             Input::get('email'));
            Session::put('password',          Input::get('password'));
            Session::put('step_4_passed',     true);

            if(!Installer::install())
            {
                FirePHP::getInstance(true)->warn('installation failed');die();
            }
            else
            {

                $salt = md5(Installer::gen_uuid());
                $hash = md5(md5(Session::get('password').':'.$salt));
                //Create Default User
                $user_admin = array(
                    'uuid'              => Session::get('uuid'),
                    'group_id'          => 1,
                    'username'          => Session::get('user_name'),
                    'avatar_first_name' => Session::get('avatar_first_name'),
                    'avatar_last_name'  => Session::get('avatar_last_name'),
                    'hash'              => $hash,
                    'salt'              => $salt,
                    'password'          => Hash::make(Session::get('password')),
                    'email'             => Session::get('email'),
                    'status'            => 'active',
                    'is_core'           => 1,
                    'created_at'        => date("Y-m-d H:i:s", time()),
                    'updated_at'        => date("Y-m-d H:i:s", time()),
                );

                $user = DB::table('users')->insert_get_id($user_admin);

                Auth::login($user);

                return Redirect::to('install/complete');
            }
        }
        else
        {
            return Redirect::to('install/step_4')
                            ->with_errors($validation)
                            ->with_input();
        }
    }

    public function get_complete()
    {
        if(!Session::get('step_4_passed'))
        {
            return Redirect::to('install/step_4');
        }
        
        // Update bundles.php file
        $source = path('bundle').'install/file_templates/bundles.tpl';
        $destination = path('app').'bundles.php';
        Installer::write_file($source, $destination);

        // Set application as installed
        Installer::set_app_installed();
        
        $this->layout->nav_step = 5;
        $this->layout->content = View::make('install::complete')
                                        ->with('avatar_first_name', Session::get('avatar_first_name'))
                                        ->with('avatar_last_name', Session::get('avatar_last_name'))
                                        ->with('email', Session::get('email'))
                                        ->with('password', Session::get('password'));
    }
}