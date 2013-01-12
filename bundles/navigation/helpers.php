<?php

function get_nav($menu_nav)
{
    $menu = \IoC::resolve('Menu');
    return $menu->get_nav($menu_nav);
}

function make($items = array(), $html = '', $partial_path = null)
{
    $menu = \IoC::resolve('Menu');
    return $menu->make($items, $html, $partial_path);
}