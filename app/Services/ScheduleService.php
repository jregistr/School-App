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

    /**
     * Updates name and/or primary parameters for a schedule.
     * @param $studentId - The id of the student.
     * @param $scheduleId - The schedule id.
     * @param $name - The update name of the schedule.
     * @param $primary - The update primary value.
     * @return Schedule[] - An array of the student's schedules.
     */
    public function updateScheduleInfo($studentId, $scheduleId, $name, $primary)
    {
        if ($name || $primary) {
            $forId = Schedule::find($scheduleId);
            if ($forId != null) {
                if ($primary != null) {
                    $already = Schedule::where([
                        [C::STUDENT_ID, '=', $studentId],
                        [C::IS_PRIMARY, '=', 1]
                    ])->first();

                    if ($already != null && $primary == 1) {
                        $already->is_primary = false;
                        $already->save();
                    }

                    $forId->is_primary = $primary;
                }

                if ($name != null) {
                    $forId->name = $name;
                }

                $forId->save();
            }
        }
        return $this->getUserSchedules($studentId);
    }

    /**
     * @param $studentId - The id of the student.
     * @return array - Array containing the organized schedules.
     */
    public function getUserSchedules($studentId)
    {
        $filter = [
            [C::STUDENT_ID, '=', $studentId],
            [C::ADDED, '=', 1],
            [C::IS_PRIMARY, 1]
        ];

        $result = [];
        $querySel = Schedule::where($filter)->first();

        array_pop($filter);
        array_push($filter, [C::IS_PRIMARY, 0]);

        $queryRemain = Schedule::where($filter)->orderBy('name', 'desc')->get();
        $result['primary'] = $querySel;
        $result['schedules'] = $queryRemain;
        $outer = ["schedules" => $result];
        return $outer;
    }

    public function addNewSchedule($studentId, $name)
    {
        $first = Schedule::where(C::STUDENT_ID, $studentId)->first() == null;
        $schedule = Schedule::create([
            C::NAME => $name,
            C::STUDENT_ID => $studentId,
            C::ADDED => 1,
            C::IS_PRIMARY => $first ? 1 : 0
        ]);
        return ["schedule" => $schedule];
    }

    /**
     * Deletes a student's schedule.
     * @param $studentId - The student's id.
     * @param $scheduleId - The schedule id.
     * @return Schedule[] - An array of the schedules.
     */
    public function deleteSchedule($studentId, $scheduleId)
    {
        Schedule::destroy($scheduleId);
        return $this->getUserSchedules($studentId);
    }

    /**
     * @param $studentId - The id of the student.
     * @param $scheduleId - The id of the schedule.
     * @return [] - courses formatted for schedule rendering.
     */
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