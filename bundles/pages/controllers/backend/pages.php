<?php

class Pages_Backend_Pages_Controller extends Admin_Controller {


    public function __construct()
    {
        parent::__construct();
        
        $this->data['section_bar'] = array(
            Lang::line('pages::lang.Pages')->get(ADM_LANG)    => URL::base().'/'.ADM_URI.'/pages',
            Lang::line('pages::lang.New Page')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/pages/new',
        );

        $this->data['bar'] = array(
            'title'       => Lang::line('pages::lang.Pages')->get(ADM_LANG),
            'url'         => URL::base().'/'.ADM_URI.'/pages',
            'description' => Lang::line('pages::lang.Allows users to create and manage pages of the application')->get(ADM_LANG),
        );
    }

    public function get_index()
    {
        $page = new Pages\Model\Page();
        $this->data['pages'] = $page->get_page_tree();
        
        $this->data['section_bar_active'] = Lang::line('pages::lang.Pages')->get(ADM_LANG);
        
        return $this->theme->render('pages::index', $this->data);
    }
    
    public function get_show($page_id)
    {
        $this->data['page'] = Pages\Model\Page::find($page_id);
        return $this->theme->render_partial('pages::partials.page_details', $this->data);
    }

    public function get_edit($page_id)
    {
        $this->data['section_bar'] = array(
            Lang::line('pages::lang.Pages')->get(ADM_LANG)     => URL::base().'/'.ADM_URI.'/pages',
            Lang::line('pages::lang.Edit Page')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/pages/edit/'.$page_id,
            Lang::line('pages::lang.New Page')->get(ADM_LANG)  => URL::base().'/'.ADM_URI.'/pages/new',
        );

        $this->data['page']              = Pages\Model\Page::find($page_id)->with('chunks');
        $this->data['groups']            = Groups\Model\Group::all();
        $this->data['navigation_groups'] = Navigation\Model\Group::all();
        $this->data['keywords'] = Config::get('settings::core.application_keywords');

        $this->data['section_bar_active'] = Lang::line('pages::lang.Edit Page')->get(ADM_LANG);
        
        return $this->theme->render('pages::edit', $this->data);
    }

    public function put_update($page_id)
    { 
        $order_data = Input::get('order');
        if(isset($order_data) and !empty($order_data) and $page_id == '0')
        {
            $this->update_child($order_data);
            return;
        }

        $is_home_post = Input::get('is_home');
            
        $page = Pages\Model\Page::find($page_id);

        $restricted_to = Input::get('restricted_to');

        if($page->slug == '404' or $restricted_to == null)
        {
            $restricted_to = array('0' => '0');
        }

        $restricted_to = implode(',', $restricted_to);
        
        $page->title                = Input::get('title');
        $page->slug                 = Input::get('slug');
        $page->body                 = Input::get('body');
        $page->status               = Input::get('status');
        $page->meta_title           = Input::get('meta_title');
        $page->meta_keywords        = Input::get('meta_keywords');
        $page->meta_description     = Input::get('meta_description');
        $page->restricted_to        = $restricted_to;
        $page->is_home              = (isset($is_home_post) and $is_home_post == "1") ? 1 : 0 ;
        $page->strict_uri           = Input::get('strict_uri');
        $page->save();


        $page_chunk = Input::get('chunk_body');
        //Now if the page was saved I have the id to create the chunks
        if(isset($page_chunk) and !empty($page_chunk))
        {
            foreach (Input::get('chunk_body') as $chunk_id => $chunk) 
            {
                $new_chunk = Pages\Model\Chunk::find($chunk_id);
                $new_chunk->body = $chunk;
                $new_chunk->save();
            }
        }

        $this->data['message'] = Lang::line('pages::lang.Page was successfully updated')->get(ADM_LANG);
        $this->data['message_type'] = 'success';
        
        if(Input::get('btnAction') == 'save_exit')
            return Redirect::to(ADM_URI.'/pages')->with($this->data);
        else
            return Redirect::to(ADM_URI.'/pages/'.$page->id.'/edit/')->with($this->data);
    }

    public function get_new($page_id = null)
    {
        if($page_id == null) // Create new page
        {
            $this->data['section_bar'] = array(
                Lang::line('pages::lang.Pages')->get(ADM_LANG)     => URL::base().'/'.ADM_URI.'/pages',
                Lang::line('pages::lang.New Page')->get(ADM_LANG)  => URL::base().'/'.ADM_URI.'/pages/new',
            );
            
            $this->data['groups'] = Groups\Model\Group::all();
            $this->data['navigation_groups'] = Navigation\Model\Group::all();
            
            $this->data['section_bar_active'] = Lang::line('pages::lang.New Page')->get(ADM_LANG);

            return $this->theme->render('pages::create', $this->data);
        }
        else // Duplicate a page
        {
            $this->data['section_bar_active'] = Lang::line('pages::lang.New Page')->get(ADM_LANG);

            $this->data['page'] = Pages\Model\Page::find($page_id);
            if($this->data['page']->parent_id != 0)
            {
                $parent_slug = Pages\Model\Page::where('id', '=', $this->data['page']->parent_id)->first('slug');
            }

            $this->data['parent_slug'] = isset($parent_slug->slug) ? $parent_slug->slug : '';
            $this->data['groups'] = Groups\Model\Group::all();
            $this->data['navigation_groups'] = Navigation\Model\Group::all();
            
            return $this->theme->render('pages::duplicate', $this->data);
        }
    }

    public function post_create()
    {

        $validation = Pages\Model\Page::validate(Input::all());
        
        if($validation->passes())
        {
            $is_home_post = Input::get('is_home');

            // If post comming from duplicate
            // we have parend_id and parent_slug
            $slug = Input::get('slug');
            $parent_slug = Input::get('parent_slug');
            if(isset($parent_slug) and !empty($parent_slug))
            {
                $slug = $parent_slug . '/'. $slug;
            }
            
            $parent_id = Input::get('parent_id');
            $parent_id = $parent_id != 0 ? $parent_id : 0;
            $restricted_to = Input::get('restricted_to');
            
            if($restricted_to == null)
            {
                $restricted_to = array('0' => '0');
            }

            $restricted_to = implode(',', $restricted_to);

            $page = new Pages\Model\Page();
            $page->parent_id            = $parent_id;
            $page->title                = Input::get('title');
            $page->slug                 = $slug;
            $page->status               = Input::get('status');
            $page->meta_title           = Input::get('meta_title');
            $page->meta_keywords        = Input::get('meta_keywords');
            $page->meta_description     = Input::get('meta_description');
            $page->body                 = Input::get('body');
            $page->restricted_to        = $restricted_to;
            $page->is_home              = (isset($is_home_post) and $is_home_post == 1) ? 1 : 0 ;
            $page->strict_uri           = Input::get('strict_uri');

            $page->save();


            // the user want to create a navigation link?
            $navigation_id = Input::get('navigation_group_id');
            
            if(is_numeric($navigation_id) and $navigation_id != 0)
            {
                // If the user want to create a link
                // for this page, we need to set this
                // page as live or it will break navigation
                
                $page->status = 'live';
                $page->save();

                $link = new Navigation\Model\Link();
                
                // add a link for this page to the navigation
                $link->title = Str::title($page->title);
                $link->link_type = 'page';
                $link->page_id = $page->id;
                $link->group_id = $navigation_id;
                $link->save();
            } 

            $this->data['message'] = Lang::line('pages::lang.Page was successfully created')->get(ADM_LANG);
            $this->data['message_type'] = 'success';

            return Redirect::to(ADM_URI.'/pages')->with($this->data);
        }
        else
        {
            return Redirect::to(ADM_URI.'/pages/new')->with_errors($validation)->with_input();
        }
    }

    public function delete_destroy($page_id)
    {

        $page = Pages\Model\Page::find($page_id);

        // If there is childen set children to the actual page parent
        Pages\Model\Page::where('parent_id', '=', $page->id)->update(array('parent_id' => $page->parent_id));

        // This page has links?? If so lets delete them
        $link = Navigation\Model\Link::where('page_id', '=', $page->id)->get();
        
        //This page has links?
        if(isset($link) and !empty($link))
        {
            foreach ($link as $delete_link)
            {
                // If there is childen set children to the actual link parent
                Navigation\Model\Link::where('parent', '=', $delete_link->id)->update(array('parent' => $delete_link->parent));
                
                $delete_link->delete();

                Event::fire('mwi.navigation_link_deleted', array($delete_link));
            }
        }

        $page->delete();

        Event::fire('mwi.pages_page_deleted', array($page));

        Session::flash('message', Lang::line('pages::lang.Page was successfully deleted')->get(ADM_LANG));
        Session::flash('message_type', 'success');

        if (Request::ajax())
        {
            $data = array(
                'success' => 'true',
                'url' => URL::base().'/'.ADM_URI.'/pages',
            );
            
            return json_encode($data);
        }
        else
        {
            return Redirect::to(ADM_URI.'/pages')->with($this->data);
        }  
    }

    public function update_child($nodes, $parent_slug = '', $parent_id = 0)
    {
        foreach ($nodes as $order => $node) 
        {
            $page = Pages\Model\Page::find($node['id']);
            
            #########################################
            # If this not is a child its slug will 
            # be something like "home/contact" if we 
            # are draging it back to root we need to
            # set its slug back to the original "contact" 
            #
            # Page slug "home" or "home/contact"
            $slug = $page->slug;
            
            # Explode slug to try get its elements
            $slug_parts = explode('/', $slug);
            $count = count($slug_parts);
            
            # Do we have elements?
            if( count($slug_parts) >= 1)
            {
                # We have elements lets set the slug to the original page slug
                # actual slug /game/resident/full-version
                # original slug will be full-version
                $slug = $slug_parts[$count-1];
            }
            #######################################

            $page->parent_id = $parent_id;
            $page->slug = $parent_slug . $slug;
            $page->order = $order;
            $page->save();
            
            if(isset($node['children']))
            {
                $this->update_child($node['children'], $page->slug . '/', $page->id);
            }
        }
    }

    public function get_preview($page_id)
    {
        $page = Pages\Model\Page::find($page_id);
        if( ! is_null($page))
        {
            return Redirect::to('page/'.$page->slug);
        }
        else
        {
            return Redirect::to('404');
        }
    }
}