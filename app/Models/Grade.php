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

    public $timestamps = false;

    protected $fillable = [
        'assignment',
        'grade'
    ];

    public function weight()
    {
        return $this->belongsTo(Weight::class);
    }
}
