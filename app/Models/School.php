<?php

namespace App\Models;

class School extends BaseModel
{

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
