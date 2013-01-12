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