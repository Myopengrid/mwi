<?php

class Modules_Backend_Modules_Controller extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->data['bar'] = array(
            'title'       => __('modules::lang.Modules')->get(ADM_LANG),
            'url'         => URL::base().DS.ADM_URI.'/modules',
            'description' => __('modules::lang.Allow admin to see a list of currently installed modules')->get(ADM_LANG),
        );

        $this->data['section_bar'] = array(
            Lang::line('modules::lang.Modules')->get(ADM_LANG)           => URL::base().DS.ADM_URI.'/modules',
            Lang::line('modules::lang.Core Modules')->get(ADM_LANG)      => URL::base().DS.ADM_URI.'/modules/core',
            Lang::line('modules::lang.Upload New Module')->get(ADM_LANG) => URL::base().DS.ADM_URI.'/modules/upload',
        );
    }

    public function get_index()
    {
        $this->data['section_bar_active'] = __('modules::lang.Modules')->get(ADM_LANG);

        $this->data['modules'] = Modules\Installer::make()->all();

        return $this->theme->render('modules::backend.index', $this->data);
    }

    public function get_core()
    {
        $this->data['section_bar_active'] = __('modules::lang.Core Modules')->get(ADM_LANG);
        
        $this->data['core_modules'] = Modules\Installer::make()->all(array('core'));

        return $this->theme->render('modules::backend.core', $this->data);
    }

    public function get_upload()
    {
        $this->data['section_bar_active'] = __('modules::lang.Upload New Module')->get(ADM_LANG);
        return $this->theme->render('modules::backend.upload', $this->data);
    }

    public function post_upload()
    {
        // Upload bundle
        $rules = array('zip_file' => 'required|mimes:zip');

        $validation = Validator::make(Input::all(), $rules)->speaks(ADM_LANG);
        
        if ($validation->passes())
        {
            $zip_info = Input::file();
            $zip_file = $zip_info['zip_file'];

            $installer = new Modules\Installer;
            if($installer::upload($zip_file))
            {
                $this->data['message_type'] = 'success';
                $this->data['message'] = __('modules::lang.Module was successfully uploaded')->get(ADM_LANG);
                return Redirect::to(ADM_URI.DS.'modules')->with($this->data);
            }
            else
            {
                return Redirect::to(ADM_URI.DS.'modules/upload')->with($this->data)->with_errors($installer::$errors);
            }
        }
        else
        {
            return Redirect::to(ADM_URI.DS.'modules/upload')->with($this->data)->with_errors($validation->errors);
        }
    }

    public function post_install($module_slug)
    {
        $installer = new Modules\Installer;
        if($installer::install($module_slug))
        {
            if(Request::ajax())
            {
                // temporary until
                // fix/reload top bar
                // to remove modules
                // links if any
                // partial view error (not found)
                return 'success';

                $data = array(
                    'flash_message'    => array(
                        'message_type' => 'success',
                        'messages'     => array(__('modules::lang.Module was successfully installed')->get(ADM_LANG)),
                    ),
                    'html'           => array(
                        'identifier' => 'tbody',
                        'partial'    => View::make('modules::backend.partials.modules_index_table')
                                            ->with('modules', $installer->all())
                                            ->render(),
                    ),
                );
                
                return json_encode($data);
            }

            $this->data['message_type'] = 'success';
            $this->data['message'] = __('modules::lang.Module was successfully installed')->get(ADM_LANG);
            return Redirect::to(ADM_URI.DS.'modules')->with($this->data);
        }
        else
        {
            if(Request::ajax())
            {
                $data = array(
                    'flash_message'    => array(
                        'message_type' => 'error',
                        'messages'     => $installer::$errors->all(),
                    )
                );
                return json_encode($data);
            }
            
            $this->data['message_type'] = 'error';
            $this->data['message'] = $installer::$errors->first();
            return Redirect::to(ADM_URI.DS.'modules')->with($this->data)->with_errors($installer::$errors);
        }
    }

    public function put_enable($module_slug)
    {
        $installer = new Modules\Installer;
        if($installer->enable($module_slug))
        {
            if(Request::ajax())
            {
                // temporary until
                // fix/reload top bar
                // to remove modules
                // links if any
                // partial view error (not found)
                return 'success';

                $data = array(
                    'flash_message'    => array(
                        'message_type' => 'success',
                        'messages'     => array(__('modules::lang.Module was successfully enabled')->get(ADM_LANG)),
                    ),
                    'html'           => array(
                        'identifier' => 'tbody',
                        'partial'    => View::make('modules::backend.partials.modules_index_table')
                                            ->with('modules', $installer->all())
                                            ->render(),
                    ),
                );

                return json_encode($data);
            }

            $this->data['message_type'] = 'success';
            $this->data['message'] = __('modules::lang.Module was successfully enabled')->get(ADM_LANG);
            return Redirect::to(ADM_URI.DS.'modules')->with($this->data);
        }
        else
        {
            if(Request::ajax())
            {
                $data = array(
                    'flash_message'    => array(
                        'message_type' => 'error',
                        'messages'     => $installer::$errors->all(),
                    )
                );

                // temporary until
                // fix/reload top bar
                // to remove modules
                // links if any
                return 'success';


                return json_encode($data);
            }

            $this->data['message_type'] = 'error';
            $this->data['message'] = $installer::$errors->first();
            return Redirect::to(ADM_URI.DS.'modules')->with($this->data)->with_errors($installer::$errors);
        }
    }

    public function put_disable($module_slug)
    {
        $installer = new Modules\Installer;
        if($installer::disable($module_slug))
        {
            if(Request::ajax())
            {
                // temporary until
                // fix/reload top bar
                // to remove modules
                // links if any
                // partial view error (not found)
                return 'success';

                $data = array(
                    'flash_message'    => array(
                        'message_type' => 'success',
                        'messages'     => array(__('modules::lang.Module was successfully disabled')->get(ADM_LANG)),
                    ),
                    'html'           => array(
                        'identifier' => 'tbody',
                        'partial'    => View::make('modules::backend.partials.modules_index_table')
                                            ->with('modules', $installer->all())
                                            ->render(),
                    ),
                );

                return json_encode($data);
            }

            $this->data['message_type'] = 'success';
            $this->data['message']      = __('modules::lang.Module was successfully disabled')->get(ADM_LANG);
            return Redirect::to(ADM_URI.DS.'modules')->with($this->data);
        }
        else
        {
            if(Request::ajax())
            {
                $data = array(
                    'action'       => 'flash_message',
                    'message_type' => 'error',
                    'messages'     => $installer::$errors->all(),
                );
                return json_encode($data);
            }
            $this->data['message_type'] = 'error';
            $this->data['message']      = $installer::$errors->first();
            return Redirect::to(ADM_URI.DS.'modules')->with($this->data);
        }
    }

    public function delete_uninstall($module_slug)
    {
        $installer = new Modules\Installer;

        if($installer::uninstall($module_slug))
        {
            if(Request::ajax())
            {
                // temporary until
                // fix/reload top bar
                // to remove modules
                // links if any
                // partial view error (not found)
                return 'success';

                $data = array(
                    'flash_message'    => array(
                        'message_type' => 'success',
                        'messages'     => array(__('modules::lang.Module ":module_slug" was successfully uninstalled', $module_slug)->get(ADM_LANG)),
                    ),
                    'html'           => array(
                        'identifier' => 'tbody',
                        'partial'    => View::make('modules::backend.partials.modules_index_table')
                                            ->with('modules', $installer->all())
                                            ->render(),
                    ),
                );

                return json_encode($data);
            }

            $this->data['message_type'] = 'success';
            $this->data['message']      = __('modules::lang.Module ":module_slug" was successfully deleted', $module_slug)->get(ADM_LANG);
            return Redirect::to(ADM_URI.DS.'modules')->with($this->data);
        }
        else
        {
            if(Request::ajax())
            {
                $data = array(
                    'flash_message'    => array(
                        'message_type' => 'error',
                        'messages'     => $installer::$errors->all(),
                    )
                );

                return json_encode($data);
            }

            $this->data['message_type'] = 'error';
            $this->data['message']      = $installer::$errors->first();
            return Redirect::to(ADM_URI.DS.'modules')->with($this->data);
        }
    }

    public function post_remove($module_slug)
    {
        $installer = new Modules\Installer;

        if($installer::remove($module_slug))
        {
            if(Request::ajax())
            {
                // temporary until
                // fix/reload top bar
                // to remove modules
                // links if any
                // partial view error (not found)
                return 'success';

                $data = array(
                    'flash_message'    => array(
                        'message_type' => 'success',
                        'messages'     => array(__('modules::lang.Module ":module_slug" was successfully removed', $module_slug)->get(ADM_LANG)),
                    ),
                    'html'           => array(
                        'identifier' => 'tbody',
                        'partial'    => View::make('modules::backend.partials.modules_index_table')
                                            ->with('modules', $installer->all())
                                            ->render(),
                    ),
                );

                return json_encode($data);
            }

            $this->data['message_type'] = 'success';
            $this->data['message']      = __('modules::lang.Module ":module_slug" was successfully uninstalled', $module_slug)->get(ADM_LANG);
            return Redirect::to(ADM_URI.DS.'modules')->with($this->data);
        }
        else
        {
            if(Request::ajax())
            {
                $data = array(
                    'flash_message'    => array(
                        'message_type' => 'error',
                        'messages'     => $installer::$errors->all(),
                    )
                );
                return json_encode($data);
            }

            $this->data['message_type'] = 'error';
            $this->data['message']      = $installer::$errors->first();
            return Redirect::to(ADM_URI.DS.'modules')->with($this->data);
        }
    }
}