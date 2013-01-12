<?php namespace Registration;

class User {
    
    public static function update_password($password, $user_id)
    {
        $user = null;
        
        if(ctype_digit($user_id))
        {
            $user = \Users\Model\User::find($user_id);
        }

        if( !is_null($user) and !empty($user) )
        {
            $hashed_password = \Users\Helper::hash_password($password);
            $user->hash              = $hashed_password['hash'];
            $user->salt              = $hashed_password['salt'];
            $user->password          = \Hash::make($password);
            $user->save();

            \Event::fire('users.updated', array($user));

            return true;
        }

        return false;
    }
}