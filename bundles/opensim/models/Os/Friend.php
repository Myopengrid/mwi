<?php namespace Opensim\Model\Os;

use Eloquent;

class Friend extends Eloquent {
    
    public static $connection = 'opensim';
    public static $table      = 'friends';
    public static $timestamps = false;
}