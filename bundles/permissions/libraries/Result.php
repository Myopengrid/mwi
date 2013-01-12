<?php namespace Permissions;


class Result {

    public $message;

    public $message_type;

    public $username;

    public $group;

    public $role;

    public $module;
    
    public $redirect_to;

    public $is_allowed = false;

}