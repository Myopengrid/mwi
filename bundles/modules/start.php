<?php

Autoloader::namespaces(array(
    'Modules\Model' => Bundle::path('modules').'models',
    'Modules'       => Bundle::path('modules').'libraries',
));


$bundles = Modules\Model\Module::all();

foreach ($bundles as $key => $module)
{
    Config::set('installed_modules.'.$module->slug, $module->slug);
    
    if($module->enabled)
    {
        Config::set('enabled_modules.'.$module->slug, $module->slug);
        $options = json_decode($module->options, true);
        if($options == null)
        {
            $options = array();
        }

        if($module->slug != 'modules' and $module->slug != 'settings')
        {
            Bundle::register($module->slug, $options );
            Bundle::start($module->slug);

        }
    }
}