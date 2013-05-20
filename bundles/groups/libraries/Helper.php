<?php namespace Groups;

class Helper {
    
    /**
     * Return the application groups
     * 
     * @var array
     * @return mixed
     * 
     */
    public static function get_groups(Array $params = array('sys' => false))
    {

        $app_groups = \Groups\Model\Group::all();
        
        if($params['sys'] === true)
        {
            $groups = array(
                 0 => '-- Any --',
                -1 => 'Guests',
                -2 => 'Authenticated',
            );
        }

        foreach ($app_groups as $group) 
        {
            $groups[$group->id] = $group->name;
        }
        
        if(isset($params['to_json']) and $params['to_json'] === true)
        {
            return json_encode($groups);
        }
        
        return $groups;
    }

    /**
     * Sanitase group access options and check for
     * options precedence.
     * @param  Array  $restricted_to User restrict access options
     * @param  array  $params        The format to be returned
     * @return mixed                
     */
    public static function sanitaze(Array $restricted_to, Array $params = array())
    {
        // Set a safe result
        $result = array();

        if( !empty($restricted_to))
        {
            // Change valued to integer in case they
            // are strings
            $restricted_to = array_map('intval', $restricted_to);

            // If we have the "any" option selected for this
            // category lets keep only it's value since having
            // 'any' and 'users' restriction makes no sense.
            if( !in_array(0, $restricted_to))
            {
                // Check if we have restricted access for "authenticated"
                // since it precced any group. 
                // 
                // Makes no sense have
                // "autenticated" and "users" as group restriction
                // since if the user is autenticated she have the
                // authorization to procced. If I want permit access
                // only for group users, only select "users" since the
                // user must be authenticated in order to the system knows
                // her group so lets keep only "authenticated" option.
                if( in_array(-2, $restricted_to))
                {
                    $result = array(-2);
                }
                else
                {
                    $result = $restricted_to;
                }
            }
        }

        $result = array_filter($result, 'is_integer');

        // if we have only "any" as option
        // it gets cleared since array_filter
        // sees 0 as false and unset the value
        // if is the case lets put it back here
        if(empty($result))
        {
            $result = array(0);
        }

        if(isset($params['to_array']))
        {
            return $result;
        }
        elseif(isset($params['to_json']))
        {
            return json_encode($result);
        }
        else
        {
            return implode(',', $result);
        }
    }
}