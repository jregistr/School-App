<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MeetingTime;
use App\Models\Section;
use App\Services\FormatService;
use App\Services\PrecondResultsService;
use App\Services\ScheduleService;
use App\Util\C;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{

    private $service;
    private $res;
    private $formatService;

    /**
     * ScheduleController constructor.
     * @param ScheduleService $scheduleService
     * @param PrecondResultsService $precondResultsService
     * @param FormatService $formatService
     */
    public function __construct(ScheduleService $scheduleService, PrecondResultsService $precondResultsService,
                                FormatService $formatService)
    {
        $this->middleware('auth');
        $this->service = $scheduleService;
        $this->res = $precondResultsService;
        $this->formatService = $formatService;
    }

    public function getUserSchedules()
    {
        $userId = Auth::id();
        return $this->res->result($this->service->getUserSchedules($userId));
    }

    public function createSchedule(Request $request)
    {
        $studentId = Auth::id();
        $name = $request->input(C::NAME);
        if ($name != null) {
            return $this->res->result($this->service->addNewSchedule($studentId, $name));
        } else {
            return $this->res->missingParameter(C::NAME);
        }
    }

    public function updateSchedule(Request $request)
    {
        $studentId = Auth::id();
        $scheduleId = $request->input(C::SCHEDULE_ID);
        $name = $request->input(C::NAME);
        $selected = $request->input(C::IS_PRIMARY);

        if ($scheduleId != null) {
            return $this->res->result($this->service->updateScheduleInfo($studentId, $scheduleId, $name, $selected));
        } else {
            return $this->res->missingParameter(C::SCHEDULE_ID);
        }
    }

    public function deleteSchedule(Request $request)
    {
        $studentId = Auth::id();
        $scheduleId = $request->input(C::SCHEDULE_ID);

        if ($scheduleId != null) {
            return $this->res->result($this->service->deleteSchedule($studentId, $scheduleId));
        } else {
            return $this->res->missingParameter(C::SCHEDULE_ID);
        }
    }

    public function getScheduledCourses(Request $request)
    {
        $checks = $this->res->exist($request, [C::SCHEDULE_ID]);
        if ($checks[C::SUCCESS]) {
            $data = $this->service->getScheduledCourses(
                Auth::id(),
                $request->input(C::SCHEDULE_ID)
            );
            return $this->res->result(['courses' => $data != null ? $data : []]);
        } else {
            return $this->res->missingParameter($checks[C::NAME]);
        }
    }

    public function addScheduledCourse(Request $request)
    {
        $c = Course::find(1);
        $s = Section::find(1);
        $m = MeetingTime::find(1);
        return $this->res->result($this->formatService->formatScheduledCourseMeeting($c, $s, $m));
    }

    public function editScheduledCourse(Request $request)
    {
        $checks = $this->res->exist($request, [C::SCHEDULE_ID, C::COURSE]);
        if ($checks[C::SUCCESS]) {
            return $this->res->result($this->service->editScheduledCourse(
                Auth::id(),
                $request->input(C::SCHEDULE_ID),
                $request->input(C::COURSE)
            ));
        } else {
            return $this->res->missingParameter($checks[C::NAME]);
        }

//        $c = Course::find(1);
//        $s = Section::find(1);
//        $m = MeetingTime::find(1);
//        return $this->res->result($this->formatService->formatScheduledCourseMeeting($c, $s, $m));
    }

    public function deleteScheduledCourse(Request $request)
    {
        return $this->res->result(['deleted' => true]);
    }

}