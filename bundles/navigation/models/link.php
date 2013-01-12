<?php namespace Navigation\Model;

use \Eloquent;

class Link extends Eloquent {

    /**
     * Holds the database table name
     */
    public static $table = 'navigation_links';

    public $includes = array('page');


    /**
     * Relationship with pages module
     *
     * @return object The page of this link.
     */
    public function page()
    {
        if(\Bundle::exists('pages'))
        {
            return $this->belongs_to('Pages\Model\Page');
        }
        else
        {
            throw new \Exception('Pages module is not installed. To call links with pages you must install the pages module.');
        }  
    }

    /**
     * Relationship with modules module.
     *
     * @return object The module that created this link.
     */
    public function module()
    {
        return $this->has_one('Modules\Model\Module');
    }

    /**
     * Build a multi-array of parent > children.
     *
     * @return array An array representing the page tree.
     */
    public function get_links_tree()
    {
        if(\Bundle::exists('pages'))
        {
            $all_links = Link::with('page')->order_by('order', 'asc')->get(array('id', 'group_id', 'title', 'class'));
        }
        else
        {
            $all_links = Link::order_by('order', 'asc')->get(array('id', 'group_id', 'title', 'class'));
        }
        
        
        // First, re-index the array.
        foreach ($all_links as $row)
        {
            $pages[$row->id] = array(
                    'id'                  => $row->id,
                    'li_id'               => 'link_',
                    'link_type'           => $row->link_type,
                    'rel'                 => $row->navigation_group_id,
                    'parent_id'           => $this->id,
                    'title'               => $row->title,
                    'url'                 => $row->url,//isset($page->slug) ? $page->slug : '',
                    'target'              => $row->target,
                    'class'               => $row->class,
                );
        }
        unset($all_links);

        // Build a multidimensional array of parent > children.
        foreach ($pages as $row)
        {
            if (array_key_exists($row['parent_id'], $pages))
            {
                // Add this page to the children array of the parent page.
                $pages[$row['parent_id']]['children'][] =& $pages[$row['id']];
            }

            // This is a root page.
            if ($row['parent_id'] == 0)
            {
                $page_array[] =& $pages[$row['id']];
            }
        }
        return $page_array;
    }


}