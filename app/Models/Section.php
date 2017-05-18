<?php

namespace App\Models;

use App\Util\C;

/**
 * @property integer id
 * @property integer course_id
 * @property string instructors
 */
class Section extends BaseModel
{

    public $timestamps = false;

    protected $fillable = [
        C::COURSE_ID,
        C::STUDENT_ID,
        C::INSTRUCTORS
    ];

    /**
     * @return Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class)->first();
    }

    public function meetings()
    {
        return $this->belongsToMany(MeetingTime::class, 'sections_meeting_times');
    }

}
