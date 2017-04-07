<?php

namespace App\Models;


/**
 * @property integer id
 * @property integer school_id
 * @property string name
 * @property integer crn
 * @property integer credits
 */
class Course extends BaseModel
{

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

}
