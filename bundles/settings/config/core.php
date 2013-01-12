<?php

$mwi_settings = \DB::table('settings')->get();

$core_settings = array();

foreach ($mwi_settings as $setting)
{
    if(empty($setting->value))
    {
        $core_settings[$setting->slug] = $setting->default;
    }
    else
    {
        $core_settings[$setting->slug] = $setting->value;
    }
}

return $core_settings;