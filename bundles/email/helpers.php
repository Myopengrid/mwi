<?php namespace Email;

if( !function_exists('get_email_templates') ) 
{
    /**
     * Used by registration settings form
     * to retrieve all email templates and 
     * populate form select input
     * 
     * @return array array will all available email templates
     */
    function get_email_templates()
    {
        $all_email_templates = \Email\Model\Template::all();

        $templates = array();

        foreach ($all_email_templates as $template) 
        {
            $templates[$template->id] = $template->name.' (' .$template->lang.')';
        }

        return ($templates);
    }
}