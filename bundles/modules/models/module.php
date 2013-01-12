<?php namespace Modules\Model;

use \Eloquent;

class Module extends Eloquent {

    public static $timestamps = true;

    public function permissions()
    {
          return $this->has_many('Permissions\Model\Permission');
    }
}