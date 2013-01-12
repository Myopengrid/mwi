<?php namespace Opensim;

use Opensim\UUID as UUID;

function load_ruth_items($user_uuid, $account_inventory)
{
    $items = array(
        'Body Parts' => array(
            'Default Eyes' => array(
                'assetID'                      => '4bb6fa4d-1cd2-498a-a84c-95c1a0e745a7',
                'assetType'                    => \Opensim\AssetType::Bodypart,
                'inventoryName'                => 'Default Eyes',
                'inventoryDescription'         => '',
                'inventoryNextPermissions'     => \Opensim\Permission::PERM_ALL,
                'inventoryCurrentPermissions'  => \Opensim\Permission::PERM_ALL,
                'invType'                      => \Opensim\AssetType::ImageTGA,
                'creatorID'                    => $user_uuid,
                'inventoryBasePermissions'     => \Opensim\Permission::PERM_ALL,
                'inventoryEveryOnePermissions' => \Opensim\Permission::PERM_ALL,
                'salePrice'                    => 0,
                'saleType'                     => 0,
                'creationDate'                 => time(),
                'groupID'                      => UUID::ZERO,
                'groupOwned'                   => 0,
                'flags'                        => 3,
                'inventoryID'                  => UUID::random(),
                'avatarID'                     => $user_uuid,
                'parentFolderID'               => '',
                'inventoryGroupPermissions'    => \Opensim\Permission::PERM_ALL,
            ),
            'Default Hair' => array(
                'assetID'                      => 'd342e6c0-b9d2-11dc-95ff-0800200c9a66',
                'assetType'                    => \Opensim\AssetType::Bodypart,
                'inventoryName'                => 'Default Hair',
                'inventoryDescription'         => '',
                'inventoryNextPermissions'     => \Opensim\Permission::PERM_ALL,
                'inventoryCurrentPermissions'  => \Opensim\Permission::PERM_ALL,
                'invType'                      => \Opensim\AssetType::ImageTGA,
                'creatorID'                    => $user_uuid,
                'inventoryBasePermissions'     => \Opensim\Permission::PERM_ALL,
                'inventoryEveryOnePermissions' => \Opensim\Permission::PERM_ALL,
                'salePrice'                    => 0,
                'saleType'                     => 0,
                'creationDate'                 => time(),
                'groupID'                      => UUID::ZERO,
                'groupOwned'                   => 0,
                'flags'                        => 2,
                'inventoryID'                  => UUID::random(),
                'avatarID'                     => $user_uuid,
                'parentFolderID'               => '',
                'inventoryGroupPermissions'    => \Opensim\Permission::PERM_ALL,
            ),
            'Default Shape' => array(
                'assetID'                      => '66c41e39-38f9-f75a-024e-585989bfab73',
                'assetType'                    => \Opensim\AssetType::Bodypart,
                'inventoryName'                => 'Default Shape',
                'inventoryDescription'         => '',
                'inventoryNextPermissions'     => \Opensim\Permission::PERM_ALL,
                'inventoryCurrentPermissions'  => \Opensim\Permission::PERM_ALL,
                'invType'                      => \Opensim\AssetType::ImageTGA,
                'creatorID'                    => $user_uuid,
                'inventoryBasePermissions'     => \Opensim\Permission::PERM_ALL,
                'inventoryEveryOnePermissions' => \Opensim\Permission::PERM_ALL,
                'salePrice'                    => 0,
                'saleType'                     => 0,
                'creationDate'                 => time(),
                'groupID'                      => UUID::ZERO,
                'groupOwned'                   => 0,
                'flags'                        => 0,
                'inventoryID'                  => UUID::random(),
                'avatarID'                     => $user_uuid,
                'parentFolderID'               => '',
                'inventoryGroupPermissions'    => \Opensim\Permission::PERM_ALL,
            ),
            'Default Skin' => array(
                'assetID'                      => '77c41e39-38f9-f75a-024e-585989bbabbb',
                'assetType'                    => \Opensim\AssetType::Bodypart,
                'inventoryName'                => 'Default Skin',
                'inventoryDescription'         => '',
                'inventoryNextPermissions'     => \Opensim\Permission::PERM_ALL,
                'inventoryCurrentPermissions'  => \Opensim\Permission::PERM_ALL,
                'invType'                      => \Opensim\AssetType::ImageTGA,
                'creatorID'                    => $user_uuid,
                'inventoryBasePermissions'     => \Opensim\Permission::PERM_ALL,
                'inventoryEveryOnePermissions' => \Opensim\Permission::PERM_ALL,
                'salePrice'                    => 0,
                'saleType'                     => 0,
                'creationDate'                 => time(),
                'groupID'                      => UUID::ZERO,
                'groupOwned'                   => 0,
                'flags'                        => 1,
                'inventoryID'                  => UUID::random(),
                'avatarID'                     => $user_uuid,
                'parentFolderID'               => '',
                'inventoryGroupPermissions'    => \Opensim\Permission::PERM_ALL,
            ),
        ),
        'Clothing' => array(
            'Default Pants' => array(
                'assetID'                      => '00000000-38f9-1111-024e-222222111120',
                'assetType'                    => \Opensim\AssetType::Clothing,
                'inventoryName'                => 'Default Pants',
                'inventoryDescription'         => '',
                'inventoryNextPermissions'     => \Opensim\Permission::PERM_ALL,
                'inventoryCurrentPermissions'  => \Opensim\Permission::PERM_ALL,
                'invType'                      => \Opensim\AssetType::ImageTGA,
                'creatorID'                    => $user_uuid,
                'inventoryBasePermissions'     => \Opensim\Permission::PERM_ALL,
                'inventoryEveryOnePermissions' => \Opensim\Permission::PERM_ALL,
                'salePrice'                    => 0,
                'saleType'                     => 0,
                'creationDate'                 => time(),
                'groupID'                      => UUID::ZERO,
                'groupOwned'                   => 0,
                'flags'                        => 5,
                'inventoryID'                  => UUID::random(),
                'avatarID'                     => $user_uuid,
                'parentFolderID'               => '',
                'inventoryGroupPermissions'    => \Opensim\Permission::PERM_ALL,
            ),
            'Default Shirt' => array(
                'assetID'                      => '00000000-38f9-1111-024e-222222111110',
                'assetType'                    => \Opensim\AssetType::Clothing,
                'inventoryName'                => 'Default Shirt',
                'inventoryDescription'         => '',
                'inventoryNextPermissions'     => \Opensim\Permission::PERM_ALL,
                'inventoryCurrentPermissions'  => \Opensim\Permission::PERM_ALL,
                'invType'                      => \Opensim\AssetType::ImageTGA,
                'creatorID'                    => $user_uuid,
                'inventoryBasePermissions'     => \Opensim\Permission::PERM_ALL,
                'inventoryEveryOnePermissions' => \Opensim\Permission::PERM_ALL,
                'salePrice'                    => 0,
                'saleType'                     => 0,
                'creationDate'                 => time(),
                'groupID'                      => UUID::ZERO,
                'groupOwned'                   => 0,
                'flags'                        => 4,
                'inventoryID'                  => UUID::random(),
                'avatarID'                     => $user_uuid,
                'parentFolderID'               => '',
                'inventoryGroupPermissions'    => \Opensim\Permission::PERM_ALL,
            ),
        ),
    );

    // For each item
    // find the folder id
    // to insert the item
    $parsed_items = array();
    foreach ($items as $folder_name => $item) 
    {
        foreach ($item as $key => $value) 
        {
            if(isset($account_inventory[$folder_name]))
            {
                // Folder info
                $f = $account_inventory[$folder_name];
                $item[$key]['parentFolderID'] = $f['folderID'];
                $parsed_items[] = $item[$key];
            }
        }
    }

    return $parsed_items;
}

function load_ruth_appearance($user_uuid)
{
    $items_array = array(
        'AvatarHeight' => array(
            'PrincipalID' => $user_uuid,
            'Name'        => 'AvatarHeight',
            'Value'       => '1.690999',
        ),
        'AvatarType' => array(
            'PrincipalID' => $user_uuid,
            'Name'        => 'AvatarType',
            'Value'       => '1',
        ),
        'Serial' => array(
            'PrincipalID' => $user_uuid,
            'Name'        => 'Serial',
            'Value'       => '0',
        ),
        'VisualParams' => array(
            'PrincipalID' => $user_uuid,
            'Name'        => 'VisualParams',
            'Value'       => '33,61,85,23,58,127,63,85,63,42,0,85,63,36,85,95,153,63,34,0,63,109,88,132,63,136,81,85,103,136,127,0,150,150,150,127,0,0,0,0,0,127,0,0,255,127,114,127,99,63,127,140,127,127,0,0,0,191,0,104,0,0,0,0,0,0,0,0,0,145,216,133,0,127,0,127,170,0,0,127,127,109,85,127,127,63,85,42,150,150,150,150,150,150,150,25,150,150,150,0,127,0,0,144,85,127,132,127,85,0,127,127,127,127,127,127,59,127,85,127,127,106,47,79,127,127,204,2,141,66,0,0,127,127,0,0,0,0,127,0,159,0,0,178,127,36,85,131,127,127,127,153,95,0,140,75,27,127,127,0,150,150,198,0,0,63,30,127,165,209,198,127,127,153,204,51,51,255,255,255,204,0,255,150,150,150,150,150,150,150,150,150,150,0,150,150,150,150,150,0,127,127,150,150,150,150,150,150,150,150,0,0,150,51,132,150,150,150',
        ),
        'Wearable 0:0' => array(
            'PrincipalID' => $user_uuid,
            'Name' => 'Wearable 0:0',
            'Value' => '66c41e39-38f9-f75a-024e-585989bfab73',
        ),
        'Wearable 1:0' => array(
            'PrincipalID' => $user_uuid,
            'Name' => 'Wearable 1:0',
            'Value' => '77c41e39-38f9-f75a-024e-585989bbabbb',
        ),
        'Wearable 2:0' => array(
            'PrincipalID' => $user_uuid,
            'Name' => 'Wearable 2:0',
            'Value' => 'd342e6c0-b9d2-11dc-95ff-0800200c9a66',
        ),
        'Wearable 3:0' => array(
            'PrincipalID' => $user_uuid,
            'Name' => 'Wearable 3:0',
            'Value' => '4bb6fa4d-1cd2-498a-a84c-95c1a0e745a7',
        ),
        'Wearable 4:0' => array(
            'PrincipalID' => $user_uuid,
            'Name' => 'Wearable 4:0',
            'Value' => '00000000-38f9-1111-024e-222222111110',
        ),
        'Wearable 5:0' => array(
            'PrincipalID' => $user_uuid,
            'Name' => 'Wearable 5:0',
            'Value' => '00000000-38f9-1111-024e-222222111120',
        ),
    );

    // From the appearance, 
    // get only items with uuid values
    // those must be wearable items
    $items_only_uuids = array();
    foreach ($items_array as $key => $value) 
    {
        if(\Opensim\UUID::is_valid($value['Value']))
        {
            $items_only_uuids[$value['Name']] = $value['Value'];
        }
    }

    // Get items inserted on the database
    $apearance_items = \Opensim\Model\Os\InventoryItem::where_in('assetID', array_values($items_only_uuids))->where('avatarID', '=', $user_uuid)->get();

    // Build appearance values with
    // received appareance items
    foreach ($apearance_items as $a_item) 
    {
        $key = array_search($a_item->assetid, $items_only_uuids);
        if(isset($key) and !empty($key))
        {
            // inventoryid:assetid
            $items_array[$key]['Value'] = $a_item->inventoryid.':'.$items_only_uuids[$key];
        }
    }

    return $items_array;
}

if( !function_exists('get_regions') ) 
{
    /**
     * Used by opensim settings form
     * to retrieve all regions and 
     * populate form select input
     * 
     * @return array array will all available regions
     */
    function get_regions()
    {
        $db_is_ready = \Settings\Config::get('settings::core.passes_db_settings');

        if((bool)$db_is_ready)
        {
            $all_regions = \Opensim\Model\Os\Region::all();

            $regions = array();

            foreach ($all_regions as $region) 
            {
                $regions[$region->uuid] = $region->regionname;
            }

            return $regions;
        }
        else
        {
            return array();
        }
    }
}