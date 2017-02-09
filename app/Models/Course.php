<?php

namespace App\Models;


/**
 * @property mixed id
 * @property mixed school_id
 * @property mixed name
 * @property mixed crn
 * @property mixed credits
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
