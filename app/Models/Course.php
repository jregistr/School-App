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

    protected $rules = array(
        'school_name' => 'alpha|max:191',
        'name' => 'required|alpha|max:191',
        'crn' => 'integer',
        'credits' => 'integer'
    );

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

}
