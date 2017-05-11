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

}