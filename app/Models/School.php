<?php

namespace App\Models;

/**
 * Class School
 * @property integer id
 * @property string name
 * @property string country
 * @property string state
 * @property string city
 * @package App\Models
 */
class School extends BaseModel
{

    protected $rules = array(
        'name' => 'required|max:191',
        'country' => 'max:3',
        'state' => 'max:2',
        'city' => 'max:191'
    );

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
