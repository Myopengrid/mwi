<?php namespace Opensim\Model\Os;

use Eloquent;
use Laravel\Log;

class InventoryItem extends Eloquent {

    public static $connection = 'opensim';
    public static $table      = 'inventoryitems';
    public static $timestamps = false;

    public function insert_items($folder_name, $items = array())
    {
        $folder = InventoryFolder::where_folderName($folder_name)->first();
        if( !isset($folder) )
        {
            Log::error('Failed to insert items. Folder '.$folder_name.' does not exist.');
            return null;
        }

        foreach ($items as $item) 
        {
            $item['parentFolderID'] = $folder->folderid;
        }

        $this->insert($items);

        return $items;
    } 
}