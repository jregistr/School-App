<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Services\CourseService;
use App\Services\PrecondResultsService;
use App\Util\C;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    private $res;
    private $courseService;

    /**
     * CourseController constructor.
     * @param PrecondResultsService $res
     * @param CourseService $courseService
     */
    public function __construct(PrecondResultsService $res, CourseService $courseService)
    {
        $this->middleware('auth');
        $this->res = $res;
        $this->courseService = $courseService;
    }

    public function getCourses(Request $request)
    {
        $student_id = $request->input(C::STUDENT_ID);
        $course_id = $request->input(C::COURSE_ID);
        if ($student_id != null) {
            return $this->res->result($this->courseService->getCourses($student_id));
        } else {
            return $this->res->missingParameter(C::STUDENT_ID);
        }
    }

}