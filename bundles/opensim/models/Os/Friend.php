<?php namespace Opensim\Model\Os;

use Eloquent;

class Friend extends Eloquent {
    
    public static $connection = 'opensim';
    public static $table      = 'Friends';
    public static $timestamps = false;
}