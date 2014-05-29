<?php namespace Opensim\Model\Os;

use Eloquent;

class Griduser extends Eloquent {
    
    public static $connection = 'opensim';
    public static $table      = 'griduser';
    public static $timestamps = false;
}