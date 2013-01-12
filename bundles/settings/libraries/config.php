<?php namespace Settings;

use \Laravel;

class Config extends Laravel\Config {

    /**
     * Set a configuration item's value.
     *
     * <code>
     *      // Set the "session" configuration array
     *      Config::set('session', $array);
     *
     *      // Set a configuration option that belongs by a bundle
     *      Config::set('admin::names.first', 'Taylor');
     *
     *      // Set the "timezone" option in the "application" configuration file
     *      Config::set('application.timezone', 'UTC');
     * </code>
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public static function set($key, $value, $persistence = true)
    {
        list($bundle, $file, $item) = static::parse($key);

        static::load($bundle, $file);

        // If the item is null, it means the developer wishes to set the entire
        // configuration array to a given value, so we will pass the entire
        // array for the bundle into the array_set method.
        if (is_null($item))
        {
            // @TODO handle saving of
            // nested array configuration
            array_set(static::$items[$bundle], $file, $value);
        }
        else
        {
            if($persistence)
            {
                $setting = Model\Setting::where_slug($item)->first();
                if( ! empty($setting))
                {
                    array_set(static::$items[$bundle][$file], $item, $value);
                    $setting->value = $value;
                    $setting->save();
                }
                else
                {

                    \Log::error('"'.__CLASS__.'": Trying to persist a non existent setting "'.$item.'".');
                    return null;
                }
            }
            else
            {
                array_set(static::$items[$bundle][$file], $item, $value);
            }
        }
    }
}