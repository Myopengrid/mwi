<?php namespace Opensim\Model\Os;

use Eloquent;

class Avatar extends Eloquent {

    public static $connection = 'opensim';
    public static $table      = 'Avatars';
    public static $timestamps = false;
}