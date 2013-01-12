<?php namespace Groups\Model;

use \Eloquent;

class Group extends Eloquent {

    public static $timestamps = true;

    public function permissions()
    {
        if(\Bundle::exists('permissions'))
        {
            return $this->has_many('Permissions\Model\Permission');
        }
        else
        {
            throw new \Exception('Permissions module is not installed. To call groups with permissions you must install the permissions module.');
        } 
    }

    public function users()
    {
        if(\Bundle::exists('permissions'))
        {
            return $this->has_many('Users\Model\User');
        }
        else
        {
            throw new \Exception('Permissions module is not installed. To call goups with users you must install the permissions module.');
        }  
    }
}