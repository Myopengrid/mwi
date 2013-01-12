<?php

/*
|--------------------------------------------------------------------------
| Forms Library
|--------------------------------------------------------------------------
|
| Map Forms Library using PSR-0 standard namespace. 
*/

Autoloader::namespaces(array(
    'Themes\Model' => Bundle::path('themes').'models'.DS,
    'Themes'       => Bundle::path('themes').'libraries'.DS,
));

IoC::singleton('Theme', function()
{
    $config = array(
        'theme_path' => '',
    );
    
    return $theme = new Themes\Theme('base', $config);
});