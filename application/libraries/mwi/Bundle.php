<?php namespace Mwi;

class Bundle extends \Laravel\Bundle {
    
    /**
     * Parse an element identifier and return the bundle name and element.
     *
     * <code>
     *      // Returns array(null, 'admin.user')
     *      $element = Bundle::parse('admin.user');
     *
     *      // Parses "admin::user" and returns array('admin', 'user')
     *      $element = Bundle::parse('admin::user');
     * </code>
     *
     * @param  string  $identifier
     * @return array
     */
    public static function parse($identifier)
    {
        // The parsed elements are cached so we don't have to reparse them on each
        // subsequent request for the parsed element. So if we've already parsed
        // the given element, we'll just return the cached copy as the value.
        if (isset(static::$elements[$identifier]))
        {
            return static::$elements[$identifier];
        }

        if (strpos($identifier, '::') !== false)
        {
            $element = explode('::', $identifier);
        }
        // If no bundle is in the identifier, we will insert the default bundle
        // since classes like Config and Lang organize their items by bundle.
        // The application folder essentially behaves as a default bundle.
        else
        {
            $element = array(DEFAULT_BUNDLE, $identifier);
        }

        return static::$elements[$identifier] = $element;
    }
}