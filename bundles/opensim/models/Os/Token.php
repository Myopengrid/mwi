<?php namespace Opensim\Model\Os;

use Eloquent;

class Token extends Eloquent {
    
    public static $connection = 'opensim';
    public static $table      = 'tokens';
    public static $timestamps = false;
}