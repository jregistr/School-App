<?php

namespace App\Services;


use App\Models\Course;
use App\Models\User;
use App\Util\C;

/**
 * Class CourseService - Operations on courses.
 * @package App\Services
 */
class CourseService
{

    /**
     * @param $studentId - The id of the student to get course information for.
     * @return array | [] - Returns an array of the courses and an array of their common categories.
     */
    public function getCourses($studentId)
    {
        $user = User::find($studentId);
        if ($user != null) {
            $courses = Course::where(C::STUDENT_ID, $studentId)
                ->orWhere(C::STUDENT_ID, null)
                ->where(C::SCHOOL_ID, $user->school_id)->get()->shuffle();

            $subjs = [];
            foreach ($courses as $course) {
                $name = $course->name;
                $course->name = ucwords(strtolower($name));
                $split = explode(" ", $name);
                if (count($split) == 2) {
                    if (!in_array($split[0], $subjs)) {
                        array_push($subjs, $split[0]);
                    }
                }
            }

            $outer = [];
            $outer['subjects'] = $subjs;
            $outer['courses'] = $courses;
            return $outer;
        } else {
            return [];
        }
    }

    /**
     * @param $studentId - The id of the student.
     * @param $name - The name of the course.
     * @param $crn - The crn for the course.
     * @param $credits - The credits for the course.
     * @return Course | null - The created course or null should the conditions not be met.
     */
    public function createCourse($studentId, $name, $crn, $credits)
    {
        $user = User::find($studentId);
        if ($user != null) {
            $sid = $user->school_id;
            $course = Course::create([C::NAME => $name, C::CRN => $crn, C::CREDITS => $credits,
                C::SCHOOL_ID => $sid]);
            return $course;
        } else {
            return null;
        }
    }

}