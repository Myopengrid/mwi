<?php namespace Opensim;

class Eloquent extends \Laravel\Database\Eloquent\Model {
    
    public static $not_autoincremented_key = false;

    public function __construct($attributes = array(), $exists = false)
    {
        parent::__construct($attributes, $exists);
    }

    /**
     * Save the model instance to the database.
     *
     * @return bool
     */
    public function save()
    {
        if ( !static::$not_autoincremented_key ) 
        {
            return parent::save();
        } 
        else 
        {
            if ( !$this->dirty() ) return true;

            if (static::$timestamps)
            {
                $this->timestamp();
            }

            $this->fire_event('saving');

            if ($this->exists)
            {
                $query = $this->query()->where(static::$key, '=', $this->get_key());

                $result = $query->update($this->get_dirty()) === 1;

                if ($result) $this->fire_event('updated');
            }

            // If the model does not exist, we will insert the record.
            // The parent::save needs to get the generated ID because this field is auto
            // incremented. With non-numeric or more precisely not auto-generated key we
            // do not need to get this ID because we have our primary key set in advance.
            // So we simply insert a new row in the table and do not set the key afterwards.
            else
            {
                $result = $this->query()->insert($this->attributes);

                $this->exists = $result;

                if ($result) $this->fire_event('created');
            }

            // After the model has been "saved", we will set the original attributes to
            // match the current attributes so the model will not be viewed as being
            // dirty and subsequent calls won't hit the database.
            $this->original = $this->attributes;

            if ($result)
            {
                $this->fire_event('saved');
            }

            return $result;
        }
    }
}