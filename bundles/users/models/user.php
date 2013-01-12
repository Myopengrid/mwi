<?php namespace Users\Model;

use \Eloquent;

class User extends Eloquent {

    public static $key = 'id';

    public function group()
    {

        if(\Bundle::exists('groups'))
        {
            return $this->belongs_to('Groups\Model\Group');
        }
        else
        {
            throw new \Exception('Groups module is not installed. To call users with group you must install the groups module.');
        }

        
    }
}