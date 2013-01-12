<?php namespace Opensim\Model\Os;

use Eloquent;

class Griduser extends Eloquent {
    
    public static $connection = 'opensim';
    public static $table      = 'GridUser';
    public static $timestamps = false;
}