<?php namespace Modules\Model;

use \Eloquent;

class Field extends Eloquent {

    public static $table = 'module_fields';

    public static function get_extra($module_slug)
    {
        $all_fields = self::where('module_slug', '=', $module_slug)->get();
        // Get all groups (Tabs)
        $groups = array();
        foreach($all_fields as $field)
        {
            if(empty($field->value))
            {
                $field->value = $field->default;
            }

            if( ! isset($groups[$field->group]) )
            {
                $groups[$field->group] = \Str::title(str_replace('_', ' ' , $field->group));
            }
            
            // The field option is a function? if it is grab 
            // what is needed func:\Navigation\Groups::title,slug
            if (starts_with($field->value, 'func:'))
            {
                $func = substr($field->value, 5);
                $func_parts = explode('.', $func);
                
                $arr = array('*');
                if(isset($func_parts['1']))
                {
                    $arr = explode(',', $func_parts['1']);
                }
                
                $options_raw = call_user_func($func_parts['0'], $arr );

                // Build options array
                // array('header' => Header) (navigation) example
                $options = array();
                if(count($arr) > 1)
                {
                    foreach ($options_raw as $opt)
                    {
                        $options[$opt->{$arr['0']}] = $opt->{$arr['1']}; 
                    }
                }

                $field->value = $options;
            }
        }
        
        $response = array('groups' => $groups, 'fields' => $all_fields);
        return $response;
    }
}
