<?php

namespace App\Models;

use App\Util\C;

/**
 * @property integer id
 * @property integer school_id
 * @property string name
 * @property integer crn
 * @property integer credits
 * @property  integer student_id
 */
class Course extends BaseModel
{

    public $timestamps = false;

    protected $fillable = [
        C::NAME,
        C::CRN,
        C::CREDITS,
        C::SCHOOL_ID,
        C::STUDENT_ID
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    /**
     * @return Course
     */
    public function sectionsWithMeetings()
    {
        $course = $this;
        $sections = $this->sections()->get();
        foreach ($sections as $section) {
            $meetings = $section->meetings()->get();
            $section->meetings = $meetings;
        }
        $course[C::SECTIONS] = $sections;
        return $course;
    }

}
