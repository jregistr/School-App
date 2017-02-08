<?php

namespace App\Models;

class Section extends BaseModel
{

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function gradingScale()
    {
        return $this->hasOne(GradeScale::class);
    }

}
