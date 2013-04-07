<?php

class Navigation_Backend_Navigation_Links_Controller extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_show($link_id)
    {
        if(ctype_digit($link_id))
        {
            if(Bundle::exists('pages'))
            {
                $link = Navigation\Model\Link::with('page')->find($link_id);
            }
            else
            {
                $link = Navigation\Model\Link::find($link_id);
            }
            
            if(isset($link) and !empty($link))
            {
                // Build Restricted_to String 
                $groups_array = array();
                $ids = explode(',', $link->restricted_to);
                $groups = Groups\Model\Group::where_in('id', $ids)->get(array('name'));
                if(in_array(0, $ids))
                {
                    $groups_array[] = Lang::line('navigation::lang.Any')->get(ADM_LANG);
                }
                if(in_array(-1, $ids))
                {
                    $groups_array[] = Lang::line('navigation::lang.Guests')->get(ADM_LANG);
                }
                if(in_array(-2, $ids))
                {
                    $groups_array[] = Lang::line('navigation::lang.Authenticated')->get(ADM_LANG);
                }

                if(isset($groups) and !empty($groups))
                {
                    foreach ($groups as $group)
                    {
                        $groups_array[] = $group->name;
                    }
                }
                $restricted_to = implode(', ', $groups_array);
                //

                return View::make('navigation::backend.links.show', $this->data)
                            ->with('restricted_to', $restricted_to)
                            ->with('link', $link);
            }
            else
            {
                return Lang::line('navigation::lang.Details not found for this link')->get(ADM_LANG);
            }
        }
        else
        {
            return Lang::line('navigation::lang.Invalid Link ID')->get(ADM_LANG);
        }
    }

    public function get_edit($link_id)
    {
        if(Bundle::exists('pages'))
        {
            $link = Navigation\Model\Link::with('page')->find($link_id);
            $pages = Pages\Model\Page::where('status', '=', 'live')->get(array('id', 'title'));
        }
        else
        {
            $link = Navigation\Model\Link::find($link_id);
            $pages = null;
        }
        
        $navigation_groups = Navigation\Model\Group::all();
        $groups = Groups\Model\Group::all();
        
        $modules = Modules\Model\Module::where('enabled', '=', 1)
                            ->where('is_frontend', '=', 1)
                            ->get(array('slug', 'name', 'id'));

        if(isset($link) and !empty($link))
        {
            return View::make('navigation::backend.links.edit', $this->data)
                         ->with('link', $link)
                         ->with('pages', $pages)
                         ->with('groups', $groups)
                         ->with('navigation_group', $navigation_groups)
                         ->with('modules', $modules);
        }
    }

    public function put_update($link_id)
    {
        $order_data = Input::get('order');

        if(isset($order_data) and !empty($order_data) and $link_id == '0')
        {
            $this->update_child($order_data);
            Event::fire('mwi.navigation_links_order_updated', array($order_data));
            return;
        }

        $rules = array(
            'title'         => 'required|max:50',
            'link_type'     => 'required|link_type',
            'restricted_to' => 'required',
        );

        $validation = Navigation\Validator::make(Input::all(), $rules)->speaks(ADM_LANG);

        if ($validation->passes())
        {
            $restricted_to_array = Input::get('restricted_to');
            // If there is many restrictions selected 
            // and the ANY option is selected as well
            // just save the link with the any value 0
            // and remove all the others since "ANY" precced other values
            if( in_array(0, $restricted_to_array))
            {
                $restricted_to = 0;
            }
            else
            {
                $restricted_to = !empty($restricted_to_array) ? implode(',', $restricted_to_array) : 0;
            }

           
            $link = Navigation\Model\Link::find(Input::get('link_id'));

            // This link exists
            if(isset($link) and !empty($link))
            {
                // we are changing the navigation group
                // change parent to 0.
                $post_group = Input::get('group_id');
                if($post_group != $link->group_id)
                {
                    $link->parent = 0;
                }
                // set message for edit post
                Session::flash('message', Lang::line('navigation::lang.Link was successfully updated')->get(ADM_LANG));
            }
            else
            {
                Session::flash('message_type', 'error');
                Session::flash('message', Lang::line('navigation::lang.Cant find link to update')->get(ADM_LANG));
                // return success so javascript can refresh the page
                echo 'success';
                return;
            }
            
            $link->title         = Input::get('title');
            $link->link_type     = Input::get('link_type');
            $link->url           = URL::valid(Input::get('url')) ? Input::get('url') : '';
            $link->module_id     = Input::get('module_id');
            $link->uri           = trim( Input::get('uri'), "/" );
            $link->page_id       = Input::get('page_id');
            $link->target        = Input::get('target');
            $link->class         = Input::get('class');
            $link->restricted_to = $restricted_to;
            $link->group_id      = Input::get('group_id');
            $link->save();

            Event::fire('mwi.navigation_link_created', array($link));

            Session::flash('message_type', 'success');
            // return success so javascript can refresh the page
            echo 'success';
        }
        else
        {
            return View::make('navigation::backend.partials.links.message')->with('errors', $validation->errors);
        }
    }

    // this is not following the convention
    // since we need to pass the goup where
    // the link is going to be created
    public function get_new($group_id)
    {
        if(Bundle::exists('pages'))
        {
            $pages = Pages\Model\Page::where('status', '=', 'live')->get(array('id', 'title'));
        }
        else
        {
            $pages = null;
        }

        $modules = Modules\Model\Module::where('enabled', '=', 1)
                           ->where('is_frontend', '=', 1)
                           ->get(array('id', 'slug', 'name'));
        
        $groups = Groups\Model\Group::all();
        
        return View::make('navigation::backend.links.create', $this->data)
                    ->with('nav_group_id', $group_id)
                    ->with('modules', $modules)
                    ->with('pages', $pages)
                    ->with('groups', $groups);
    }

    public function post_create()
    {
        $rules = array(
            'title'  => 'required|max:50',
            'link_type' => 'required|link_type',
            'restricted_to' => 'required',
        );

        $validation = Navigation\Validator::make(Input::all(), $rules)->speaks(ADM_LANG);

        if ($validation->passes())
        {
            $restricted_to_array = Input::get('restricted_to');
            // If there is many restrictions selected 
            // and the ANY option is selected as well
            // just save the link with the any value 0
            // and remove all the others since any precced other values
            if( in_array(0, $restricted_to_array))
            {
                $restricted_to = 0;
            }
            else
            {
                $restricted_to = !empty($restricted_to_array) ? implode(',', $restricted_to_array) : 0;
            }

            $link = new Navigation\Model\Link();
            $link->parent = 0;
            // set message for new link creation post
            Session::flash('message', Lang::line('navigation::lang.Link was successfully created')->get(ADM_LANG));
            

            $link->title         = Input::get('title');
            $link->link_type     = Input::get('link_type');
            $link->url           = URL::valid(Input::get('url')) ? Input::get('url') : '';
            $link->module_id     = 0;
            $link->uri           = trim( Input::get('uri'), "/" );
            $link->page_id       = Input::get('page_id');
            $link->target        = Input::get('target');
            $link->class         = Input::get('class');
            $link->restricted_to = $restricted_to;
            $link->group_id      = Input::get('group_id');
            $link->save();

            Event::fire('mwi.navigation_link_created', array($link));

            Session::flash('message_type', 'success');
            // return success so javascript can refresh the page
            echo 'success';
        }
        else
        {
            return View::make('navigation::backend.partials.links.message')->with('errors', $validation->errors);
        }
    }

    public function delete_destroy($link_id)
    {
        if(ctype_digit($link_id))
        {
            $link = Navigation\Model\Link::find($link_id);
            if(isset($link) and !empty($link))
            {
                // if this is a parent link lets update the children to its parent
                Navigation\Model\Link::where('parent', '=', $link->id)->update(array('parent' => $link->parent));

                $link->delete();

                Event::fire('mwi.navigation_link_deleted', array($link));

                $this->data['message'] = Lang::line('navigation::lang.Link was successfully deleted')->get(ADM_LANG);
                $this->data['message_type'] = 'success';
                return Redirect::to(ADM_URI.'/navigation')->with($this->data);
            }
            else
            {
                $this->data['message'] = Lang::line('Could not delete link The link was not found')->get(ADM_LANG);
                $this->data['message_type'] = 'error';
                return Redirect::to(ADM_URI.'/navigation')->with($this->data);
            }
        }
        else
        {
            $this->data['message'] = Lang::line('Invalid link id')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/navigation')->with($this->data);
        }
    }

    public function update_child($nodes, $parent_id = 0)
    {
        if(is_array($nodes) and !empty($nodes))
        {
            foreach ($nodes as $order => $node)
            {
                $link = Navigation\Model\Link::find($node['id']);
                $link->parent = $parent_id;
                $link->order = $order;
                $link->save();
                if ( ! empty($node['children'])) 
                {
                    $this->update_child($node['children'], $node['id']);
                }
            }
        }
    }
}