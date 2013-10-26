<?php namespace Opensim\Model\Os;

use Eloquent;

class Presence extends Eloquent {
    
    public static $connection = 'opensim';
    public static $table      = 'presence';
    public static $timestamps = false;
}