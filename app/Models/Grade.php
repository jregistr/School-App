<?php

namespace App\Models;

/**
 * @property integer id
 * @property integer weight_id
 * @property float grade
 */
class Grade extends BaseModel
{

    protected $rules = array(
        'weight_id' => 'required|integer',
        'grade' => 'required|regex:/^(?:[0-9]{1,3})+(?:\.\d{1,2})?$/'
    );

    public function weight()
    {
        return $this->belongsTo(Weight::class);
    }
}
