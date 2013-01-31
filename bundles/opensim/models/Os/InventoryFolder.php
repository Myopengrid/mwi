<?php namespace Opensim\Model\Os;

use Eloquent;
use Opensim\User;
use Opensim\UUID;
use Laravel\Event;

class InventoryFolder extends Eloquent {

    public static $connection = 'opensim';
    public static $table      = 'inventoryfolders';
    public static $timestamps = false;
    public static $key        = 'folderID';

    public function create_inventory($user_uuid)
    {
        if( !isset($user_uuid) or empty($user_uuid))
        {
            return null;
        }

        $inventory_uuid = UUID::random();

        $inventory = array(

            'Textures' => array(
                'folderName'     => 'Textures',
                'type'           => 0,
                'version'        => 1,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),

            'Sounds' => array(
                'folderName'     => 'Sounds',
                'type'           => 1,
                'version'        => 1,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),
            'Calling Cards' => array(
                'folderName'     => 'Calling Cards',
                'type'           => 2,
                'version'        => 1,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),
            'Landmarks' => array(
                'folderName'     => 'Landmarks',
                'type'           => 3,
                'version'        => 1,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),
            'My Inventory' => array(
                'folderName'     => 'My Inventory',
                'type'           => 9,
                'version'        => 1,
                'folderID'       => $inventory_uuid,
                'agentID'        => $user_uuid,
                'parentFolderID' => UUID::ZERO,
            ),
            'Photo Album' => array(
                'folderName'     => 'Photo Album',
                'type'           => 15,
                'version'        => 1,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),
            'Clothing' => array(
                'folderName'     => 'Clothing',
                'type'           => 5,
                'version'        => 3,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),
            'Objects' => array(
                'folderName'     => 'Objects',
                'type'           => 6,
                'version'        => 1,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),
            'Notecards' => array(
                'folderName'     => 'Notecards',
                'type'           => 7,
                'version'        => 1,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),
            'Scripts' => array(
                'folderName'     => 'Scripts',
                'type'           => 10,
                'version'        => 1,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),
            'Body Parts' => array(
                'folderName'     => 'Body Parts',
                'type'           => 13,
                'version'        => 5,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),
            'Trash' => array(
                'folderName'     => 'Trash',
                'type'           => 14,
                'version'        => 1,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),
            'Lost And Found' => array(
                'folderName'     => 'Lost And Found',
                'type'           => 16,
                'version'        => 1,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),
            'Animations' => array(
                'folderName'     => 'Animations',
                'type'           => 20,
                'version'        => 1,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            ),
            'Gestures' => array(
                'folderName'     => 'Gestures',
                'type'           => 21,
                'version'        => 1,
                'folderID'       => UUID::random(),
                'agentID'        => $user_uuid,
                'parentFolderID' => $inventory_uuid,
            )
        );

        $modified_inventory = Event::until('opensim.model.os.inventoryfolder.create_inventory', array($inventory_uuid, $inventory));
        if( !is_null($modified_inventory) )
        {
            $inventory = $modified_inventory;
        }

        $this->insert($inventory);

        return $inventory;
    }

    public function get_inventory()
    {
        $inventory = InventoryFolder::where_agentID($this->principalid)->get();
        $items = InventoryItem::where_avatarID($this->principalid)->get();

        
        $items_array = array();

        foreach ($items as $item) 
        {
            $i = array(
                'assetid'                      => $item->assetid,
                'assettype'                    => $item->assettype, 
                'inventoryname'                => $item->inventoryname,
                'inventorydescription'         => $item->inventorydescription,
                'inventorynextpermissions'     => $item->inventorynextpermissions,
                'inventorycurrentpermissions'  => $item->inventorycurrentpermissions,
                'invtype'                      => $item->invtype,
                'creatorid'                    => $item->creatorid,
                'inventorybasepermissions'     => $item->inventorybasepermissions,
                'inventoryeveryonepermissions' => $item->inventoryeveryonepermissions,
                'saleprice'                    => $item->saleprice,
                'saletype'                     => $item->saletype,
                'creationdate'                 => $item->creationdate,
                'groupid'                      => $item->groupid,
                'groupowned'                   => $item->groupowned,
                'flags'                        => $item->flags,
                'inventoryid'                  => $item->inventoryid,
                'avatarid'                     => $item->avatarid,
                'parentfolderid'               => $item->parentfolderid,
                'inventorygrouppermissions'    => $item->inventorygrouppermissions,
            );
            $items_array[$item->parentfolderid] = $i;
        }

        $new_array = array();

        foreach ($inventory as $inventory_folder) 
        {
            $f = array(
                'foldername'     => $inventory_folder->foldername,
                'type'           => $inventory_folder->type,
                'version'        => $inventory_folder->version,
                'folderid'       => $inventory_folder->folderid,
                'agentid'        => $inventory_folder->agentid,
                'parentfolderid' => $inventory_folder->parentfolderid,
                'items'          => array(),
            );
            
            $new_array[$inventory_folder->folderid] = $f;
        }

        //ff($this->buildTree($new_array, $items_array));
    }

    public function buildTree(array &$elements, $items, $parentId = '00000000-0000-0000-0000-000000000000') 
    {
       $branch = array();

       foreach ($elements as $element) 
       {
           if ($element['parentfolderid'] == $parentId) 
           {
               $children = $this->buildTree($elements, $items, $element['folderid']);
               if($children) 
               {
                   $element['children'] = $children;
               }
               
               $branch[$element['folderid']] = $element;
               
               foreach ($items as $item) 
               {
                   if($item['parentfolderid'] == $element['folderid'])
                   {
                        $branch[$element['folderid']]['items'][] = $item;
                        //unset($items[$item['inventoryid']]);
                   }
               }
               unset($items['parentfolderid']);
               unset($elements[$element['folderid']]);
           }
       }
       return $branch;
    }

    public static function delete_inventory($user_uuid)
    {
        if( !isset($user_uuid) or empty($user_uuid))
        {
            return null;
        }
    }
    
    public function clean_trash($user_uuid)
    {
        if( !isset($user_uuid) or empty($user_uuid))
        {
            return null;
        }
    }
}