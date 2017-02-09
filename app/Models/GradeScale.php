<?php

namespace App\Models;

class GradeScale extends BaseModel
{

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function weights()
    {
        return $this->hasMany(Weight::class);
    }

}
