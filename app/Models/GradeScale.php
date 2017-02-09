<?php

namespace App\Models;

/**
 * @property mixed id
 * @property mixed section_id
 * @property mixed scale_type
 */
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
