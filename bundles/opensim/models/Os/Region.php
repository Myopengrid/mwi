<?php namespace Opensim\Model\Os;

class Region extends \Opensim\Eloquent {
    
    public static $connection = 'opensim';
    public static $table      = 'regions';
    public static $timestamps = false;
    public static $key        = 'owner_uuid';
}