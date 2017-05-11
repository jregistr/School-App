<?php

namespace App\Services;


use App\Models\Schedule;
use App\Util\C;

class ScheduleService
{

    public function getUserSchedules($studentId)
    {
        $schedules = [];
        $rawSchedules = Schedule::where(C::STUDENT_ID, $studentId)->get();

        if ($rawSchedules != null) {
            foreach ($rawSchedules as $rawSchedule) {
                $meetings = $rawSchedule->courses();
                if ($meetings != null & !empty($meetings)) {
                    $rawSchedule->courses = $meetings;
                } else {
                    $rawSchedule->courses = [];
                }
                array_push($schedules, $rawSchedule);
            }
        }

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

}