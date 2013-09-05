<?php

// Check if opensim database is 
// configured before enable listners
$db_is_ready = Config::get('settings::core.passes_db_settings');

if((bool)$db_is_ready)
{
    Event::listen('users.created', function($user, $customAvatar = 'ruth')
    {

        $account = \Opensim\Model\Os\UserAccount::where_FirstName($user->avatar_first_name)
                                                   ->where_LastName($user->avatar_last_name)
                                                   ->where_PrincipalID($user->uuid)->first();

        if(is_null($account))
        {
            $account = new \Opensim\Model\Os\UserAccount;
            $opensim_account = $account->create_account($user, $customAvatar);
            Log::debug('Opensim creating new account.');
        }
        else
        {
            Log::error('Opensim event: create account for ' . $account->firstname . ' ' . $account->lastname . ' . Account already exists.');
        }
    });

    Event::listen('users.updated', function($user)
    {
        $account = \Opensim\Model\Os\UserAccount::where_PrincipalID($user->uuid)->first();

        if( !is_null($account) )
        {
            $account = new \Opensim\Model\Os\UserAccount;
            $opensim_account = $account->update_account($user);
        }
        else
        {
            Log::error('Opensim event: update account for [' . $user->uuid . '] does not exist.');
        }
    });

    Event::listen('users.updated_many', function($user_list)
    {
        $users = Users\Model\User::where_in('id', $user_list)->get();
        $users_uuids = array();
        foreach ($users as $user) 
        {
            $users_uuids[$user->uuid] = $user;
        }

        $user_accounts = Opensim\Model\Os\UserAccount::where_in('PrincipalID', array_keys($users_uuids))->get();
        foreach ($user_accounts as $user_account) 
        {
            if(isset($users_uuids[$user_account->principalid]))
            {
                $user_account->update_account($users_uuids[$user_account->principalid]);
            }
        }
    });

    Event::listen('users.deleted', function($user)
    {
        $account = new \Opensim\Model\Os\UserAccount;
        $account = $account->destroy_account($user);
    });

    Event::listen('users.deleted_many', function($user_id_list, $users_uuid_list)
    {
        if( !empty($users_uuid_list))
        {
            $user_accounts = Opensim\Model\Os\UserAccount::where_in('PrincipalID', array_keys($users_uuid_list))->get();
            foreach ($user_accounts as $user_account) 
            {
                if(isset($users_uuid_list[$user_account->principalid]))
                {
                    $user_account->destroy_account($users_uuid_list[$user_account->principalid]);
                }
            }
        }
    });
    
    // Remove old regions
    Event::listen('app.cron', function()
    {
        $days = Config::get('settings::core.opensim_remove_old_regions');
        if(isset($days) and $days > 0)
        {
            $days_ago = strtotime(-$days.' days');
            $regions = DB::connection('opensim')
                          ->table('regions')
                          ->where('last_seen', '<', $days_ago)
                          ->take(100)
                          ->delete();
        }
    });
}

