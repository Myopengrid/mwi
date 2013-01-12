<?php namespace Opensim\Model\Os;

use Eloquent;

class Auth extends Eloquent {
    
    public static $connection = 'opensim';
    public static $table      = 'auth';
    public static $timestamps = false;
}