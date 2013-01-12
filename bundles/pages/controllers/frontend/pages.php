<?php

class Pages_Frontend_Pages_Controller extends Public_Controller {


    public function __construct()
    {
        parent::__construct();
    }

    public function get_index($url)
    {
        return Pages\Page::make($url, $this->data)->render();
    }
}