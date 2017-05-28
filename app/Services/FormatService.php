<?php

namespace App\Services;


use App\Models\Course;
use App\Models\MeetingTime;
use App\Models\Section;
use App\Util\C;

class FormatService
{

    /**
     * @param Section $section - The section to make formatted data with.
     * @param MeetingTime[] $meetings - (optional) - The meetings for the section.
     * @return Section - A section with it's meetings formatted.
     */
    public function formatSection($section, $meetings = null)
    {
        if ($meetings == null) {
            $meetings = $section->meetings()->get();
        }

        $formattedMeets = [];
        foreach ($meetings as $meeting) {
            $temp = [];
            $week = [];

            $temp[C::ID] = $meeting->id;
            $temp[C::START] = $meeting->start;
            $temp[C::END] = $meeting->end;
            $temp[C::LOCATION] = $meeting->location;

            $week[C::SUNDAY] = $meeting->sunday;
            $week[C::MONDAY] = $meeting->monday;
            $week[C::TUESDAY] = $meeting->tuesday;
            $week[C::WEDNESDAY] = $meeting->wednesday;
            $week[C::THURSDAY] = $meeting->thursday;
            $week[C::FRIDAY] = $meeting->friday;
            $week[C::SATURDAY] = $meeting->saturday;

            $temp['week'] = $week;
            array_push($formattedMeets, $temp);
        }

        $section['meetings'] = $formattedMeets;

        return $section;
    }

    /**
     * @param Course $course - Course to format.
     * @param Section $section - The selected section.
     * @param $meeting - The selected meeting.
     * @return Course - The formatted scheduled course.
     */
    public function formatScheduledCourseMeeting($course, $section, $meeting)
    {
        $formatedSection = $this->formatSection($section, [$meeting]);
        $course['section'] = $formatedSection;
        return $course;
    }

    /**
     * @param $course
     * @param $formattedSection
     * @return Course - The formatted course object.
     */
    public function formatScheduleCourse($course, $formattedSection)
    {
        $course['section'] = $formattedSection;
        return $course;
    }

}