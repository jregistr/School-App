<?php

namespace App\Services;


use App\Models\Course;
use App\Models\User;
use App\Util\C;

class CourseService
{

    public function getCourses($studentId)
    {
        $user = User::find($studentId);
        if ($user != null) {
            $courses = Course::where(C::STUDENT_ID, $studentId)
                ->orWhere(C::STUDENT_ID, null)
                ->where(C::SCHOOL_ID, $user->school_id)->get();

            $subjs = [];
            foreach ($courses as $course) {
                $name = $course->name;
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

}