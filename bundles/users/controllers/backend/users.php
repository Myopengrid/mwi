<?php

class Users_Backend_Users_Controller extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->data['section_bar'] = array(
            __('users::lang.Users')->get(ADM_LANG)     => URL::base().'/'.ADM_URI.'/users',
            __('users::lang.New User')->get(ADM_LANG)  => URL::base().'/'.ADM_URI.'/users/new'
        );

        $this->data['bar'] = array(
            'title' => __('users::lang.Users')->get(ADM_LANG),
            'url' => URL::base().'/'.ADM_URI.'/users',
            'description' => __('users::lang.Manage all users registered in the site')->get(ADM_LANG),
        );
    }

    public function get_index()
    {
        $records_per_page = Config::get('settings::core.records_per_page');

        if(Bundle::exists('groups'))
        {
            $users = Users\Model\User::with('group')->order_by('created_at', 'DESC')->paginate($records_per_page);
            $this->data['groups_dropdown'] = Groups\Model\Group::all();
        }
        else
        {
            $users = Users\Model\User::order_by('created_at', 'DESC')->paginate($records_per_page);
        }

        $this->data['users'] = $users->results;

        $this->data['pagination_links'] = $users->links();

        $this->data['section_bar_active'] = Lang::line('users::lang.Users')->get(ADM_LANG);
        
        return $this->theme->render('users::backend.index', $this->data);
    }

    // AJAX FILTER
    public function post_index()
    {
        $users = Users\Model\User::select('*')->order_by('created_at', 'DESC');

        $group = Input::get('f_group');
        $status = Input::get('f_status');
        $search = Input::get('f_keywords');

        if(isset($group) and $group != '0' and !empty($group))
        {
            $users->where('group_id', '=', $group);
        }
        if(isset($status) and $status != '0' and !empty($status))
        {
            $users->where('status', '=', $status);
        }
        if(isset($search) and !empty($search))
        {
            $users->where('avatar_first_name', 'LIKE', '%'.$search.'%');
            $users->or_where('avatar_last_name', 'LIKE', '%'.$search.'%');
            $users->or_where('email', 'LIKE', '%'.$search.'%');
        }
        
        $users  = $users->paginate(Config::get('settings::core.records_per_page'));
        $this->data['users'] = $users->results;

        $this->data['pagination_links'] = $users->links();

        return View::make('users::backend.partials._users_list', $this->data);
    }

    public function get_new()
    {
        $this->data['uuid'] = Mwi_Core::random_uuid();

        $this->data['section_bar_active'] = Lang::line('users::lang.New User')->get(ADM_LANG);

        if(Bundle::exists('groups'))
        {
            $this->data['groups_dropdown'] = Groups\Model\Group::all();
        }
        
        return $this->theme->render('users::backend.new', $this->data);
    }

    public function post_create()
    {
        $messages = array(
            'valid_uuid' => __('users::lang.Invalid UUID')->get(ADM_LANG),
            'unique_avatar_name' => __('users::lang.This combination of avatar first name and avatar last name is already in use')->get(ADM_LANG),
        );

        Validator::register('valid_uuid', function($attribute, $value, $parameters)
        {
            return (bool)preg_match('#^[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}$#', $value);
        });

        Validator::register('unique_avatar_name', function($attribute, $value, $parameters)
        {
            $user = Users\Model\User::where('avatar_first_name', '=', Input::get('avatar_first_name'))
                          ->where('avatar_last_name', '=', Input::get('avatar_last_name'))
                          ->first();

            if(isset($user) and !empty($user))
                return false;

            return true;
        });

        $rules = array(
            'uuid'              => 'required|min:3|max:50|valid_uuid|unique:users',
            'username'          => 'required|min:3|max:50|alpha_dash|unique:users',
            'avatar_first_name' => 'required|min:3|max:50',
            'avatar_last_name'  => 'required|min:3|max:50|unique_avatar_name',
            'email'             => 'required|email|unique:users',
            'status'            => 'required',
            'password'          => 'required|min:8',
        );

        $validation = Validator::make(Input::all(), $rules, $messages)->speaks(ADM_LANG);

        if ($validation->passes())
        {
            $group_id = Input::get('group_id');
            if(!isset($group_id) or empty($group_id))
            {
                $group_id = 0;
            }

            $password = Users\Helper::hash_password(Input::get('password'));

            $new_user = new Users\Model\User;
            
            $new_user->uuid              = Input::get('uuid');
            $new_user->username          = Input::get('username');
            $new_user->avatar_first_name = Input::get('avatar_first_name');
            $new_user->avatar_last_name  = Input::get('avatar_last_name');
            $new_user->email             = Input::get('email');
            $new_user->status            = Input::get('status');
            $new_user->password          = Hash::make(Input::get('password'));
            $new_user->hash              = $password['hash'];
            $new_user->salt              = $password['salt'];
            $new_user->group_id          = $group_id;
            $new_user->save();
            
            Event::fire('users.created', array($new_user));
            
            if($new_user->status == 'active')
            {
                Event::fire('users.activated', array($new_user));
            }

            $this->data['message'] = __('users::lang.User was successfully created')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
            return Redirect::to(ADM_URI.'/users')->with($this->data);
        }
        else
        {
            return Redirect::to(ADM_URI.'/users/new')
                            ->with_errors($validation)
                            ->with_input();
        }
    }

    public function post_action()
    {
        // Used by javascript
        // on users table to
        // enable or delete bulk users
        $action_to = Input::get('action_to');
        $action = Input::get('btnAction');

        if($action == 'delete')
        {
            if(is_array($action_to) and !empty($action_to))
            {   
                $users = Users\Model\User::where_in('id', $action_to)->get();

                $event_uuids = array();
                $users_to_delete = array();
                foreach ($users as $user) 
                {
                    if( !$user->is_core and $user->id != Auth::user()->id )
                    {
                        $event_uuids[$user->uuid] = $user;
                        $users_to_delete[] = $user->id;
                    }
                }
                
                if( !empty($users_to_delete) )
                {
                    $users = Users\Model\User::where_in('id', $users_to_delete)->delete();

                    Event::fire('users.deleted_many', array($action_to, $event_uuids));
                }

                $this->data['message'] = __('users::lang.User(s) were successfully destroyed')->get(ADM_LANG);
                $this->data['message_type'] = 'success';

            }
        }

        if($action == 'activate')
        {
            if(is_array($action_to) and !empty($action_to))
            {   

                Users\Model\User::where_in('id', $action_to)->update(array('status' => 'active'));

                $this->data['message'] = __('users::lang.User(s) were successfully activated')->get(ADM_LANG);
                $this->data['message_type'] = 'success';

                Event::fire('users.updated_many', array($action_to));
            }
        }

        return Redirect::back()->with($this->data);
    }

    public function get_preview($user_id)
    {
        return 'TODO';
    }

    public function get_edit($user_id)
    {
        if( ! ctype_digit($user_id))
        {
            $this->data['message'] = __('users::lang.Invalid id to edit user')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/users')->with($this->data);
        }

        $this->data['section_bar_active'] = __('users::lang.Edit')->get(ADM_LANG);
        
        $this->data['section_bar'] = array(
            __('users::lang.Users')->get(ADM_LANG)    => URL::base().'/'.ADM_URI.'/users',
            __('users::lang.New User')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/users/new',
            __('users::lang.Edit')->get(ADM_LANG)     => URL::base().'/'.ADM_URI.'/users/'.$user_id.'/edit',
        );
        
        if(\Bundle::exists('groups'))
        {
            $this->data['groups_dropdown'] = Groups\Model\Group::all();
        }

        $this->data['edit_user'] = Users\Model\User::find($user_id);

        if( ! isset($this->data['edit_user']) or empty($this->data['edit_user']))
        {
            $this->data['message'] = __('users::lang.Sorry can\'t find user to edit')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/users')->with($this->data);
        }

        return $this->theme->render('users::backend.edit', $this->data);
    }

    public function put_update($user_id)
    {
        if( ! ctype_digit($user_id))
        {
            $this->data['message'] = Lang::line('Invalid id to edit user')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/users')->with($this->data);
        }

        $edit_user = Users\Model\User::find($user_id);

        if( ! isset($edit_user) or empty($edit_user))
        {
            $this->data['message'] = Lang::line('Sorry can\'t find user to update')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/users')->with($this->data);
        }

        $messages = array(
            'valid_uuid' => Lang::line('Invalid UUID.')->get(ADM_LANG),
            'unique_avatar_name' => Lang::line('This combination of avatar first name and avatar last name is already in use')->get(ADM_LANG),
        );

        Validator::register('valid_uuid', function($attribute, $value, $parameters)
        {
            return (bool)preg_match('#^[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}$#', $value);
        });

        Validator::register('unique_avatar_name', function($attribute, $input_value) 
        {
            $user = Users\Model\User::where('uuid', '!=', Input::get('uuid'))
                          ->where(function($query) use ($attribute, $input_value)
                          {
                               $query->where($attribute, '=', $input_value);
                               $query->where('avatar_first_name', '=', Input::get('avatar_first_name'));
                           })
                          ->first();
            
            if(!isset($user) or empty($user))
                return true;

            return false;
        });

        $rules = array(
            'uuid'              => 'required|min:3|max:50|valid_uuid|unique:users,uuid,'.$edit_user->id,
            'username'          => 'required|min:3|max:50|alpha_dash|unique:users,username,'.$edit_user->id,
            'avatar_first_name' => 'required|min:3|max:50',
            'avatar_last_name'  => 'required|min:3|max:50|unique_avatar_name',
            'email'             => 'required|email|unique:users,email,'.$edit_user->id,
            'status'            => 'required',
            'password'          => 'min:8',
        );

        $validation = Validator::make(Input::all(), $rules, $messages);

        if ($validation->passes())
        {

            $group_id = Input::get('group_id');
            if( ! isset($group_id) or empty($group_id))
            {
                $group_id = 0;
            }
            
            $password = Input::get('password');
            if(isset($password) and ! empty($password))
            {
                $password = Users\Helper::hash_password(Input::get('password'));
                
                $edit_user->password          = Hash::make(Input::get('password'));
                $edit_user->hash              = $password['hash'];
                $edit_user->salt              = $password['salt'];
            }
            

            $edit_user->uuid              = Input::get('uuid');
            $edit_user->username          = Input::get('username');
            
            // Disable change of avatar name for now
            // 
            //$edit_user->avatar_first_name = Input::get('avatar_first_name');
            //$edit_user->avatar_last_name  = Input::get('avatar_last_name');
            
            $edit_user->email             = Input::get('email');
            $edit_user->status            = Input::get('status');
            $edit_user->group_id          = $group_id;
            $edit_user->save();

            Event::fire('users.updated', array($edit_user));

            $this->data['message'] = __('users::lang.User information was successfully updated', array('avatar_name' => Input::get('avatar_first_name').' '.Input::get('avatar_last_name') ))->get(ADM_LANG);
            $this->data['message_type'] = 'success';

            return Redirect::to(ADM_URI.'/users')->with($this->data);
        }
        else
        {
            return Redirect::to(ADM_URI.'/users/edit/'.$edit_user->id)
                            ->with_errors($validation)
                            ->with_input();
        }
    }

    public function delete_destroy($user_id)
    {
        if( ! ctype_digit($user_id))
        {
            $this->data['message'] = __('users::lang.Invalid id to delete user')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/users')->with($this->data);
        }
        
        $user = Users\Model\User::find($user_id);

        if( ! isset($user) or empty($user))
        {
            $this->data['message'] = __('users::lang.Sorry can\'t find user to delete')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/users')->with($this->data);
        }
        
        if($user->is_core)
        {
            $this->data['message'] = __('users::lang.Sorry can\'t delete a core user')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/users')->with($this->data);
        }
        
        $user->delete();

        Event::fire('users.deleted', array($user));


        //Session::flash('message', 'User was successfully deleted.');
        //Session::flash('message_type', 'success');
        $this->data['message'] = __('users::lang.User was successfully deleted')->get(ADM_LANG);
        $this->data['message_type'] = 'success';

        if(Request::ajax())
        {
            // the element that hold the user info 
            // to be removed by ajax
            $data = array(
                'flash_message'    => array(
                    'message_type' => 'success',
                    'messages'     => array(__('users::lang.User was successfully deleted')->get(ADM_LANG)),
                ),
                'hide'           => array(
                    'identifier' => 'tr#'.$user->id,
                ),
            );
            return json_encode($data);
        }
        
        return Redirect::to(ADM_URI.'/users')->with($this->data);
    }
}
