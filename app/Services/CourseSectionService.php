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
    private $formatService;

    /**
     * CourseSectionService constructor.
     * @param CourseService $courseService - Course service.
     * @param FormatService $formatService
     */
    public function __construct(CourseService $courseService, FormatService $formatService)
    {
        $this->courseService = $courseService;
        $this->formatService = $formatService;
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
            foreach ($sections as $section) {
                $f = $this->formatService->formatSection($section);
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
                C::SUNDAY => intval($days[0]),
                C::MONDAY => intval($days[1]),
                C::TUESDAY => intval($days[2]),
                C::WEDNESDAY => intval($days[3]),
                C::THURSDAY => intval($days[4]),
                C::FRIDAY => intval($days[5]),
                C::SATURDAY => intval($days[6])
            ]);

            DB::table('sections_meeting_times')->insert(
                [C::SECTION_ID => $section->id, 'meeting_time_id' => $meeting->id, C::STUDENT_ID => $studentId]
            );

            return $this->formatService->formatSection($section, [$meeting]);
        } else {
            return null;
        }
    }

}