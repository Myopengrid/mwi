<?php namespace Permissions\Model;

use \Eloquent;

class Permission extends Eloquent {

    public static $timestamps = true;

    public function group()
    {
        return $this->has_many_and_belongs_to('Groups\Model\Group');
    }

    public function module()
    {
        return $this->has_many_and_belongs_to('Modules\Model\Module');
    }

    public static function build_permission_groups($group_id)
    {
        //Get the modules with permissions \
        //if module does not have a permission set for this group
        //get it from module // module needs to have rules and descriptions
        $modules = \Modules\Model\Module::with(array('permissions' => function($query) use ($group_id)
                                  {
                                      $query->where('group_id', '=', $group_id);
                                  }))
                                  ->where('enabled', '=', 1)
                                  ->get(array('id', 'slug', 'roles', 'name'));


        // Build permission groups for our view
        $permissions_groups = array();
        foreach ($modules as $module)
        {

            //root array
            $permissions_groups[$module->id]['slug'] = $module->slug;
            $permissions_groups[$module->id]['name'] = $module->name;

            if(isset($module->permissions) and !empty($module->permissions))
            {
                //\firephp::getinstance(true)->warn('from permisions');
                foreach ($module->permissions as $permission)
                {
                    $roles = json_decode($permission->roles);
                    if( isset($roles) and ! empty($roles) )
                    {
                        foreach($roles as $role)
                        {
                            $checked = $role->value == 1 ? 'checked="checked"' : '';
                            $permissions_groups[$module->id]['roles'][] = array(
                                'name'        => $role->name,
                                'description' => isset($role->description) ?: 'No description provided for this role.',
                                'slug'        => $role->slug,
                                'value'       => $role->value,
                                'checked'     => $checked,
                            );
                        }
                    }
                    else
                    {
                        //failed to decode json
                        $permissions_groups[$module->id]['roles'] = array();
                    }
                }
                
            }
            else
            {
                if(isset($module->roles) and !empty($module->roles))
                {
                    $roles = json_decode($module->roles);
                    if( isset($roles) and ! empty($roles) )
                    {
                        foreach($roles as $role)
                        {
                            $checked = $role->value == 1 ? 'checked="checked"' : '';
                            $permissions_groups[$module->id]['roles'][] = array(
                                'name'        => $role->name,
                                'description' => isset($role->description) ?: 'No description provided for this role.',
                                'slug'        => $role->slug,
                                'value'       => $role->value,
                                'checked'     => $checked,
                            );
                        }
                    }
                    else
                    {
                        //failed to decode json
                        $permissions_groups[$module->id]['roles'] = array();
                    }
                }
            }
            
        }

        return $permissions_groups;
    }

    public static function update_permissions($group_id, $post_rules, $post_modules)
    {

        // if post_modules is empty erase all rules
        if( ! isset($post_modules) or empty($post_modules))
        {
            $perm_to_erase = Permission::where('group_id', '=', $group_id)->delete();

            //VERY IMPORTANT
            return;
        }

        // Get the modules id name and roles
        $modules = \Modules\Model\Module::where_in('id', $post_modules)->get(array('id', 'slug', 'roles'));
            
            
        // If the permission has just one field and it was unckecked
        // erase it from the permissions table
        $perm_to_erase = Permission::where_not_in('module_id', $post_modules)
                                        ->where('group_id', '=', $group_id)
                                        ->get();
        
        if(isset($perm_to_erase) and ! empty($perm_to_erase))
        {
            foreach ($perm_to_erase as $permission) 
            {
                $permission->delete();
            }
        }
        unset($perm_to_erase);

        foreach ($modules as $module)
        {

            $permission = Permission::where('group_id', '=', $group_id)
                                      ->where('module_id', '=', $module->id)
                                      ->first();
            
            if(empty($permission))
            {
                $permission = new Permission();
                $permission->module_id = $module->id;
                $permission->group_id = $group_id;
                $permission->module_name = $module->slug;
                $permission->roles = $module->roles;
            }

            $permission->roles = json_decode($permission->roles, true);
            if($permission->roles == null)
            {
                $permission->roles = json_decode($module->roles, true);
            }

            $tmp_permission = $permission->roles;
            $result = array();
            foreach ($tmp_permission as $role)
            {
                $tmp['name'] = $role['name'];
                $tmp['description'] = isset($role['name']) ?: 'No description provided for this role.';
                $tmp['slug'] = $role['slug'];
                
                if(isset($post_rules[$module->id][$role['slug']]))
                {
                    $tmp['value'] = true;
                }
                else
                {
                    $tmp['value'] = false;
                }
                $result[] = $tmp;
            }
            
            $permissions_result = $permission->roles = json_encode($result);

            $permission->save();
        }
    }
}