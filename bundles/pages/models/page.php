<?php namespace Pages\Model;

use \Eloquent;

class Page extends Eloquent {

    public static $timestamps = true;

    public static $rules = array(
        'title' => 'required',
        'slug'  => 'required|unique:pages',
        'body'  => 'required',
    );

    public static function validate($input, $rules = array())
    {
        return $validation = \Validator::make($input, self::$rules)->speaks(ADM_LANG);
    }

    public function fields()
    {
        return $this->has_many('Modules\Field');
    }

    public function children()
    {
        return $this->has_many('Pages\Model\Page', 'parent_id');
    }

    public static function get_uri($parent_id, $slug)
    {
        $slug = $slug;
        $page = self::where('id', '=', $parent_id)->first();
        if(isset($page) and isset($page->slug) and !empty($page->slug))
        {
            $slug .= $slug . '/'. $page->slug;
            self::get_uri($page->parent_id, $page->slug);
        }
        \FirePHP::getInstance(true)->info($slug);
        return $slug;
    }

    /**
     * Build a multi-array of parent > children.
     *
     * @return array An array representing the page tree.
     */
    public function get_page_tree()
    {
        $all_pages = Page::order_by('order', 'asc')->get(array('id', 'parent_id', 'title'));
        
        // First, re-index the array.
        foreach ($all_pages as $row)
        {
            $pages[$row->id] = array(
                'id'                  => $row->id,
                'li_id'               => 'page_',
                'link_type'           => 'page',
                'rel'                 => $row->id,
                'parent_id'           => $row->parent_id,
                'title'               => $row->title,
                'url'                 => isset($page->slug) ? $page->slug : '',
                'class'               => $row->class,
            );
        }
        unset($all_pages);

        $page_array = array();

        // Do we have pages?
        if(isset($pages) and !empty($pages))
        {
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
        }
        return $page_array;
    }
}