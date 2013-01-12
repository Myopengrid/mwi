<?php namespace Navigation;

class Link {
    
    public $id;
    
    public $li_id;

    public $url;

    public $target;

    public $class;
    
    public $rel;
    
    public $title;

    public $type;
    
    public $parent_id;

    public $children;

    public function __construct()
    {

    }

    public static function make()
    {
        return new static();
    }

    public function render()
    {

    }

}