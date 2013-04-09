<?php

class Themes_Backend_Themes_Controller extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->data['bar'] = array(
            'title'       => __('themes::lang.Themes')->get(ADM_LANG),
            'url'         => URL::base() . '/'.ADM_URI.'/'.'themes',
            'description' => __('themes::lang.Allows admins and staff to switch themes, upload new themes, and manage theme options')->get(ADM_LANG),
        );

        $this->data['section_bar'] = array(
            __('themes::lang.Frontend Themes')->get(ADM_LANG)      => URL::base().'/'.ADM_URI.'/'.'themes',
            __('themes::lang.Backend Themes')->get(ADM_LANG)       => URL::base().'/'.ADM_URI.'/'.'themes/backend',
            __('themes::lang.Not Installed Themes')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/'.'themes/not_installed',
            __('themes::lang.Upload New Theme')->get(ADM_LANG)     => URL::base().'/'.ADM_URI.'/themes/upload',
        );
    }

    public function get_index()
    {
        $this->data['themes'] = Themes\Model\Theme::where('layer', '=', 'frontend')->get();
        $this->data['section_bar_active'] = __('themes::lang.Frontend Themes')->get(ADM_LANG);
        return $this->theme->render('themes::backend.index', $this->data);
    }

    public function get_backend()
    {
        $this->data['themes'] = Themes\Model\Theme::where('layer', '=', 'backend')->get();
        $this->data['section_bar_active'] = __('themes::lang.Backend Themes')->get(ADM_LANG);
        return $this->theme->render('themes::backend.backend', $this->data);
    }

    public function get_not_installed()
    {

        $themes_path = path('public').'themes'.DS;
        
        $all_theme_folders = glob($themes_path.'*', GLOB_ONLYDIR);

        $themes = Themes\Model\Theme::order_by('name', 'asc')->get();

        $all_theme_slugs = array();

        // Grab the last folder name (the theme slug)
        foreach($all_theme_folders as $file)   
        {
            if(is_dir($file))
            {
                $folder_name = basename($file);
                $all_theme_slugs[$folder_name] = $folder_name;
            }  
        }
        unset($all_theme_folders);

        // Remove installed themes from $all_theme_slugs array
        foreach ($themes as $theme)
        {
            if(isset($all_theme_slugs[$theme->slug]))
            {
                unset($all_theme_slugs[$theme->slug]);
            }
        }

        $this->data['not_installed_themes'] = array();
        if( ! empty($all_theme_slugs) )
        {
            foreach ($all_theme_slugs as $theme_slug)
            {
                if(file_exists($path = $themes_path.$theme_slug.DS.'theme.info.json'))
                {
                    // decode as array to be able to send to the view
                    $json_data = json_decode(File::get($path), true);
                    
                    if(isset($json_data) and !empty($json_data))
                    {
                        $json_data['name']        = !isset($json_data['name']) ? 'Not found in the info file' : $json_data['name'];
                        $json_data['description'] = !isset($json_data['description']) ? 'Not found in the info file' : $json_data['description'];
                        $json_data['version']     = !isset($json_data['version']) ? '0.0' : $json_data['version'];

                        if( !isset($json_data['slug']) )
                        {
                            $json_data['slug'] = '';
                            Log::error('The slug info is missing for theme ' . $theme_slug . ' in the info file');
                        }

                        if( !isset($json_data['layer']) )
                        {
                            $json_data['layer'] = '';
                            Log::error('The layer info is missing for theme ' . $theme_slug . ' in the info file');
                        }

                        $this->data['not_installed_themes'][$theme_slug] = $json_data;
                    }
                    else
                    {
                        Log::error('Not installed themes: theme info file for ' . $theme_slug . ' is malformed');
                    }
                }
            }
        }

        $this->data['section_bar_active'] = __('themes::lang.Not Installed Themes')->get(ADM_LANG);
        return $this->theme->render('themes::backend.not_installed', $this->data);  
    }

    public function post_install($theme_slug)
    {
        if(empty($theme_slug))
        {
            $this->data['message'] = __('themes::lang.Theme slug is empty Verify theme info file')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            if(Request::ajax())
            {
                $data = array(
                    'flash_message'    => array(
                        'message_type' => $this->data['message_type'],
                        'messages'     => array($this->data['message']),
                    ),
                );
                return json_encode($data);
            }
            return Redirect::to(ADM_URI.'/themes')->with($this->data);
        }
        
        $theme_path = path('public').'themes'.DS.$theme_slug.DS;

        $theme_info = null;

        if(file_exists($path = $theme_path.'theme.info.json'))
        {
            $theme_info = json_decode(File::get($path));
        }
        else
        {
            $this->data['message'] = __('themes::lang.Cannot find info file', array('theme_slug' => $theme_slug))->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            if(Request::ajax())
            {
                $data = array(
                    'flash_message'    => array(
                        'message_type' => $this->data['message_type'],
                        'messages'     => array($this->data['message']),
                    ),
                );
                return json_encode($data);
            }
            return Redirect::to(ADM_URI.'/themes')->with($this->data);
        }

        if(is_null($theme_info))
        {
            $this->data['message'] = __('themes::lang.Cannot load information from theme info file', array('theme_slug' => $theme_slug))->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            if(Request::ajax())
            {
                $data = array(
                    'flash_message'    => array(
                        'message_type' => $this->data['message_type'],
                        'messages'     => array($this->data['message']),
                    ),
                );
                return json_encode($data);
            }
            return Redirect::to(ADM_URI.'/themes')->with($this->data);
        }

        if( !isset($theme_info->name) or empty($theme_info->name))
        {
            $this->data['message'] = __('themes::lang.Missing theme slug', array('theme_slug' => $theme_slug))->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            if(Request::ajax())
            {
                $data = array(
                    'flash_message'    => array(
                        'message_type' => $this->data['message_type'],
                        'messages'     => array($this->data['message']),
                    ),
                );
                return json_encode($data);
            }
            return Redirect::to(ADM_URI.'/themes')->with($this->data);
        }

        if( !isset($theme_info->layer) or empty($theme_info->layer))
        {
            $this->data['message'] = __('themes::lang.The theme layer is required in the theme info file')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            if(Request::ajax())
            {
                $data = array(
                    'flash_message'    => array(
                        'message_type' => $this->data['message_type'],
                        'messages'     => array($this->data['message']),
                    ),
                );
                return json_encode($data);
            }
            return Redirect::to(ADM_URI.'/themes')->with($this->data);
        }

        if( !isset($theme_info->layout) or empty($theme_info->layout))
        {
            $theme_info->layout = $theme_info->layer == 'backend' ? 'admin' : 'application';
        }

        //Check for layout
        $layout_data = '';
        
        // Full path to our layout file
        $layout_path = $theme_path.'layouts'.DS;
        
        $layout_data = '';

        // Check for normal php extention
        // themes/layouts/layoutfile.php
        if(file_exists($path = $layout_path.$theme_info->layout.EXT))
        {
            $layout_data = File::get($path);
        }
        // Check for Blade extention
        elseif(file_exists($path = $layout_path.$theme_info->layout.BLADE_EXT))
        {
            $layout_data = File::get($path);
        }

        //themes/layouts/[layer]/layoutfile.php
        $layout_path = $theme_path.'layouts'.DS.$theme_info->layer.DS;
        if(file_exists($path = $layout_path.$theme_info->layout.EXT))
        {
            $layout_data = File::get($path);
        }
        // Check for Blade extention
        elseif(file_exists($path = $layout_path.$theme_info->layout.BLADE_EXT))
        {
            $layout_data = File::get($path);
        }

        if(empty($layout_data))
        {
            $this->data['message'] = __('themes::lang.Layout file is empty or not found')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            if(Request::ajax())
            {
                $data = array(
                    'flash_message'    => array(
                        'message_type' => $this->data['message_type'],
                        'messages'     => array($this->data['message']),
                    ),
                );
                return json_encode($data);
            }
            return Redirect::to(ADM_URI.'/themes')->with($this->data);
        }

        $new_theme = Themes\Model\Theme::where('slug', '=', $theme_info->slug)->first();

        if( !is_null($new_theme))
        {
            $this->data['message'] = __('themes::lang.Other theme with this same name is already installed')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            if(Request::ajax())
            {
                $data = array(
                    'flash_message'    => array(
                        'message_type' => $this->data['message_type'],
                        'messages'     => array($this->data['message']),
                    ),
                );
                return json_encode($data);
            }
            return Redirect::to(ADM_URI.'/themes')->with($this->data);
        }

        $new_theme = new \Themes\Model\Theme;

        $new_theme->name                = (isset($theme_info->name) and !empty($theme_info->name)) ? $theme_info->name : 'No name provided';
        $new_theme->slug                = $theme_info->slug;
        $new_theme->description         = (isset($theme_info->description) and !empty($theme_info->description)) ? $theme_info->description : 'No description provided';
        $new_theme->layout              = $theme_info->layout;
        $new_theme->layout_default      = $layout_data;
        $new_theme->layout_content      = $layout_data;
        $new_theme->author              = (isset($theme_info->author) and !empty($theme_info->author)) ? $theme_info->author : 'Unknown';
        $new_theme->version             = (isset($theme_info->version) and !empty($theme_info->version)) ? $theme_info->version : '0.0';
        $new_theme->layer                = $theme_info->layer;
        $new_theme->is_core             = !isset($theme_info->is_core) ? 0 : $theme_info->is_core;
        $new_theme->installed           = 1;

        $new_theme->save();

        $this->data['message']      = __('themes::lang.Theme was successfully installed', array('theme_slug' => $theme_slug))->get(ADM_LANG);
        $this->data['message_type'] = 'success';
        
        $theme_page = $theme_info->layer == 'frontend' ? '' : '/backend';

        return Redirect::back()->with($this->data);
    }

    public function get_edit($theme_id)
    {
        
        $this->data['section_bar'] = array(
            __('themes::lang.Frontend Themes')->get(ADM_LANG)         => URL::base().'/'.ADM_URI.'/'.'themes',
            __('themes::lang.Backend Themes')->get(ADM_LANG)          => URL::base().'/'.ADM_URI.'/'.'themes/backend',
            __('themes::lang.Not Installed Themes')->get(ADM_LANG)    => URL::base().'/'.ADM_URI.'/'.'themes/not_installed',
            __('themes::lang.Edit Layout')->get(ADM_LANG)             => URL::base().'/'.ADM_URI.'/'.'themes/edit_layout/'.$theme_id,
        );

        $this->data['section_bar_active'] = __('themes::lang.Edit Layout')->get(ADM_LANG);
        
        $this->data['theme_view'] = Themes\Model\Theme::find($theme_id);

        return $this->theme->render('themes::backend.edit_layout', $this->data);
    }

    public function put_set_default()
    {
        $theme = new \Themes\Model\Theme;

        $layer = Input::get('layout_type');
        $theme_slug = Input::get('theme');
        
        if( !isset($theme_slug) or empty($theme_slug) )
        {
            $this->data['message'] = __('themes::lang.Please select a theme to save as default')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::back()->with($this->data);
        }
        
        $layout = Input::get('layout');

        // set the old theme back to non default
        $theme::where('is_default', '=', 1)
                ->where('layer', '=', $layer)
                ->update(array('is_default' => '0'));

        $theme::where('slug', '=', $theme_slug)
                ->where('layer', '=', $layer)
                ->update(array('is_default' => '1'));

        \Settings\Model\Setting::where('slug', '=', $layer.'_theme')
                            ->update(array('value' => $theme_slug));

        \Settings\Model\Setting::where('slug', '=', $layer.'_layout')
                            ->update(array('value' => $layout));
        
        $this->data['message'] = __('themes::lang.Theme was saved as default')->get(ADM_LANG);
        $this->data['message_type'] = 'success';

        $theme_page = $layer == 'frontend' ? '' : '/backend';

        return Redirect::to(ADM_URI.'/themes'.$theme_page)->with($this->data);
    }

    public function put_update($theme_id)
    {
        if( ! ctype_digit($theme_id))
        {
            $this->data['message'] = __('themes::lang.Cannot Edit')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/themes')->with($this->data);
        }

        $rules = array(
            'layout_content'  => 'required',
        );

        $validation = Validator::make(Input::all(), $rules)->speaks(ADM_LANG);

        if ($validation->passes())
        {
            $theme = \Themes\Model\Theme::find($theme_id);

            if( !isset($theme) or empty($theme))
            {
                $this->data['message'] = __('themes::lang.Failed to update layout Theme not found')->get(ADM_LANG);
                $this->data['message_type'] = 'error';
                return Redirect::to(ADM_URI.'/themes')->with($this->data);
            }

            // Update database with new layout content
            // set back to original?
            $restore = Input::get('restore');
            if(isset($restore) and $restore == 'restore')
            {
                $theme->layout_content  = $theme->layout_default;
            }
            else
            {
                $theme->layout_content  = Input::get('layout_content');
            }
                 
            $theme->save();

            // Update layout file
            $theme_path  = path('public').'themes'.DS.$theme->slug.DS;
            $layout_path = $theme_path.'layouts'.DS. $theme->layer.DS;

            // Check for normal php extention
            if(file_exists($path = $layout_path.$theme->layout.EXT))
            {
                \File::put($path, $theme->layout_content);
            }
            
            // Check for Blade extention
            elseif(file_exists($path = $layout_path.$theme->layout.BLADE_EXT))
            {
                \File::put($path, $theme->layout_content);
            }

            $layout_path = $theme_path.'layouts'.DS;

            // Check for normal php extention
            if(file_exists($path = $layout_path.$theme->layout.EXT))
            {
                \File::put($path, $theme->layout_content);
            }
            
            // Check for Blade extention
            elseif(file_exists($path = $layout_path.$theme->layout.BLADE_EXT))
            {
                \File::put($path, $theme->layout_content);
            }
            
            if(isset($restore) and $restore == 'restore')
            {
                $this->data['message'] = __('themes::lang.Theme successfully restored')->get(ADM_LANG);
                $this->data['message_type'] = 'success';
            }
            else
            {
                $this->data['message'] = __('themes::lang.Theme successfully updated')->get(ADM_LANG);
                $this->data['message_type'] = 'success';
            }

            $theme_page = $theme->layer == 'frontend' ? '' : '/backend';
            return Redirect::to(ADM_URI.'/themes'.$theme_page)->with($this->data);
            
        }
        else
        {
            $this->data['message'] = __('themes::lang.Layout Field is required')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/themes/edit_layout/'.$theme_id)->with($this->data);
        }
    }

    public function get_upload()
    {
        $this->data['section_bar_active'] = __('themes::lang.Upload New Theme')->get(ADM_LANG);
        return $this->theme->render('themes::backend.upload', $this->data);
    }

    public function post_upload()
    {
        $rules = array('zip_file' => 'required|mimes:zip');

        $validation = Validator::make(Input::all(), $rules)->speaks(ADM_LANG);

        if($validation->passes())
        {
            $themes_path = path('public').'themes'.DS;

            $zip_info = Input::file();
            $zip_file = $zip_info['zip_file'];

            $zip = new \ZipArchive;

            $res = $zip->open($zip_file['tmp_name']);
            if ($res === TRUE) 
            {
                $theme_name_parts = explode('.', $zip_file['name']);

                // lets grab all the folders inside zip file
                $i = 0;
                while ($info = $zip->statIndex($i)) 
                {
                    if ($info['crc'] == 0 && preg_match('#^[^/]*?/$#', $info['name']))
                    {
                        $folders_inside_zipfile[] = preg_replace('#^([^/]*?)/$#', '$1', $info['name']);
                    }
                    $i++;
                }

                if(!isset($folders_inside_zipfile) or empty($folders_inside_zipfile))
                {
                    $this->data['message'] = __('themes::lang.This theme zipfile is invalid', array('theme_folder' => $theme_name_parts['0']))->get(ADM_LANG);
                    $this->data['message_type'] = 'error';
                    return Redirect::to(ADM_URI.'/themes/upload')->with($this->data);
                }

                foreach ($folders_inside_zipfile as $key => $folder_name) 
                {
                    // The user could upload a zipfile with more then
                    // one folder inside and it could overwrite other
                    // themes folder if they have the same folder name
                    if(File::exists($themes_path.DS.$folder_name))
                    {
                        // if any folder inside zipfile is found
                        // return with error
                        $this->data['message'] = __('themes::lang.Your theme zip file contain a folder named [:theme_folder] that already exists inside themes folder', array('theme_folder' => $folder_name))->get(ADM_LANG);
                        $this->data['message_type'] = 'error';
                        return Redirect::to(ADM_URI.'/themes/upload')->with($this->data);
                    }
                }

                if(is_array($theme_name_parts) and count($theme_name_parts) > 0)
                {
                    // check for theme with same folder name
                    if(is_dir($themes_path.$theme_name_parts['0']))
                    {
                        $this->data['message'] = __('themes::lang.A theme with this same name [:theme_folder] is already installed', array('theme_folder' => $theme_name_parts['0']))->get(ADM_LANG);
                        $this->data['message_type'] = 'error';
                        return Redirect::to(ADM_URI.'/themes/upload')->with($this->data);
                    }

                    // check for theme installed
                    $new_theme = \Themes\Model\Theme::where('slug', '=', $theme_name_parts['0'])->first();
                    if(isset($new_theme) and ! empty($new_theme))
                    {
                        $this->data['message'] = __('themes::lang.This theme is already installed')->get(ADM_LANG);
                        $this->data['message_type'] = 'error';
                        return Redirect::to(ADM_URI.'/themes/upload')->with($this->data);
                    }

                    if($zip->extractTo($themes_path))
                    {
                        $zip->close();
                        $this->data['message'] = __('themes::lang.Theme was successfully uploaded')->get(ADM_LANG);
                        $this->data['message_type'] = 'success';
                        return Redirect::to(ADM_URI.'/themes/not_installed')->with($this->data);  
                    }
                    else
                    {
                        $this->data['message'] = __('themes::lang.Failed to decompress zip file')->get(ADM_LANG);
                        $this->data['message_type'] = 'error';
                        return Redirect::to(ADM_URI.'/themes/upload')->with($this->data);
                    }
                    
                }
                else
                {
                    $this->data['message'] = __('themes::lang.The zip file name is invalid')->get(ADM_LANG);
                    $this->data['message_type'] = 'error';
                    return Redirect::to(ADM_URI.'/themes/upload')->with($this->data);
                }
            }
            // Could not open zip file
            $this->data['message'] = __('themes::lang.Invalid zip file')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/themes/upload')->with($this->data);
        }
        else
        {
            return Redirect::to(ADM_URI.'/themes/upload')
                            ->with($this->data)
                            ->with_errors($validation->errors);
        }
    }

    public function delete_destroy($theme_slug)
    {
        if( !isset($theme_slug) or empty($theme_slug))
        {
            $this->data['message']      = __('themes::lang.Cannot delete invalid theme name')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            if(Request::ajax())
            {
                $data = array(
                    'flash_message'    => array(
                        'message_type' => $this->data['message_type'],
                        'messages'     => array($this->data['message']),
                    ),
                );
                return json_encode($data);
            }
            return Redirect::back()->with($this->data);
        }

        $theme = \Themes\Model\Theme::where('slug', '=', $theme_slug)->first();
        
        if(isset($theme))
        {
            // do not delete theme if is defatul
            if($theme->is_default == 1)
            {
                $this->data['message'] = __('themes::lang.Selected as default')->get(ADM_LANG);
                $this->data['message_type'] = 'error';
                if(Request::ajax())
                {
                    $data = array(
                        'flash_message'    => array(
                            'message_type' => $this->data['message_type'],
                            'messages'     => array($this->data['message']),
                        ),
                    );
                    return json_encode($data);
                }
                return Redirect::to(ADM_URI.'/themes')->with($this->data);
                
                // do not delete theme if is a core theme
                if($theme->is_core == 1)
                {
                    $this->data['message'] = __('themes::lang.A core theme cannot be deleted')->get(ADM_LANG);
                    $this->data['message_type'] = 'error';
                    if(Request::ajax())
                    {
                        $data = array(
                            'flash_message'    => array(
                                'message_type' => $this->data['message_type'],
                                'messages'     => array($this->data['message']),
                            ),
                        );
                        return json_encode($data);
                    }
                    return Redirect::to(ADM_URI.'/themes')->with($this->data);
                }
            }

            // Theme is not core and is 
            // not enabled. Remove it
            $theme->delete();
        }
           
        $path = path('public').'themes'.DS.$theme_slug;

        \File::rmdir($path);

        $this->data['message']      = __('themes::lang.Theme was successfully destroyed')->get(ADM_LANG);
        $this->data['message_type'] = 'success';
        if(Request::ajax())
        {
            $data = array(
                'flash_message'    => array(
                    'message_type' => $this->data['message_type'],
                    'messages'     => array($this->data['message']),
                ),
                'hide' => array(
                    'identifier' => 'tr#theme-'.$theme_slug,
                ),
            );
            return json_encode($data);
        }
        return Redirect::back()->with($this->data);
    }
}