<?php namespace Install;

use Laravel\Log;
use Laravel\Session;
use Laravel\Input;

class Validator extends \Laravel\Validator {

    public $adm_lang;

    public function validate_db_type($attribute, $value, $parameters)
    {
        $this->adm_lang = Session::get('adm_lang');
        
        $this->messages['db_type'] = '';

        if( !$this->validate_required($this->attributes['driver'], $this->attributes['driver']) )
        {
            $this->messages['db_type'] = __('install::lang.The database type is required')->get($this->adm_lang);
            return false;
        }
        
        if($value == 'sqlite')
        {
            if($this->validate_required($this->attributes['database'], $this->attributes['database']))
            {
                if($this->validate_alpha_dash($this->attributes['database'], $this->attributes['database']))
                {

                    return true;
                }
                else
                {
                    $this->messages['db_type'] = __('install::lang.The database name may only contain letters, numbers, underlines and dashes')->get($this->adm_lang);
                    return false;
                }
            }
            else
            {
                $this->messages['db_type'] = __('install::lang.The database name is required')->get($this->adm_lang);
                return false;
            }
        }
        
        else
        {
            if( !$this->validate_required($this->attributes['database'], $this->attributes['database']) )
            {
                $this->messages['db_type'] = __('install::lang.The database name is required')->get($this->adm_lang);
                return false;
            }

            if( !$this->validate_required($this->attributes['host'], $this->attributes['host']) )
            {
                $this->messages['db_type'] = __('install::lang.The database host is required')->get($this->adm_lang);
                return false;
            }
            
            if( !$this->validate_required($this->attributes['username'], $this->attributes['username']) )
            {
                $this->messages['db_type'] = __('install::lang.The database username is required')->get($this->adm_lang);
                return false;
            }

            if( !$this->validate_alpha_dash($this->attributes['prefix'], $this->attributes['prefix']) )
            {
                $this->messages['db_type'] = __('install::lang.The database prefix may only contain letters, numbers, underlines and dashes')->get($this->adm_lang);
                return false;
            }

            if(isset($this->attributes['port']) and !empty($this->attributes['port']))
            {
                $is_integer = filter_var($this->attributes['port'], FILTER_VALIDATE_INT) !== false;
                
                if( !$is_integer )
                {
                    $this->messages['db_type'] = __('install::lang.The database port may only contain an integer')->get($this->adm_lang);
                    return false;
                }
            }

            if($value == 'mysql')
            {
                if(!isset($this->attributes['port']) or empty($this->attributes['port']))
                {
                    $this->attributes['port'] = 3306;
                }

            }
            return true;
        }
    }

    public function validate_test_db_connection()
    {
        $this->adm_lang = Session::get('adm_lang');
        $this->messages['test_db_connection'] = __('install::lang.Unable to connect to database')->get($this->adm_lang);
        return Installer::test_db_connection(Input::all());
    }       
}