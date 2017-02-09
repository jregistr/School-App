<?php

namespace App\Models;

/**
 * @property integer id
 * @property integer section_id
 * @property string scale_type
 */
class GradeScale extends BaseModel
{

    protected $rules = array(
        'section_id' => 'required|integer',
        'scale_type' => 'required|regex:/^(percent|points)$/'
    );

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function weights()
    {
        return $this->hasMany(Weight::class);
    }

}
