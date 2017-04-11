<?php

namespace App\Models;

use App\Util\C;

/**
 * Class Schedule
 * @package App\Models
 * @property integer id
 * @property integer student_id
 * @property boolean selected
 */
class Schedule extends BaseModel
{

    public $timestamps = false;

    protected $fillable = [
        C::STUDENT_ID,
        C::SELECTED
    ];

    public function user()
    {
        return $this->belongsTo(User::class, C::STUDENT_ID);
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }

    public function sectionsWithMeetings()
    {
        $sections = $this->sections()->get();
        if ($sections->count() > 0) {
            $outer = [];
            foreach ($sections as $section) {
                $course = $section->course();
                $course->section = $section;
                $meets = $section->meetings()->get();
                $section->meetings = $meets;
                array_push($outer, $course);
            }
            return $outer;
        } else {
            return [];
        }
    }

}
