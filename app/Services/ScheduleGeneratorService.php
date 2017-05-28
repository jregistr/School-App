<?php

namespace App\Services;

use App\Models\GeneratorList;
use App\Models\GeneratorListEntry;
use App\Models\MeetingTime;
use App\Models\Section;
use App\Models\User;
use App\Util\C;

class ScheduleGeneratorService
{

    private $formatService;

    /**
     * ScheduleGeneratorService constructor.
     * @param $formatService
     */
    public function __construct(FormatService $formatService)
    {
        $this->formatService = $formatService;
    }

    public function getGenerator($studentId)
    {
        $user = User::find($studentId);
        if ($user != null) {
            $generator = GeneratorList::where(C::STUDENT_ID, $studentId)->first();
            if ($generator == null) {
                $generator = GeneratorList::create([C::STUDENT_ID => $studentId]);
                $generator['courses'] = [];
            }
            return $generator;
        } else {
            return null;
        }
    }

    /**
     * @param $studentId - The id of the student.
     * @return GeneratorList | null - A generator list object with all associated courses and sections formatted.
     */
    public function getGeneratorWithCourses($studentId)
    {
        $generator = $this->getGenerator($studentId);
        if ($generator != null) {
            $courses = [];
            $entries = GeneratorListEntry::where(C::GENERATOR_LIST_ID, $generator->id)->get();
            foreach ($entries as $entry) {
                $section = $entry->section();
                $meeting = $entry->meeting();
                $course = $section->course();

                $formatted = $this->formatService->formatScheduledCourseMeeting($course, $section, $meeting);
                array_push($courses, $formatted);
            }

            $generator['courses'] = $courses;

            return $generator;
        } else {
            return null;
        }
    }

    /**
     * @param $studentId - The id of the student.
     * @param $sectionId - The section id.
     * @param $meetingId - The meeting id.
     * @return GeneratorList|null - The generator list with its associated courses or null if no list is found
     * or either section or meeting doesn't exist.
     */
    public function addToGenerator($studentId, $sectionId, $meetingId)
    {
        $gen = $this->getGenerator($studentId);
        $sec = Section::find($sectionId);
        $meet = MeetingTime::find($meetingId);
        if ($gen != null && $sec != null && $meet != null) {
            GeneratorListEntry::create([
                C::GENERATOR_LIST_ID => ($gen->id),
                C::SECTION_ID => $sectionId,
                C::MEETING_ID => $meetingId
            ]);
            return $this->getGeneratorWithCourses($studentId);
        } else {
            return null;
        }
    }

    /**
     * @param $studentId - The id of the student.
     * @param $sectionId - The section id to identify the row.
     * @param $meetingId - The meeting id to identify the row.
     * @return boolean - True if data was deleted.
     */
    public function deleteFromGenerator($studentId, $sectionId, $meetingId)
    {
        $gen = $this->getGenerator($studentId);
        if ($gen != null) {
            $status = GeneratorListEntry::where([
                [C::GENERATOR_LIST_ID, '=', $gen->id],
                [C::SECTION_ID, '=', $sectionId],
                [C::MEETING_ID, '=', $meetingId]
            ])->delete();
            return $status > 0;
        } else {
            return false;
        }
    }

    /**
     * @param $studentId - The id of the student.
     * @return bool - True if data was deleted.
     */
    public function deleteGenerator($studentId)
    {
        $status = GeneratorList::where(C::STUDENT_ID, $studentId)->delete();
        return $status > 0;
    }

}