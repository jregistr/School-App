<?php

namespace App\Models;

use App\Util\C;
use Illuminate\Support\Facades\DB;

/**
 * Class Schedule
 * @package App\Models
 * @property integer id
 * @property integer student_id
 * @property string name
 * @property boolean selected
 */
class Schedule extends BaseModel
{

    public $timestamps = false;

    protected $fillable = [
        C::STUDENT_ID,
        C::IS_PRIMARY,
        C::NAME
    ];

    public function user()
    {
        return $this->belongsTo(User::class, C::STUDENT_ID);
    }

    public function courses($formatSectionFunc)
    {
        $scheduleSections = DB::table('schedule_section')->where('schedule_id', $this->id)->get();
        $outer = [];

        foreach ($scheduleSections as $scheduleSection) {
            $section = Section::find($scheduleSection->section_id);
            $meeting = MeetingTime::find($scheduleSection->meeting_time_id);
            $course = $section->course();

            $formattedSection = $formatSectionFunc($section, [$meeting]);

            $course->section = $formattedSection;
            array_push($outer, $course);
        }
        return $outer;
    }

}
