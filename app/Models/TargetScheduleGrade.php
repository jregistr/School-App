<?php

namespace App\Models;

use App\Util\C;

/**
 * Class TargetScheduleGrade
 * @package App\Models
 * @property int student_id
 * @property int schedule_id
 * @property  float grade
 */
class TargetScheduleGrade
{
    public $timestamps = false;

    protected $fillable = [
        C::STUDENT_ID,
        C::SCHEDULE_ID,
        C::GRADE
    ];

}