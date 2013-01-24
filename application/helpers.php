<?php

/**
 * Get the file extension from a string
 *
 * @param  string  $file_name
 * @return string
 */
function get_file_extension($file_name) 
{
  return substr(strrchr($file_name,'.'),1);
}

/**
 * Temporary until forms bundle be 
 * implemented
 */
Form::macro('mwi_field', function($field)
{
    $bundle = Request::route()->bundle;
    $type = $field->type;

    if ( !empty($field->options) )
    {
        $options = array();

        if (substr($field->options, 0, 5) == 'func:')
        {
            $method_string = substr($field->options, 5);
            $method_parts = explode(',', $method_string);
            $method = $method_parts['0'];
            unset($method_parts['0']);

            if (is_callable($method))
            {
                if(count($method_parts) > 1)
                {
                    $params = implode(',', $method_parts);
                    $field->options = call_user_func($method, $params);
                }
                else
                {
                    $field->options = call_user_func($method);
                }
            }
        }
    }

    if($type == 'token')
    {
        return Form::token();
    }

    elseif($type == 'password')
    {
        return Form::password($field->slug, array('name' => $field->slug));
    }

    elseif($type == 'textarea')
    {
        return Form::textarea($field->slug, $field->value, array('name' => $field->slug));
    }

    elseif($type == 'select')
    {
        $options_lang = array();

        if(is_array($field->options))
        {
            foreach ($field->options as $key => $value) 
            {

                $options_lang[$key] = Lang::line($bundle.'::lang.'.$value)->get(ADM_LANG);
            }
        }
        else
        {
            $options = json_decode($field->options, true);
            if(isset($options) and !empty($options))
            {
                foreach ($options as $key => $value) 
                {
                    $options_lang[$key] = Lang::line($bundle.'::lang.'.$value)->get(ADM_LANG);
                }
            }
        }
        return Form::select($field->slug, $options_lang, $field->value);
    }

    elseif($type == 'radio' || $type == "checkbox")
    {
        return Form::$type($field->slug, $field->value, false, array('name' => $field->slug));
    }
    else
    {
        return Form::$type($field->slug, $field->value, array('name' => $field->slug));
    }
});

/**
 * Search for a partial string match on array.
 *
 * @param  array  $array
 * @param  string $search_text
 * @param  bool   $json
 * @return mixed  $result
 */
function array_search_string($array, $search_text, $json = false)
{
    $result = array();
    
    array_filter($array, function($word) use ($search_text, &$result) 
    {
        if(strpos($word, $search_text) !== false)
        {
            $result[] = $word;
        }
    });

    if($json)
    {
        return json_encode($result);
    }
    else
    {
        return $result;
    }
}

function file_extension($path)
{
    return pathinfo($path, PATHINFO_EXTENSION);
}