<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Services\CourseSectionService;
use App\Services\CourseService;
use App\Services\PrecondResultsService;
use App\Util\C;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    private $res;
    private $courseService;
    private $courseSectionService;

    /**
     * CourseController constructor.
     * @param PrecondResultsService $res
     * @param CourseService $courseService
     * @param CourseSectionService $courseSectionService
     */
    public function __construct(PrecondResultsService $res, CourseService $courseService,
                                CourseSectionService $courseSectionService)
    {
        $this->middleware('auth');
        $this->res = $res;
        $this->courseService = $courseService;
        $this->courseSectionService = $courseSectionService;
    }

    public function getCourses(Request $request)
    {
        $student_id = $request->input(C::STUDENT_ID);
        if ($student_id != null) {
            return $this->res->result($this->courseService->getCourses($student_id));
        } else {
            return $this->res->missingParameter(C::STUDENT_ID);
        }
    }

    public function createCourse(Request $request)
    {
        $check = $this->res->exist($request, [C::STUDENT_ID, C::NAME, C::CREDITS, C::CRN]);
        if ($check[C::SUCCESS]) {
            $studentId = $request->input(C::STUDENT_ID);
            $name = $request->input(C::NAME);
            $crn = $request->input(C::CRN);
            $credits = $request->input(C::CREDITS);
            $course = $this->courseService->createCourse($studentId, $name, $crn, $credits);
            return $this->res->result(['course' => $course]);
        } else {
            return $this->res->missingParameter($check[C::NAME]);
        }
    }

    public function getSections(Request $request)
    {
        $check = $this->res->exist($request, [C::STUDENT_ID, C::COURSE_ID]);

        if ($check[C::SUCCESS]) {
            $sections = $this->courseSectionService->getSections(
                $request->input(C::STUDENT_ID),
                $request->input(C::COURSE_ID)
            );
            return $this->res->result(['sections' => $sections]);
        } else {
            return $this->res->missingParameter($check[C::NAME]);
        }
    }

    public function createSection(Request $request)
    {
        $check = $this->res->exist($request, [
            C::STUDENT_ID, C::COURSE_ID, C::INSTRUCTORS, C::LOCATION,
            C::START, C::END, C::DAYS
        ]);

        if ($check[C::SUCCESS]) {
            $section = $this->courseSectionService->createSection(
                $request->input(C::STUDENT_ID),
                $request->input(C::COURSE_ID),
                $request->input(C::INSTRUCTORS),
                $request->input(C::LOCATION),
                $request->input(C::START),
                $request->input(C::END),
                $request->input(C::DAYS)
            );
            return $this->res->result(['sections' => $section != null ? $section : []]);
        } else {
            return $this->res->missingParameter($check[C::NAME]);
        }

    }

}