<?php namespace Settings;

if( !function_exists('get_available_languages') ) 
{
    /**
     * Used by settings module settings form
     * to retrieve all available languages and 
     * populate form select input
     * 
     * @return array array will all available languages
     */
    function get_available_languages()
    {
        $languages = config::get('settings::core.available_languages');

        return json_decode($languages, true);
    }
}

if( !function_exists('get_available_time_zones') ) 
{
    /**
     * Used by settings module settings form
     * to retrieve all available time zones and 
     * populate form select input
     * 
     * @return array all available languages
     */
    function get_available_time_zones()
    {
        $time_zone_list = \DateTimeZone::listIdentifiers(2047);
        $time_zones = array();
        foreach ($time_zone_list as $key => $value) 
        {
           $time_zones[$value] = $value; 
        }

        return $time_zones;
    }
}