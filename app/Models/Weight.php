<?php

namespace App\Models;

/**
 * @property integer id
 * @property integer grade_scale_id
 * @property string name
 * @property float value
 */
class Weight extends BaseModel
{
    protected $rules = array(
        'grade_scale' => 'required|integer',
        'name' => 'required|alpha|max:191',
        'value' => 'required|regex:/^(?:[0-9]{1,3})+(?:\.\d{1,2})?$/'
    );

    public function gradingScale()
    {
        return $this->belongsTo(GradeScale::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
