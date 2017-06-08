<?php

namespace App\Services;


use App\Models\Course;
use App\Models\MeetingTime;
use App\Models\Schedule;
use App\Models\Section;
use App\Util\C;
use Illuminate\Support\Facades\DB;


class ScheduleService
{

    private $formatterService;

    /**
     * ScheduleService constructor.
     * @param FormatService $formatService
     */
    public function __construct(FormatService $formatService)
    {
        $this->formatterService = $formatService;
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
            return $schedule->courses(array($this->formatterService, 'formatScheduledSection'));
        } else {
            return null;
        }
    }

    public function editScheduledCourse($studentId, $scheduleId, $queryJson)
    {
        $schedule = Schedule::find($scheduleId);
        $queryJsonParsed = json_decode($queryJson, true);
        $oldQueryCid = intval($queryJsonParsed[C::ID]);
        $oldQuerySection = $queryJsonParsed['section'];
        $oldQuerySid = intval($oldQuerySection[C::ID]);
        $oldQueryMeeting = $oldQuerySection['meeting'];
        $oldQueryWeek = $oldQueryMeeting['week'];
        $oldQueryMid = intval($oldQueryMeeting[C::ID]);

        $oldCourse = Course::find($oldQueryCid);
        $oldSection = Section::find($oldQuerySid);
        $oldMeeting = MeetingTime::find($oldQueryMid);

        error_log($oldQueryMid);

        if ($schedule != null) {
            $sIdToRemove = null;
            $mIdToRemove = null;

            $newCourse = null;
            $newSection = null;
            $newMeeting = null;

            if ($this->shouldUpdateCourse($queryJsonParsed, $oldCourse)) {
                if ($this->belongToStudent($oldCourse, $studentId)) {
                    $oldCourse->name = $queryJsonParsed[C::NAME];
                    $oldCourse->credits = $queryJsonParsed[C::CREDITS];
                    $oldCourse->crn = $queryJsonParsed[C::CRN];
                    $oldCourse->save();
                } else {
                    $sIdToRemove = $oldQuerySid;
                    $newCourse = Course::create([
                        C::NAME => $queryJsonParsed[C::NAME],
                        C::CREDITS => $queryJsonParsed[C::CREDITS],
                        C::CRN => $queryJsonParsed[C::CRN],
                        C::STUDENT_ID => $studentId
                    ]);
                }
            }

            $cIdTemp = $newCourse != null ? $newCourse->id : $oldQueryCid;
            if ($this->belongToStudent($oldSection, $studentId)) {//simply update the courseId
                $oldSection->course_id = $cIdTemp;
                $oldSection->instructors = $oldQuerySection[C::INSTRUCTORS];
                $oldSection->save();
            } else {//create a new one
                $sIdToRemove = $oldQuerySid;
                $newSection = Section::create([
                    C::STUDENT_ID => $studentId,
                    C::COURSE_ID => $cIdTemp,
                    C::INSTRUCTORS => $oldQuerySection[C::INSTRUCTORS]
                ]);
            }

            $sIdTemp = $newSection != null ? $newSection->id : $oldSection->id;
            if ($this->belongToStudent($oldMeeting, $studentId)) {
                DB::table('sections_meeting_times')
                    ->where(C::MEETING_TIME_ID, $oldMeeting->id)
                    ->update([C::SECTION_ID => $sIdTemp]);
                $meet = $oldMeeting;
                $meet->start = $oldQueryMeeting[C::START];
                $meet->end = $oldQueryMeeting[C::END];
                $meet->location = $oldQueryMeeting[C::LOCATION];
                $meet->sunday = $oldQueryWeek[C::SUNDAY];
                $meet->monday = $oldQueryWeek[C::MONDAY];
                $meet->tuesday = $oldQueryWeek[C::TUESDAY];
                $meet->wednesday = $oldQueryWeek[C::WEDNESDAY];
                $meet->thursday = $oldQueryWeek[C::THURSDAY];
                $meet->friday = $oldQueryWeek[C::FRIDAY];
                $meet->saturday = $oldQueryWeek[C::SATURDAY];
                $meet->save();
            } else {
                $mIdToRemove = $oldQueryMid;
                $newMeeting = MeetingTime::create([
                    C::START => $oldQueryMeeting[C::START],
                    C::END => $oldQueryMeeting[C::END],
                    C::LOCATION => $oldQueryMeeting[C::LOCATION],
                    C::STUDENT_ID => $studentId,
                    C::SUNDAY => $oldQueryWeek[C::SUNDAY],
                    C::MONDAY => $oldQueryWeek[C::MONDAY],
                    C::TUESDAY => $oldQueryWeek[C::TUESDAY],
                    C::WEDNESDAY => $oldQueryWeek[C::WEDNESDAY],
                    C::THURSDAY => $oldQueryWeek[C::THURSDAY],
                    C::FRIDAY => $oldQueryWeek[C::FRIDAY],
                    C::SATURDAY => $oldQueryWeek[C::SATURDAY]
                ]);
                DB::table('sections_meeting_times')
                    ->insert([
                        C::SECTION_ID => $sIdTemp,
                        C::MEETING_TIME_ID => $newMeeting->id,
                        C::STUDENT_ID => $studentId
                    ]);
            }

            $c = null;
            $s = null;
            $m = null;

            $c = $newCourse != null ? $newCourse : $oldCourse;
            $s = $newSection != null ? $newSection : $oldSection;
            $m = $newMeeting != null ? $newMeeting : $oldMeeting;

            $updates = [

            ];

            if ($sIdToRemove != null) {
                $updates[C::SECTION_ID] = $newSection->id;
            }

            if ($mIdToRemove != null) {
                $updates[C::MEETING_TIME_ID] = $newMeeting->id;
            }

            if(count($updates) > 0) {
                DB::table('schedule_section')
                    ->where([
                        [C::SCHEDULE_ID, '=', $scheduleId],
                        [C::SECTION_ID, '=', $oldQuerySid],
                        [C::MEETING_TIME_ID, '=', $oldQueryMid]
                    ])
                    ->update($updates);
            }
            return $this->formatterService->formatScheduledCourseMeeting($c, $s, $m);
        } else {
            return ['course' => null];
        }
    }

    private function belongToStudent($item, $studentId)
    {
        return $item->student_id == $studentId;
    }

    private function shouldUpdateCourse($queryCourse, $course)
    {
        $result = false;
        if ($queryCourse[C::NAME] != $course[C::NAME]) {
            $result = true;
        }
        if ((!$result) && $queryCourse[C::CREDITS] != $course[C::CREDITS]) {
            $result = true;
        }
        if ((!$result) && $queryCourse[C::CRN] != $course[C::CRN]) {
            $result = true;
        }
        return $result;
    }

}