<?php

class Navigation_Schema_Task {

    public function __construct()
    {
        Bundle::register('modules');
        Bundle::start('modules');
        
        Bundle::register('navigation');
        Bundle::start('navigation');

        Bundle::register('pages');
        Bundle::start('pages');
    }

    public function install()
    {   
        $module = Modules\Model\Module::where_slug('navigation')->first();
        $home_page = Pages\Model\Page::where_slug('home')->first();
        
        $header = array(

            'title'     => 'Header',
            'slug'      => 'header',
            'module_id' => $module->id,
            'is_core'   => '1',
        );
        $header = Navigation\Model\Group::create($header);

        // Add links to header navigation group
        $header_home_link = array(
            'title'         => 'Home',
            'module_id'     => $module->id,
            'page_id'       => isset($home_page->id) ? $home_page->id : '1',
            'group_id'      => $header->id,
            'parent'        => '0',
            'link_type'     => 'page',
            'url'           => '',
            'uri'           => '',
            'target'        => '',
            'order'         => '1',
            'restricted_to' => '0',
            'class'         => '',
            'is_core'       => '1',
        );
        $header_link = Navigation\Model\Link::create($header_home_link);

        $about_us = Pages\Model\Page::where_slug('about-us')->first();
        $header_about_us_link = array(
            'title'         => 'About Us',
            'module_id'     => $module->id,
            'page_id'       => isset($about_us->id) ? $about_us->id : '3',
            'group_id'      => $header->id,
            'parent'        => '0',
            'link_type'     => 'page',
            'url'           => '',
            'uri'           => '',
            'target'        => '',
            'order'         => '1',
            'restricted_to' => '0',
            'class'         => '',
            'is_core'       => '1',
        );
        $header_a_us_link = Navigation\Model\Link::create($header_about_us_link);

        $sidebar = array(

            'title'     => 'Sidebar',
            'slug'      => 'sidebar',
            'module_id' => $module->id,
            'is_core'   => '1',
        );
        $sidebar = Navigation\Model\Group::create($sidebar);

        // Add links to sidebar navigation group
        $sidebar_home_link = array(
            'title'         => 'Home',
            'module_id'     => $module->id,
            'page_id'       => isset($home_page->id) ? $home_page->id : '1',
            'group_id'      => $sidebar->id,
            'parent'        => '0',
            'link_type'     => 'page',
            'url'           => '',
            'uri'           => '',
            'target'        => '',
            'order'         => '1',
            'restricted_to' => '0',
            'class'         => '',
            'is_core'       => '1',
        );
        $sidebar_link = Navigation\Model\Link::create($sidebar_home_link);

        $footer = array(

            'title'     => 'Footer',
            'slug'      => 'footer',
            'module_id' => $module->id,
            'is_core'   => '1',
        );
        $footer = Navigation\Model\Group::create($footer);

        // Add links to footer navigation group
        $footer_home_link = array(
            'title'         => 'Home',
            'module_id'     => $module->id,
            'page_id'       => isset($home_page->id) ? $home_page->id : '1',
            'group_id'      => $footer->id,
            'parent'        => '0',
            'link_type'     => 'page',
            'url'           => '',
            'uri'           => '',
            'target'        => '',
            'order'         => '1',
            'restricted_to' => '0',
            'class'         => '',
            'is_core'       => '1',
        );
        $footer_link = Navigation\Model\Link::create($footer_home_link);

        $footer_about_us_link = array(
            'title'         => 'About Us',
            'module_id'     => $module->id,
            'page_id'       => isset($about_us->id) ? $about_us->id : '3',
            'group_id'      => $footer->id,
            'parent'        => '0',
            'link_type'     => 'page',
            'url'           => '',
            'uri'           => '',
            'target'        => '',
            'order'         => '1',
            'restricted_to' => '0',
            'class'         => '',
            'is_core'       => '1',
        );
        $footer_a_us_link = Navigation\Model\Link::create($footer_about_us_link);
        
    }

    public function uninstall()
    {
        //
        // REMOVE NAVIGATION GROUPS
        // 
        $header = Navigation\Model\Group::where_slug('header')->first();
        if(isset($header) and !empty($header))
        {
            $header->delete();
        }

        $sidebar = Navigation\Model\Group::where_slug('sidebar')->first();
        if(isset($sidebar) and !empty($sidebar))
        {
            $sidebar->delete();
        }

        $footer = Navigation\Model\Group::where_slug('footer')->first();
        if(isset($footer) and !empty($footer))
        {
            $footer->delete();
        }
    }

    public function __destruct()
    {
        Bundle::disable('navigation');
        Bundle::disable('modules');
        Bundle::disable('pages');
    }
}