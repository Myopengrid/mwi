<?php
 
class Redirect extends Laravel\Redirect {
    
    /**
     * Add an item to the session flash data.
     *
     * This is useful for "passing" status messages or other data to the next request.
     *
     * <code>
     *      // Create a redirect response and flash to the session
     *      return Redirect::to('profile')->with('message', 'Welcome Back!');
     * </code>
     *
     * @param  string          $key
     * @param  mixed           $value
     * @return Redirect
     */
    public function with($key, $value = '')
    {
        if (Config::get('session.driver') == '')
        {
            throw new \Exception('A session driver must be set before setting flash data.');
        }

        if (is_array($key))
        {
            foreach ($key as $k => $v) 
            {
                if(is_string($v))
                {
                    Session::flash($k, $v);
                }
            }
        }
        else
        {
            Session::flash($key, $value);
        }

        return $this;
    }
}