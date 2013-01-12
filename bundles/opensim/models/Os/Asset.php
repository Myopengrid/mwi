<?php namespace Opensim\Model\Os;

use Eloquent;

class Asset extends Eloquent {
    
    public static $connection = 'opensim';
    public static $table      = 'assets';
    public static $timestamps = false;
}