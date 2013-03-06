<?php

class Splashscreen_Backend_Flashnews_Controller extends Admin_Controller {

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
            Lang::line('splashscreen::lang.Settings')->get(ADM_LANG)             => URL::base().'/'.ADM_URI.'/splashscreen',
            Lang::line('splashscreen::lang.Logo And Backgrounds')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/splashscreen/images_backgrounds',
            Lang::line('splashscreen::lang.Flash News')->get(ADM_LANG)           => URL::base().'/'.ADM_URI.'/splashscreen/flash_news',
            Lang::line('splashscreen::lang.New Flash News')->get(ADM_LANG)           => URL::base().'/'.ADM_URI.'/splashscreen/flash_news/new',
        );
    }

    public function get_index()
    {
        $this->data['section_bar_active'] = Lang::line('splashscreen::lang.Flash News')->get(ADM_LANG);
        
        $this->data['pagination'] = Splashscreen\Model\News::paginate(10);
        $this->data['news'] = $this->data['pagination']->results;
        

        if(Request::ajax())
        {
            return View::make('splashscreen::backend.flashnews.partials.flash_news_tbody', $this->data);
        }
        

        return $this->theme->render('splashscreen::backend.flashnews.index', $this->data);
    }

    public function get_new()
    {
        $this->data['section_bar_active'] = Lang::line('splashscreen::flashnews.New Flash News')->get(ADM_LANG);
        return $this->theme->render('splashscreen::backend.flashnews.new', $this->data);
    }

    public function post_create()
    {
        $rules = array(
            'name'          => 'required|max:255',
            'slug'          => 'required|max:255|alpha_dash|unique:splashscreen_flash_news',
            'message'       => 'required',
        );

        $validation = Validator::make(Input::all(), $rules)->speaks(ADM_LANG);

        if ($validation->passes())
        {
            $news = new Splashscreen\Model\News;
            $news->title      = Input::get('name');
            $news->slug       = Input::get('slug');
            $news->is_enabled = Input::get('is_enabled');
            $news->message    = Input::get('message');
            $news->save();
            
            Event::fire('splashscreen.flash_news_created', array($news));

            $this->data['message']      = __('splashscreen::lang.New flash news was successfully created')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
            return Redirect::to(ADM_URI.'/splashscreen/flash_news')->with($this->data);
        }
        else
        {
            return Redirect::back()
                            ->with_input()
                            ->with_errors($validation);
        }
    }

    public function get_edit($news_id)
    {
        $this->data['section_bar'] = array(
            Lang::line('splashscreen::lang.Settings')->get(ADM_LANG)             => URL::base().'/'.ADM_URI.'/splashscreen',
            Lang::line('splashscreen::lang.Logo And Backgrounds')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/splashscreen/images_backgrounds',
            Lang::line('splashscreen::lang.Flash News')->get(ADM_LANG)           => URL::base().'/'.ADM_URI.'/splashscreen/flash_news',
            Lang::line('splashscreen::lang.Edit Flash News')->get(ADM_LANG)           => URL::base().'/'.ADM_URI.'/splashscreen/flash_news/'.$news_id.'/edit',
            Lang::line('splashscreen::lang.New Flash News')->get(ADM_LANG)           => URL::base().'/'.ADM_URI.'/splashscreen/flash_news/new',
        );

        $this->data['section_bar_active'] = Lang::line('splashscreen::lang.Edit Flash News')->get(ADM_LANG);

        if( ! ctype_digit($news_id))
        {
            $this->data['message']      = __('splashscreen::flashnews.Invalid flash news id')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/splashscreen/flash_news')->with($this->data);
        }
        
        $news = Splashscreen\Model\News::find($news_id);
        
        if( !isset($news) or empty($news))
        {
            $this->data['message']      = __('splashscreen::flashnews.Could not find flash news to edit')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/splashscreen/flash_news')->with($this->data);
        }

        $this->data['news'] = $news;

        return $this->theme->render('splashscreen::backend.flashnews.edit', $this->data);
    }

    public function put_update($news_id)
    {
        if( !ctype_digit($news_id))
        {
            $this->data['message']      = __('splashscreen::flashnews.Invalid flash news id')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/splashscreen/flash_news')->with($this->data);
        }
        
        $news = Splashscreen\Model\News::find($news_id);
        
        if( !isset($news) or empty($news))
        {
            $this->data['message']      = __('splashscreen::flashnews.Could not find flash news to update')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/splashscreen/flash_news')->with($this->data);
        }

        $rules = array(
            'name'        => 'required|max:255',
            'message'     => 'required|max:255',
            'is_enabled'  => 'required',
        );

        $validation = Validator::make(Input::all(), $rules)->speaks(ADM_LANG);

        if ($validation->passes())
        {
            $news->title      = Input::get('name');
            $news->message    = Input::get('message');
            $news->is_enabled = Input::get('is_enabled');
            $news->save();

            Event::fire('splashscreen.flash_news_updated', array($news));

            $this->data['message']      = __('splashscreen::flashnews.Flash news was successfully updated')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
            return Redirect::to(ADM_URI.'/splashscreen/flash_news')->with($this->data);
        }
        else
        {
            return Redirect::back()
                            ->with_input()
                            ->with_errors($validation);
        }
    }

    public function delete_destroy($news_id)
    {
        $news_ids = Input::get('action_to');
        
        if(isset($news_ids) and !empty($news_ids) and $news_id == '0')
        {
            $flash_news = Splashscreen\Model\News::where_in('id', $news_ids);
            if(isset($flash_news))
            {
                $flash_news->delete();
            }

            Event::fire('splashscreen.flash_news_delete_many', array($flash_news));
            $this->data['message'] = __('splashscreen::flashnews.Flash news were successfully deleted')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
        }
        else
        {
            if(ctype_digit($news_id) and $news_id != 0)
            {
                $news = Splashscreen\Model\News::find($news_id);
                if(isset($news))
                {
                    $news->delete();
                }

                Event::fire('splashscreen.flash_news_delete', array($news));
                $this->data['message'] = __('splashscreen::flashnews.Flash news were successfully deleted')->get(ADM_LANG);
                $this->data['message_type'] = 'success';
            }
            else
            {
                if($news_id == 0)
                {
                    $this->data['message'] = __('splashscreen::flashnews.Please select a flash news to delete')->get(ADM_LANG);
                }
                else
                {
                    $this->data['message'] = __('splashscreen::flashnews.Invalid flash news id')->get(ADM_LANG);
                }
                
                $this->data['message_type'] = 'error';
            }
        }

        if(Request::ajax())
        {
            $json_response = array(
                'flash_message' => array(
                    'message_type' => $this->data['message_type'],
                    'messages'     => array($this->data['message']),
                ),
                'hide' => array(
                    'identifier' => 'tr#flash-news-row-'.$news_id,
                ),
            );
                
            return json_encode($json_response);
        }
        else
        {
            return Redirect::back()->with($this->data);
        }
    }
}