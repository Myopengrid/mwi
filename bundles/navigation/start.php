<?php

Autoloader::namespaces(array(
    'Navigation\Model' => Bundle::path('navigation').'models'.DS,
    'Navigation'       => Bundle::path('navigation').'libraries'.DS,
));

IoC::register('Menu', function()
{   
    return $menu = new Navigation\Menu;
});

/*
|--------------------------------------------------------------------------
| Load Module Helpers file
|--------------------------------------------------------------------------
|
| Load all module magic methods and make them 
| available globally in the application
|
*/
include(__DIR__.DS.'helpers.php');