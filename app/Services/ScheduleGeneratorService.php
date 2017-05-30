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
                $generator['entries'] = [];
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
            $formattedEntries = [];
            $entries = GeneratorListEntry::where(C::GENERATOR_LIST_ID, $generator->id)->get();
            foreach ($entries as $entry) {
                $section = $entry->section();
                $meeting = $entry->meeting();
                $course = $section->course();

                $formattedEntry = [C::REQUIRED => $entry->required];
                $formattedCourse = $this->formatService->formatScheduledCourseMeeting($course, $section, $meeting);

                $formattedEntry['course'] = $formattedCourse;
                array_push($formattedEntries, $formattedEntry);
            }

            $generator['entries'] = $formattedEntries;

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
            $exist = GeneratorListEntry::where
            ([
                [C::SECTION_ID, '=', $sectionId],
                [C::MEETING_ID, '=', $meetingId]
            ])->first();

            if ($exist == null) {
                GeneratorListEntry::create([
                    C::GENERATOR_LIST_ID => ($gen->id),
                    C::SECTION_ID => $sectionId,
                    C::MEETING_ID => $meetingId,
                    C::REQUIRED => false
                ]);
            }
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

    /**
     * @param $studentId - The id of the student.
     * @param $sectionId - The section id to identify the row.
     * @param $meetingId - The meeting id to identify the row.
     * @param $required - The update value.
     * @return bool - True if data was updated.
     */
    public function updateRequiredValue($studentId, $sectionId, $meetingId, $required)
    {
        $gen = $this->getGenerator($studentId);
        if ($gen != null) {
            $status = GeneratorListEntry::where([
                [C::GENERATOR_LIST_ID, '=', $gen->id],
                [C::SECTION_ID, '=', $sectionId],
                [C::MEETING_ID, '=', $meetingId]
            ])->update([C::REQUIRED => $required]);
            return $status > 0;
        } else {
            return false;
        }
    }

}