<?php namespace Opensim\Model\Os;

use Eloquent;
use Opensim\UUID as UUID;
use Laravel\Event;
use Laravel\Log;

class UserAccount extends Eloquent {

    public static $connection = 'opensim';
    public static $table      = 'UserAccounts';
    public static $timestamps = false;
    public static $key        = 'PrincipalID';

    public function inventoryfolders()
    {
        return $this->has_many('Opensim\Model\Os\InventoryFolder', 'agentID');
    }

    public function create_account($user, $default_avatar_name = 'ruth')
    {
        // Check if user already exists
        $user_account = self::where_PrincipalID($user->uuid)->first();
        if( !isset($user_account) )
        {
            $ServiceURLs  = 'HomeURI= GatekeeperURI= InventoryServerURI= AssetServerURI=';

            $user_account = new self;
            
            $user_account->PrincipalID  = $user->uuid;
            $user_account->ScopeID      = UUID::ZERO;
            $user_account->FirstName    = $user->avatar_first_name;
            $user_account->LastName     = $user->avatar_last_name;
            $user_account->Email        = $user->email;
            $user_account->ServiceURLs  = $ServiceURLs;
            $user_account->Created      = time();
            $user_account->UserLevel    = 0;
            $user_account->UserFlags    = 0;
            $user_account->UserTitle    = '';
            
            $user_account->save();
        }
        
        // Set passwords
        $account_auth = Auth::where_UUID($user->uuid)->first();
        // Check if there is no 
        // password already set for this account
        if( !isset($account_auth) )
        {
            $account_auth = new Auth;

            if($user->status == 'active')
            {
                $account_auth->passwordHash = $user->hash;
                $account_auth->passwordSalt = $user->salt;
            }
            else
            {
                $account_auth->passwordHash = $user->status;
                $account_auth->passwordSalt = $user->status;
            }

            $account_auth->UUID         = $user->uuid;
            $account_auth->webLoginKey  = UUID::ZERO;
            $account_auth->accountType  = 'UserAccount';

            $account_auth->save();
        }

        // Set home location
        $home_location = \Settings\Model\Setting::where_module_slug('opensim')->where_slug('opensim_home_location')->first();

        if(isset($home_location->value) and \Opensim\UUID::is_valid($home_location->value))
        {
            $home_location_data = array(
                'UserID'       => $user->uuid,
                'HomeRegionID' => $home_location->value,
                'HomePosition' => '<0,0,0>',
                'HomeLookAt'   => '<0,0,0>',
                'LastRegionID' => $home_location->value,
                'LastPosition' => '<128,128,22>',
                'LastLookAt'   => '<0,1,0>',
                'Online'       => 'False',
                'Login'        => $user->created_at->getTimestamp(),
                'Logout'       => $user->created_at->getTimestamp(),
            );

            Griduser::insert($home_location_data);
        }

        // create inventory
        $account_inventory = InventoryFolder::where_agentID($user->uuid)->get();
        if( !isset($account_inventory) or empty($account_inventory))
        {
            $account_inventory = new InventoryFolder;
            $account_inventory = $account_inventory->create_inventory($user->uuid);
        }
        else
        {
            $acc_inv = array();
            foreach ($account_inventory as $folder_name => $folder) 
            {
                $acc_inv[$folder->foldername] = array(
                    'folderName'     => $folder->foldername,
                    'type'           => $folder->type,
                    'version'        => $folder->version,
                    'folderID'       => $folder->folderid,
                    'agentID'        => $folder->agentid,
                    'parentFolderID' => $folder->parentfolderid,
                );
            }
            $account_inventory = $acc_inv;
        }

        // Load avatar initial items items
        $items = Event::until('opensim.load.avatar.items', array($default_avatar_name, $user, $account_inventory));
        if(is_null($items))
        {
            $items = \Opensim\load_ruth_items($user->uuid, $account_inventory);
        }

        if(isset($items) and !empty($items))
        {
            $invento = InventoryItem::insert($items);
        }
        

        // Load Avatar appearance
        $items_array = Event::until('opensim.load.avatar.appearance', array($default_avatar_name, $user));
        if(is_null($items_array))
        {
            $items_array = \Opensim\load_ruth_appearance($user->uuid);
        }

        // Save appearance
        $avatar_apearance = \Opensim\Model\Os\Avatar::insert($items_array);
    }

    public function update_account($user)
    {
        $user_account = self::where_PrincipalID($user->uuid)->first();

        if( !is_null($user_account) )
        {
            $account_auth = Auth::where_UUID($user->uuid)->first();
            if( !is_null($account_auth))
            {
                if($user->status == 'active')
                {
                    // update password
                    $account_auth_update['passwordHash'] = $user->hash;
                    $account_auth_update['passwordSalt'] = $user->salt;
                }
                else
                {
                    // set password to blank
                    $account_auth_update['passwordHash'] = $user->status;
                    $account_auth_update['passwordSalt'] = $user->status;
                }
                
                $account_auth->where('UUID', '=', $user->uuid)->update($account_auth_update);
            }
            else
            {

                Log::error('UserAccount Model: [update_account] failed. Authentication passwords does not exist for UUID: ['. $user->uuid .'] First Name: ['. $user->avatar_first_name.'] Last Name: ['. $user->avatar_last_name.'].');
            }
            
            // Update user account
            $user_account_update['FirstName'] = $user->avatar_first_name;
            $user_account_update['LastName']  = $user->avatar_last_name;
            $user_account_update['Email']     = $user->email;
            $user_account->where('PrincipalID', '=', $user->uuid)->update($user_account_update);
        }
        else
        {
            Log::error('UserAccount Model: [update_account] failed. Account does not exist for UUID: ['. $user->uuid .'] First Name: ['. $user->avatar_first_name.'] Last Name: ['. $user->avatar_last_name.'].');
        }
    }

    public function destroy_account($user)
    {
        // Authentication
        $account_auth = \Opensim\Model\Os\Auth::where_UUID($user->uuid);
        $account_auth->delete();

        // Avatars
        $account_avatar = \Opensim\Model\Os\Avatar::where_PrincipalID($user->uuid);
        $account_avatar->delete();

        // Friends
        $account_friend = \Opensim\Model\Os\Friend::where_PrincipalID($user->uuid);
        $account_friend->delete();

        // GridUser
        $account_griduser = \Opensim\Model\Os\Griduser::where_UserID($user->uuid);
        $account_griduser->delete();

        // InventoryFolders
        $account_inventoryfolder = \Opensim\Model\Os\InventoryFolder::where_agentID($user->uuid);
        $account_inventoryfolder->delete();

        // InventoryItems
        $account_inventoryitem = \Opensim\Model\Os\InventoryItem::where_avatarID($user->uuid);
        $account_inventoryitem->delete();

        // Presence
        $account_presence = \Opensim\Model\Os\Presence::where_UserID($user->uuid);
        $account_presence->delete();

        // Tokens
        $account_token = \Opensim\Model\Os\Token::where_UUID($user->uuid);
        $account_token->delete();

        // UserAccounts
        $account_useraccount = \Opensim\Model\Os\UserAccount::where_PrincipalID($user->uuid);
        $account_useraccount->delete();
    }
}