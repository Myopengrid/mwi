<?php namespace Mwi;

class Core {

    public static function random_uuid()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
    }

    public static function keygen($length = 10)
    {
        $key = '';
        list($usec, $sec) = explode(' ', microtime());
        mt_srand((float) $sec + ((float) $usec * 100000));
        
        $inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

        for($i=0; $i<$length; $i++)
        {
            $key .= $inputs{mt_rand(0,61)};
        }
        return $key;
    }
}