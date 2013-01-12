<?php namespace Opensim\Model\Os;

use Eloquent;

class Presence extends Eloquent {
    
    public static $connection = 'opensim';
    public static $table      = 'Presence';
    public static $timestamps = false;
}