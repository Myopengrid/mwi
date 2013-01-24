<?php

class Pages_Schema_Task {

    public function __construct()
    {
        Bundle::register('pages');
        Bundle::start('pages');
    }

    public function install()
    {   
        $home_page = array(

            'title'            => 'Home',
            'slug'             => 'home',
            'body'             => File::get(dirname(__FILE__).DS.'data'.DS.'home_body.html'),
            'uri'              => '',
            'parent_id'        => '0',
            'revision_id'      => '0',
            'layout_id'        => '0',
            'class'            => '',
            'meta_title'       => '',
            'meta_keywords'    => '',
            'meta_description' => '',
            'rss_enabled'      => '0',
            'comments_enabled' => '0',
            'status'           => 'live',
            'restricted_to'    => '0',
            'is_home'          => '1',
            'is_core'          => '1',
            'strict_uri'       => '1',
            'order'            => '1',
        );
        $home = Pages\Model\Page::create($home_page);

        $not_found = array(

            'title'            => 'Page missing (404)',
            'slug'             => '404',
            'body'             => File::get(dirname(__FILE__).DS.'data'.DS.'404_body.html'),
            'uri'              => '',
            'parent_id'        => '0',
            'revision_id'      => '0',
            'layout_id'        => '0',
            'class'            => '',
            'meta_title'       => '',
            'meta_keywords'    => '',
            'meta_description' => '',
            'rss_enabled'      => '0',
            'comments_enabled' => '0',
            'status'           => 'live',
            'restricted_to'    => '0',
            'is_home'          => '0',
            'is_core'          => '1',
            'strict_uri'       => '1',
            'order'            => '2',
        );
        $n_found = Pages\Model\Page::create($not_found);

        $about_us = array(

            'title'            => 'About Us',
            'slug'             => 'about-us',
            'body'             => File::get(dirname(__FILE__).DS.'data'.DS.'about_us_body.html'),
            'uri'              => '',
            'parent_id'        => '0',
            'revision_id'      => '0',
            'layout_id'        => '0',
            'class'            => '',
            'meta_title'       => '',
            'meta_keywords'    => '',
            'meta_description' => '',
            'rss_enabled'      => '0',
            'comments_enabled' => '0',
            'status'           => 'live',
            'restricted_to'    => '0',
            'is_home'          => '0',
            'is_core'          => '1',
            'strict_uri'       => '1',
            'order'            => '2',
        );
        $a_us = Pages\Model\Page::create($about_us);
        
    }

    public function uninstall()
    {
        //
        // REMOVE PAGES
        // 
        $home = Pages\Model\Page::where_slug('home')->first();
        if(isset($home) and !empty($home))
        {
            $home->delete();
        }

        $not_found = Pages\Model\Page::where_slug('404')->first();
        if(isset($not_found) and !empty($not_found))
        {
            $not_found->delete();
        }

        $about_us = Pages\Model\Page::where_slug('about-us')->first();
        if(isset($about_us) and !empty($about_us))
        {
            $about_us->delete();
        }
    }

    public function __destruct()
    {
        Bundle::disable('pages');
    }
}