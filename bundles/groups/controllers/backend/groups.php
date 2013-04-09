<?php

class Groups_Backend_Groups_Controller extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->data['bar'] = array(
            'title'       => __('groups::lang.Groups')->get(ADM_LANG),
            'url'         => URL::base(). '/'.ADM_URI.'/groups',
            'description' => __('groups::lang.Users can be placed into groups to manage permissions')->get(ADM_LANG),
        );

        $this->data['section_bar'] = array(
            __('groups::lang.Groups')->get(ADM_LANG)           => URL::base().'/'.ADM_URI.'/groups',
            __('groups::lang.New Group')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/groups/new'
        );
    }

    public function get_index()
    {
        $this->data['section_bar_active'] = __('groups::lang.Groups')->get(ADM_LANG);
        $this->data['groups'] = Groups\Model\Group::all();
        return $this->theme->render('groups::backend.index', $this->data);
    }

    public function get_edit($group_id)
    {
        if( ! ctype_digit($group_id))
        {
            $this->data['message'] = __('groups::lang.Invalid id')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/groups')->with($this->data);
        }

        $group = Groups\Model\Group::find($group_id);

        if( ! isset($group) or empty($group))
        {
            $this->data['message'] = __('groups::lang.Sorry cannot find group to edit')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/groups')->with($this->data);
        }

        $this->data['section_bar'] = array(
            __('groups::lang.Groups')->get(ADM_LANG)     => URL::base().'/'.ADM_URI.'/groups',
            __('groups::lang.New Group')->get(ADM_LANG)  => URL::base().'/'.ADM_URI.'/groups/new',
            __('groups::lang.Edit Group')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/groups/'.$group_id.'/edit',
        );
        
        $this->data['section_bar_active'] = __('groups::lang.Edit Group')->get(ADM_LANG);

        if($group->slug == 'admin' or $group->slug == 'users')
        {
            $this->data['message'] = __('groups::lang.Group [:group_name] cannot be edited', array('group_name' => $group->name))->get(ADM_LANG);
            $this->data['message_type'] = 'info';
            return Redirect::to(ADM_URI.'/'.'groups')->with($this->data);
        }
        else
        {
            $this->data['group'] = $group;
            return $this->theme->render('groups::backend.edit', $this->data);
        }
    }

    public function put_update($group_id)
    {

        if( ! ctype_digit($group_id))
        {
            $this->data['message'] = __('groups::lang.Invalid id')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/groups')->with($this->data);
        }

        $group = Groups\Model\Group::find($group_id);

        if( ! isset($group) or empty($group))
        {
            $this->data['message'] = __('groups::lang.Sorry cannot find group to edit')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/groups')->with($this->data);
        }

        $this->data['section_bar'] = array(
            'Groups'     => URL::base().'/'.ADM_URI.'/groups',
            'Create New Group'  => URL::base().'/'.ADM_URI.'/groups/new',
            'Edit Group'  => URL::base().'/'.ADM_URI.'/groups/'.$group_id.'/edit',
        );
        $this->data['section_bar_active'] = 'Edit Group';

        $messages = array(
            'unique_name' => __('groups::lang.Group name is already taken')->get(ADM_LANG),
        );

        Validator::register('unique_name', function($attribute, $value, $parameters)
        {
            $group_name = Groups\Model\Group::where('id', '!=', Input::get('id'))
                                        ->where(function($query)
                                        {
                                            $query->where('name', '=', Input::get('name'));
                                        })
                                        ->get();
            
            if(empty($group_name))
                return true;

            return false;
        });

        $this->validation_rules = array(
            'name'  => 'required|min:4|max:30|unique_name',
        );

        $validation = Validator::make(Input::all(), $this->validation_rules, $messages)->speaks(ADM_LANG);
        $this->data['errors'] = $validation->errors;
        if( $validation->passes() )
        {
            $this->data['message'] = __('groups::lang.Group was successfully saved', array('group_name' => Input::get('name')))->get(ADM_LANG);
            $this->data['message_type'] = 'success';

            $group->name        = Input::get('name');
            $group->description = Input::get('description');

            $group->save();

            Event::fire('mwi.group_updated', array($group));

            return Redirect::to(ADM_URI.'/groups')->with($this->data);
        }
        else
        {
            $this->data['errors'] = $validation->errors;
        }
        
        $this->data['group'] = $group;
        return $this->theme->render('groups::backend.edit', $this->data);
    }

    public function get_new()
    {
        $this->data['section_bar_active'] = __('groups::lang.New Group')->get(ADM_LANG);
        return $this->theme->render('groups::backend.new', $this->data);
    }

    public function post_create()
    {
        $this->data['section_bar_active'] = __('groups::lang.New Group')->get(ADM_LANG);

        $messages = array(
            'unique_name_slug' => __('groups::lang.Name or Short name already taken')->get(ADM_LANG),
        );

        Validator::register('unique_name_slug', function($attribute, $value, $parameters)
        {
            $group_name = Groups\Model\Group::where('name', '=', Input::get('name'))
                                        ->or_where('slug', '=', Input::get('slug'))
                                        ->first();
            
            if(!isset($group_name) or empty($group_name))
                return true;

            return false;
        });

        $this->validation_rules = array(
            'name'  => 'required|min:4|max:30|unique_name_slug',
            'slug'  => 'required|min:4|max:30|alpha_dash'
        );

        $validation = Validator::make(Input::all(), $this->validation_rules, $messages)->speaks(ADM_LANG);
        $this->data['errors'] = $validation->errors;
        if( $validation->passes() )
        {
            $this->data['message'] = __('groups::lang.Group was successfully created')->get(ADM_LANG);
            $this->data['message_type'] = 'success';

            $group = new Groups\Model\Group();

            $group->name        = Input::get('name');
            $group->slug        = Input::get('slug');
            $group->description = Input::get('description');
            $group->save();

            Event::fire('mwi.group_created', array($group));

            return Redirect::to(ADM_URI.'/'.'groups')->with($this->data);
        }
        else
        {
            $this->data['errors'] = $validation->errors;
        }
        
        return $this->theme->render('groups::backend.new', $this->data);
    }

    public function delete_destroy($group_id)
    {
        if( ! ctype_digit($group_id))
        {
            $this->data['message'] = __('groups::lang.Invalid id')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::back()->with($this->data);
        }

        if(Bundle::exists('permissions'))
        {
            $group = Groups\Model\Group::with('permissions')->find($group_id);
        }
        else
        {
            $group = Groups\Model\Group::find($group_id);
        }

        if( !isset($group) or empty($group))
        {
            $this->data['message'] = __('groups::lang.Sorry cannot find group to delete')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::back()->with($this->data);
        }

        
        $users_group = Groups\Model\Group::where('slug', '=', 'users')->first();
        // if there is users on this group
        // set all users back to users group
        if(isset($users_group) and isset($users_group->id))
        {
            $update = Users\Model\User::where('group_id', '=', $group->id)->update(array('group_id' => $users_group->id));
        }

        if(isset($group->permissions) and !empty($group->permissions))
        {
            $group->permissions()->delete();
        }

        $group->delete();

        Event::fire('mwi.group_deleted', array($group));

        $this->data['message'] = __('groups::lang.Group was successfully destroyed')->get(ADM_LANG);
        $this->data['message_type'] = 'success';
        return Redirect::to(ADM_URI.'/groups')->with($this->data);
    }

    // used by ajax to check if the 
    // slug is already in use when 
    // creating a new users group
    public function post_check_slug()
    {
        $slug = Input::get('title');
        if(!isset($slug) or empty($slug))
        {
            return '';
        }
        $slug_check = Groups\Model\Group::where('slug', '=', $slug)->get(array('slug'));
        if( isset($slug_check) and ! empty($slug_check))
        {
            return 'slug::error::'.Str::slug($slug);
        }
        else
        {
            return Str::slug($slug);
        }
    }
}