<?php

/**
 * Listen for the menu builder event.
 * This listener modify all navigation
 * groups that have links to pages,
 * if the link in the menu is a link 
 * for a page, it will be altered so
 * the link will redirect to the right
 * location
 */
Event::listen('menu.make.navigation', function($menu_links)
{
    if(isset($menu_links) and !empty($menu_links))
    {
        foreach ($menu_links as &$link) 
        {
            // If this link is a page link
            // add the page uri before its slug.
            if(isset($link['link_type']) and $link['link_type'] == 'page')
            {
                $link['url'] = 'page/'.$link['url'];
            }
        }

        return $menu_links;
    }
    else
    {
        Log::error("Pages event listener for [menu.make.navigation] got an empty link set.");
        return $menu_links;
    }

});