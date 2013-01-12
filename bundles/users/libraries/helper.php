<?php namespace Users;

class Helper {
    
    public static function generate_uuid()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
    }

    public static function hash_password($password)
    {
        $salt = md5(static::generate_uuid());
        $hash = md5(md5($password).':'.$salt);
        return array('hash' => $hash, 'salt' => $salt);
    }
}