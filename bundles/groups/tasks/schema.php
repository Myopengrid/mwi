<?php

class Groups_Schema_Task {

    public function __construct()
    {
        Bundle::register('groups');
        Bundle::start('groups');
    }

    public function install()
    {   
        $admin = array(

            'name'        => 'Administrator',
            'slug'        => 'admin',
            'description' => 'Administration group',
            'is_core'     => '1',
        );
        $admin = Groups\Model\Group::create($admin);

        $users = array(

            'name'        => 'Users',
            'slug'        => 'users',
            'description' => 'Basic users group',
            'is_core'     => '1',
        );
        $users = Groups\Model\Group::create($users);
        
    }

    public function uninstall()
    {
        //
        // REMOVE GROUPS
        // 
        $admin = Groups\Model\Group::where_slug('admin')->first();
        if(isset($admin) and !empty($admin))
        {
            $admin->delete();
        }

        $users = Groups\Model\Group::where_slug('users')->first();
        if(isset($users) and !empty($users))
        {
            $users->delete();
        }
    }

    public function __destruct()
    {
        Bundle::disable('groups');
    }
}