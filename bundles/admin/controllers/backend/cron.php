<?php

class Admin_Backend_Cron_Controller extends Public_Controller {

    public function get_index()
    {
        Event::fire('app.cron');
        return 'Done';
    }
}