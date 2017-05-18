<?php

namespace App\Services;

use App\Models\Course;
use App\Models\MeetingTime;
use App\Models\Section;
use App\Models\User;
use App\Util\C;
use Illuminate\Support\Facades\DB;

/**
 * Class CourseSectionService - Operations on sections.
 * @package App\Services
 */
class CourseSectionService
{
    private $courseService;

    /**
     * CourseSectionService constructor.
     * @param $courseService - Course service.
     */
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

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

            error_log($meeting->start);

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
     * @param int $studentId - The id of the student.
     * @param int $courseId - The id of the course.
     * @return array
     */
    public function getSections($studentId, $courseId)
    {
        $course = Course::find($courseId);
        $user = User::find($studentId);
        if ($user != null && $course != null) {
            $sections = Section::where(C::COURSE_ID, $courseId)
                ->where(function ($q) use ($studentId) {
                    $q->where(C::STUDENT_ID, $studentId)
                        ->orWhere(C::STUDENT_ID, null);
                })
                ->get();
            $formatted = [];
            error_log(count($sections));
            foreach ($sections as $section) {
                $f = $this->formatSection($section);
                array_push($formatted, $f);
            }
            return $formatted;
        } else {
            return [];
        }
    }

    /**
     * @param int $studentId - The id of the student.
     * @param int $course_id - The id of the course to add the section to.
     * @param string $instructors - The instructors.
     * @param string $location - The location of the course.
     * @param string $start - The start time.
     * @param string $end - The end time.
     * @param int[] $days - The days.
     * @return Section
     */
    public function createSection($studentId, $course_id, $instructors, $location, $start, $end, $days)
    {
        $user = User::find($studentId);
        $days = explode(',', $days);
        if ($user != null) {
            $section = Section::create([C::COURSE_ID => $course_id,
                C::INSTRUCTORS => $instructors, C::STUDENT_ID => $studentId]);
            $meeting = MeetingTime::create([
                C::STUDENT_ID => $studentId,
                C::START => $start,
                C::END => $end,
                C::LOCATION => $location,
                C::SUNDAY => $days[0],
                C::MONDAY => $days[1],
                C::TUESDAY => $days[2],
                C::WEDNESDAY => $days[3],
                C::THURSDAY => $days[4],
                C::FRIDAY => $days[5],
                C::SATURDAY => $days[6]
            ]);

            DB::table('sections_meeting_times')->insert(
                [C::SECTION_ID => $section->id, 'meeting_time_id' => $meeting->id, C::STUDENT_ID => $studentId]
            );

            return $this->formatSection($section, [$meeting]);
        } else {
            return null;
        }
    }

}