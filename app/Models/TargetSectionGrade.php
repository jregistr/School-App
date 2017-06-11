<?php

namespace App\Models;

use App\Util\C;

/**
 * Class TargetSectionGrade
 * @package App\Models
 * @property int student_id
 * @property int section_id
 * @property  float grade
 */
class TargetSectionGrade extends BaseModel
{

    public $timestamps = false;

    protected $fillable = [
        C::STUDENT_ID,
        C::SECTION_ID,
        C::GRADE
    ];

}