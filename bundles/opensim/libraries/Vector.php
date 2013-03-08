<?php namespace Opensim;

class Vector {
    
    public static function is_valid($string)
    {
        if(empty($string))
        {
            return false;
        }
        
        $string = trim($string);

        return (bool)preg_match_all('/^\s?<\s?\.?\d+\.?\d*?\s?,\s?\.?\d+\.?\d*?\s?,\s?\.?\d+\.?\d*?\s?\>\s?$/i', $string);
    }
}