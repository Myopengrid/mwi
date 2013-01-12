<?php namespace Mwi;

class Lang extends \Laravel\Lang {

    /**
     * Get the language line as a string.
     *
     * <code>
     *      // Get a language line
     *      $line = Lang::line('validation.required')->get();
     *
     *      // Get a language line in a specified language
     *      $line = Lang::line('validation.required')->get('sp');
     *
     *      // Return a default value if the line doesn't exist
     *      $line = Lang::line('validation.required')->get(null, 'Default');
     * </code>
     *
     * @param  string  $language
     * @param  string  $default
     * @return string
     */
    public function get($language = null, $default = null)
    {
        //ff($language); die();
        // If no default value is specified by the developer, we'll just return the
        // key of the language line. This should indicate which language line we
        // were attempting to render and is better than giving nothing back.
        if (is_null($default)) $default = $this->key;

        if (is_null($language)) $language = $this->language;

        list($bundle, $file, $line) = $this->parse($this->key);
        $identifier_as_line = $line;
        // If the file does not exist, we'll just return the default value that was
        // given to the method. The default value is also returned even when the
        // file exists and that file does not actually contain any lines.
        if ( ! static::load($bundle, $language, $file))
        {
            return value($identifier_as_line);
            //return value($default);
        }

        $lines = static::$lines[$bundle][$language][$file];

        $line = array_get($lines, $line, $identifier_as_line);
        //$line = array_get($lines, $line, $default);

        // If the line is not a string, it probably means the developer asked for
        // the entire language file and the value of the requested value will be
        // an array containing all of the lines in the file.
        if (is_string($line))
        {
            foreach ($this->replacements as $key => $value)
            {
                $line = str_replace(':'.$key, $value, $line);
            }
        }
        
        return $line;
    }

    /**
     * Parse a language key into its bundle, file, and line segments.
     *
     * Language lines follow a {bundle}::{file}.{line} naming convention.
     *
     * @param  string  $key
     * @return array
     */
    protected function parse($key)
    {
        $bundle = Bundle::name($key);

        $segments = explode('.', Bundle::element($key));

        // If there are not at least two segments in the array, it means that
        // the developer is requesting the entire language line array to be
        // returned. If that is the case, we'll make the item "null".
        if (count($segments) >= 2)
        {
            $line = implode('.', array_slice($segments, 1));
            return array($bundle, $segments[0], $line);
        }
        else
        {
            return array($bundle, $segments[0], null);
        }
    }

}