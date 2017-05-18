<?php

namespace App\Services;


use App\Models\Schedule;
use App\Util\C;

class ScheduleService
{

    private $courseSectionService;

    /**
     * ScheduleService constructor.
     * @param $courseSectionService
     */
    public function __construct(CourseSectionService $courseSectionService)
    {
        $this->courseSectionService = $courseSectionService;
    }


    public function getUserSchedules($studentId)
    {
        $schedules = Schedule::where(C::STUDENT_ID, $studentId)->get();
        return $schedules;
    }

    public function updateScheduleInfo($studentId, $scheduleId, $name, $primary)
    {
        if ($name || $primary) {
            $forId = Schedule::find($scheduleId);
            if ($forId != null) {
                if ($primary != null) {
                    $already = Schedule::where([
                        [C::STUDENT_ID, '=', $studentId],
                        [C::SELECTED, '=', 1]
                    ])->first();

                    if ($already != null) {
                        $already->selected = false;
                        $already->save();
                    }

                    $forId->selected = true;
                }

                if ($name != null) {
                    $forId->name = $name;
                }

                $forId->save();
            }
        }
        return $this->getUserSchedules($studentId);
    }

    public function deleteSchedule($studentId, $scheduleId)
    {
        Schedule::destroy($scheduleId);
        return $this->getUserSchedules($studentId);
    }

    public function getScheduledCourses($studentId, $scheduleId)
    {
        $schedule = Schedule::find($scheduleId);
        if ($schedule->student_id == $studentId) {
            return $schedule->courses(array($this->courseSectionService, 'formatSection'));
        } else {
            return null;
        }
    }

}