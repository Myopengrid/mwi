<?php

class Splashscreen_Frontend_Flashnews_Controller extends Public_Controller {
    
    public function get_index($flash_news_handler)
    {
        if(empty($flash_news_handler))
        {
            $this->data['meta_title'] = 'Flash Messages';
            $news_handler = Config::get('settings::core.splashscreen_flash_news_handler');
            $this->data['news_path'] = URL::base().'/'.$news_handler.'/';
            $this->data['news'] = Splashscreen\Model\News::where_is_enabled(1)->order_by('created_at', 'desc')->get();
            return $this->theme->render('splashscreen::frontend.flashnews.index', $this->data);
        }
        else
        {
            $the_news = Splashscreen\Model\News::where_slug($flash_news_handler)->first();
            if(isset($the_news))
            {
                $this->data['the_news'] = $the_news;
                return $this->theme->render('splashscreen::frontend.flashnews.the_news', $this->data);
            }
            else
            {
                return Redirect::to('404');
            }
        }
    }
}