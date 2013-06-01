<?php namespace Opensim;

class UUID {

    const ZERO = '00000000-0000-0000-0000-000000000000';

    public static function random()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
    }

    public static function is_valid($uuid)
    {
        // Adds empty array as third parameter 
        // for compatibility with php 5.3
        // Thanks GwynethLlewelyn
        $dummy = array();
        return (bool)preg_match('#^[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}$#', $uuid, $dummy);
    }
}