<?php

namespace App\Models;

/**
 * @property integer student_id
 * @property integer weight_id
 * @property float grade
 * @property string assignment
 */
class Grade extends BaseModel
{

    protected $rules = array(
        'weight_id' => 'required|integer',
        'student_id' => 'required|integer',
        'assignment' => 'required|alpha_num',
        'grade' => 'required|regex:/^(?:[0-9]{1,4})(?:\.\d{1,2})?$/'
    );

    public function weight()
    {
        return $this->belongsTo(Weight::class);
    }
}
