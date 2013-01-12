<?php

use Permissions\Model\Permission;

class Permissions_Backend_Permissions_Controller extends Admin_Controller {

    public $user_permission;

    public function __construct()
    {
        parent::__construct();
        
        $this->data['bar'] = array(
            'title'       => __('permissions::lang.Permissions')->get(ADM_LANG),
            'url'         => URL::base(). '/'.ADM_URI.'/permissions',
            'description' => __('permissions::lang.Control what type of users can see or manage certain sections within the site')->get(ADM_LANG),
            'buttons'     => array(),
        );

        $this->data['section_bar'] = array(
            __('permissions::lang.Permissions')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/permissions',
        );
    }

    public function get_index()
    {
        $this->data['section_bar_active'] = __('permissions::lang.Permissions')->get(ADM_LANG);
        $this->data['groups'] = Groups\Model\Group::all();
        return $this->theme->render('permissions::backend.index', $this->data);
    }

    public function get_edit($group_id)
    {
        if( ! ctype_digit($group_id))
        {
            $this->data['message'] = __('permissions::lang.Invalid group id provided')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/permissions/')->with($this->data);
        }

        $group = Groups\Model\Group::find($group_id);

        if( ! isset($group) or empty($group))
        {
            $this->data['message'] = __('permissions::lang.Sorry can\'t find group to edit it\'s permissions')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/permissions/')->with($this->data);
        }

        if( isset($group) and $group->slug == 'admin')
        {
            $this->data['message'] = __('permissions::lang.The Administration group has access to everything, and cannot be edited')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
            return Redirect::to(ADM_URI.'/permissions/')->with($this->data);
        }

        $this->data['section_bar'] = array(
            __('permissions::lang.Permissions')->get(ADM_LANG)            => URL::base().'/'.ADM_URI.'/permissions',
            __('permissions::lang.Edit Group Permissions')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/permissions/'.$group->id.'/edit',
        );
        
        $this->data['section_bar_active'] = __('permissions::lang.Edit Group Permissions')->get(ADM_LANG);

        $this->data['group_name'] = $group->name;
        $this->data['group_id'] = $group->id;

        $this->data['permission_groups'] = Permission::build_permission_groups($group->id);
        
        return $this->theme->render('permissions::backend.edit', $this->data);
    }

    public function put_update()
    {
        $post_modules = Input::get('modules');
        $group_id     = Input::get('group_id');
        $action       = Input::get('btnAction');
        $post_rules   = Input::get('module_roles');

        if(isset($group_id) and ! empty($group_id) and ctype_digit($group_id))
        {
            
            Permission::update_permissions($group_id, $post_rules, $post_modules);
            
            Event::fire('mwi.permissions_updated', array('modules' => $post_modules, 'group_id' => $group_id ));

            $this->data['message'] = __('permissions::lang.Permissions were successfully updated')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
                
            if($action == 'save')
            {
                return Redirect::to(ADM_URI.'/permissions/'.$group_id.'/edit')->with($this->data);
            }
            else
            {
                // 'save_exit' action
                return Redirect::to(ADM_URI.'/groups')->with($this->data);
            }
        }
        else
        {
            // module id's and group_id not posted
            // no changes made
            if($action == 'save')
            {
                return Redirect::to(ADM_URI.'/permissions/group/'.$group_id)->with($this->data);
            }
            else
            {
                // 'save_exit' action
                return Redirect::to(ADM_URI.'/groups')->with($this->data);
            }
        }
    }
}