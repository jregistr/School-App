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

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
