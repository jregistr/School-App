<?php

namespace App\Models;

use App\Util\C;

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
        C::ASSIGNMENT,
        C::GRADE,
        C::WEIGHT_ID
    ];

    public function weight()
    {
        return $this->belongsTo(Weight::class);
    }
}
