<?php

class Splashscreen_Backend_Imagesbackgrounds_Controller extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->data['bar'] = array(
            'title'       => Lang::line('splashscreen::lang.Splash Screen')->get(ADM_LANG),
            'url'         => URL::base().'/'.ADM_URI.'/splashscreen',
            'description' => Lang::line('splashscreen::lang.Allows administrators to update settings for the viewer splash screen')->get(ADM_LANG),
            'buttons'     => array(),
        );

        $this->data['section_bar'] = array(
            Lang::line('splashscreen::lang.Settings')->get(ADM_LANG)               => URL::base().'/'.ADM_URI.'/splashscreen',
            Lang::line('splashscreen::lang.Logo And Backgrounds')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/splashscreen/images_backgrounds',
            Lang::line('splashscreen::lang.Flash News')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/splashscreen/flash_news',
            Lang::line('splashscreen::lang.New Flash News')->get(ADM_LANG)           => URL::base().'/'.ADM_URI.'/splashscreen/flash_news/new',
        );
    }

    public function get_index()
    {
        $this->data['section_bar_active'] = Lang::line('splashscreen::lang.Logo And Backgrounds')->get(ADM_LANG);

        //
        //Get switch background images
        //
        $background_images_path = path('public').'bundles/splashscreen/img/backgrounds/';
        $background_images = array();
        foreach (glob($background_images_path."*") as $filename)
        {
            $path = '/bundles/splashscreen/img/backgrounds/';
            $name = explode('/', $filename);
            $background_images[$path.$name[count($name)-1]] = $name[count($name)-1];
        }
        $this->data['images'] = $background_images;
        
        //
        //Get day time background images
        //
        $day_time_background_images_path = path('public').'bundles/splashscreen/img/day_time_bkgs/';
        $day_time_background_images = array();
        foreach (glob($day_time_background_images_path."*") as $filename)
        {
            $path = '/bundles/splashscreen/img/day_time_bkgs/';
            $name = explode('/', $filename);
            $day_time_background_images[$path.$name[count($name)-1]] = $name[count($name)-1];
        }
        $this->data['day_time_images'] = $day_time_background_images;

        //
        // Find logo image
        // 
        $logo_path = path('public').'bundles/splashscreen/img/logo/';
        $logo = array();
        foreach (glob($logo_path."logo.*") as $filename)
        {
            $path = '/bundles/splashscreen/img/logo/';
            $name = explode('/', $filename);
            $logo[$path.$name[count($name)-1]] = $name[count($name)-1];
        }
        $this->data['logo'] = $logo;

        return $this->theme->render('splashscreen::backend.imagesbackgrounds.index', $this->data);
    }

    public function post_create()
    {
        $file = null;
        
        $image_name = Opensim\UUID::random();
        
        $action = Input::get('action');
        
        if(isset($action) and $action == 'logo')
        {
            $file = Input::file('logo_image');

            $logo_path = path('public').'bundles/splashscreen/img/logo/';
            
            foreach (glob($logo_path."logo.*") as $filename)
            {
                @File::delete($filename);
            }
            
            $path = $logo_path.$file['name'];

            $logo_parts = explode('.', $file['name']);

            $path = $logo_path;
            $image_name = 'logo.'.$logo_parts['1'];
            $image_path = '/bundles/splashscreen/img/logo/'.$image_name;
            Input::upload('logo_image', $path, $image_name);
            return View::make('splashscreen::backend.imagesbackgrounds.partials.logo', array('image_name' => $image_name, 'path' => $image_path))->render();

            Log::error($path);
        }
        if(isset($action) and $action == 'background')
        {
            $file = Input::file('background');
            $path =  path('public').'bundles/splashscreen/img/backgrounds/';
            $ext = get_file_extension($file['name']);
            $image_name = $image_name.'.'.$ext;
            $image_path = '/bundles/splashscreen/img/backgrounds/'.$image_name;
            Input::upload('background', $path, $image_name);
            return View::make('splashscreen::backend.imagesbackgrounds.partials.image', array('image_name' => $image_name, 'path' => $image_path, 'action' => 'background'))->render();
        }

        if(isset($action) and $action == 'daytimebkg')
        {
            $file = Input::file('daytimebkg');
            $path =  path('public').'bundles/splashscreen/img/day_time_bkgs/';
            $image_name = $file['name'];
            $image_path = '/bundles/splashscreen/img/day_time_bkgs/'.$image_name;
            Input::upload('daytimebkg', $path, $image_name);
            return View::make('splashscreen::backend.imagesbackgrounds.partials.day_time_bkgs', array('image_name' => $image_name, 'path' => $image_path, 'action' => 'daytimebkg'))->render();
        }
    }

    public function delete_destroy($action, $image_name)
    {
        $path = null;
        switch ($action) {
            case 'logo':
                $path = path('public').'bundles/splashscreen/img/logo/';
                break;
            case 'background':
                $path = path('public').'bundles/splashscreen/img/backgrounds/';
                break;
            case 'daytimebkg':
                $path = path('public').'bundles/splashscreen/img/day_time_bkgs/';
                break;
        }

        if( !is_null($path))
        {
            File::delete($path.$image_name);
        }

        if(Request::ajax())
        {
            $image_id = explode('.', $image_name);

            $data = array(
                'flash_message'    => array(
                    'message_type' => 'success',
                    'messages'     => array(__('splashscreen::lang.Image was successfully removed')->get(ADM_LANG)),
                ),
                'hide'           => array(
                    'identifier' => '#'.$image_id['0'],
                ),
            );
            return json_encode($data);
        }

        $this->data['message_type'] = 'success';
        $this->data['message']      = ___('splashscreen::lang.Image was successfully removed')->get(ADM_LANG);
        return Redirect::to(ADM_URI.'/'.'splashscreen/images_backgrounds')->with($this->data);
    }
}