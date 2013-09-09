<?php namespace Opensim;

class Vector {
    
    public static function is_valid($string)
    {
        if(empty($string))
        {
            return false;
        }
        
        $string = trim($string);
        $out  = array();

        return (bool)preg_match_all('/^\s?<\s?\.?\d+\.?\d*?\s?,\s?\.?\d+\.?\d*?\s?,\s?\.?\d+\.?\d*?\s?\>\s?$/i', $string, $out);
    }
}