<?php namespace Navigation;

use Laravel\Log;

class Validator extends \Laravel\Validator {

    public function validate_link_type($attribute, $value, $parameters)
    {
        if(!$this->validate_required($this->attributes['link_type'], $this->attributes['link_type']))
        {
            $this->messages['link_type'] = 'The link type field is required.';
            return false;
        }

        if($value == 'url')
        {
            
            if($this->validate_required($this->attributes['url'], $this->attributes['url']))
            {
                // Check for valid url
                $this->messages['link_type'] = 'The url field must be a valid url.';
                return $this->validate_url($this->attributes['url'], $this->attributes['url']);
            }
            else
            {
                $this->messages['link_type'] = 'The url field is required.';
                return false;
            }
        }
        
        if($value == 'uri')
        {
            
            if($this->validate_required($this->attributes['uri'], $this->attributes['uri']))
            {
                // Check for valid uri
                $this->messages['link_type'] = 'The uri field must be a valid uri.';
                return preg_match('/^([-a-z0-9_\/-])+$/i', $this->attributes['uri']);
            }
            else
            {
                $this->messages['link_type'] = 'The uri field is required';
                return false;
            }
        }
        
        if($value == 'module')
        {
            
            if($this->validate_required($this->attributes['module_id'], $this->attributes['module_id']))
            {
                // the module id is generated in the form
                // it should never be something different then an integer
                // but lets check it just in case
                $is_valid_integer = $this->validate_integer($this->attributes['module_id'], $this->attributes['module_id']);
                if($is_valid_integer)
                {
                    return true;
                }
                else
                {
                    // this is really bad! maybe cross post?
                    // log the error
                    $this->messages['link_type'] = 'This module is invalid or cannot be selected.';
                    Log::write('error', 'Tried to create a link selecting a invalid module ['.$this->attributes['module_id'].']');
                    return false;
                }
            }
            else
            {
                $this->messages['link_type'] = 'Please select a module to link to.';
                return false;
            }
        }
        
        if($value == 'page')
        {
            if($this->validate_required($this->attributes['page_id'], $this->attributes['page_id']))
            {
                // the page_id is generated in the form
                // it should never be something different then an integer
                // but lets check it just in case
                $is_valid_integer = $this->validate_integer($this->attributes['page_id'], $this->attributes['page_id']);
                if($is_valid_integer)
                {
                    return true;
                }
                else
                {
                    // this is really bad! maybe cross post?
                    // log the error
                    $this->messages['link_type'] = 'This page is invalid or cannot be selected.';
                    Log::write('error', 'Tried to create a link selecting a invalid page ['.$this->attributes['page_id'].']');
                    return false;
                }
            }
            else
            {
                $this->messages['link_type'] = 'Please select a page to link to.';
                return false;
            }
            
        }
        $this->messages['link_type'] = 'Unknown';
        return false;
    }
}