<?php

namespace App\Models;

/**
 * Class School
 * @property mixed id
 * @property mixed name
 * @property mixed country
 * @property mixed state
 * @property mixed city
 * @package App\Models
 */
class School extends BaseModel
{

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
