<?php namespace Pages;

use Laravel\Auth;
use Laravel\IOC;
use Laravel\Redirect;

class Page {
    
    public $theme;

    public $data;

    public $page_slug;

    public function __construct($page_slug, $data = array())
    {
        $this->theme     = IOC::resolve('Theme');
        $this->page_slug = $page_slug;
        $this->data      = $data;
    }

    public static function make($page_slug, $data = array())
    {
        return new static($page_slug, $data);
    }

    public function render($slug = null, $check_restrictions = true)
    {
        if($slug == null)
        {
            $slug = $this->page_slug;
        }

        if($slug == '/' or empty($slug))
        {
            $slug = 'home';
        }

        if($slug == '404')
        {
            //page not found. Do we have a 404 page? really? :)
            $page = Model\Page::where('slug', '=', '404')->first();
            if(isset($page) and count($page) > 0)
            {
                $this->data['meta_title']       = $page->title;
                $this->data['meta_description'] = $page->meta_description;
                $this->data['meta_keywords']    = $page->meta_keywords;
                $this->data['page_content'] = $page->body;
                return $this->theme->render('pages::frontend.page', $this->data);
            }
            else
            {
                // How embarrassing we dont have a 404 page :)
                // Return default framework 404
                return Response::error('404');
            } 
        }

        $page = Model\Page::where('slug', '=', $slug)->first();

        if( !isset($page) or empty($page) )
        {
            $page = Model\Page::where('slug', '=', '404')->first();
        }

        $page_access = explode(',', $page->restricted_to);

        if(Restriction::passes($page_access, new Auth))
        {
            if(isset($page) and count($page) > 0)
            {
                $this->data['meta_title']       = $page->title;
                $this->data['meta_description'] = $page->meta_description;
                $this->data['meta_keywords']    = $page->meta_keywords;
                $this->data['page_content'] = $page->body;
                return $this->theme->render('pages::frontend.page', $this->data);
            }
            else
            {
                //page not found. Do we have a 404 page? really? :)
                $page = Model\Page::where('slug', '=', '404')->first();
                if(isset($page) and count($page) > 0)
                {
                    return Redirect::to('404');
                }
                else
                {
                    // How embarrassing we dont have a 404 page :)
                    // Return default framework 404
                    return Response::error('404');
                } 
            }
        }
        else
        {
            // not allowed to view page
            //page not found. Do we have a 404 page? really? :)
            $page = Model\Page::where('slug', '=', '404')->first();
            if(isset($page) and count($page) > 0)
            {
                return Redirect::to('404');
            }
            else
            {
                // How embarrassing we dont have a 404 page :)
                // Return default framework 404
                return Response::error('404');
            } 
        }
    }
}