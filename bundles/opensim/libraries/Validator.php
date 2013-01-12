<?php namespace Opensim;

class Validator extends \Laravel\Validator {

    public function validate_vector($attribute, $value, $parameters)
    {
        $this->messages['vector'] = 'The vector is invalid.';
        return Vector::is_valid($value);
    }

}