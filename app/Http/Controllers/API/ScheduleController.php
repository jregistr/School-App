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

    public function getUserSchedules(Request $request)
    {
        $userId = Auth::id();
        $generated = $request->input(C::GENERATED);
        if ($generated)
            return $this->res->result($this->service->getUserGeneratedSchedules($userId));
        else
            return $this->res->result($this->service->getUserSchedules($userId));
    }

    public function createSchedule(Request $request)
    {
        $studentId = Auth::id();
        $name = $request->input(C::NAME);
        if ($name != null) {
            $gen = $request->input(C::GENERATED);
            $result = $gen == null ? $this->service->addNewSchedule($studentId, $name)
                : $this->service->addNewSchedule($studentId, $name, $gen);
            return $this->res->result($result);
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
            $generated = $request->input(C::GENERATED);
            if ($generated == null)
                return $this->res->result($this->service->updateScheduleInfo($studentId, $scheduleId, $name, $selected));
            else
                return $this->res->result($this->service->setAddedSchedule($studentId, $scheduleId, $generated));
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
        $checks = $this->res->exist($request, [C::SCHEDULE_ID, C::COURSE]);
        if ($checks[C::SUCCESS]) {
            $course = $this->service->addScheduledCourse(
                Auth::id(),
                $request->input(C::SCHEDULE_ID),
                $request->input(C::COURSE)
            );
            return $this->res->result([C::COURSE => $course]);
        } else {
            return $this->res->missingParameter($checks[C::NAME]);
        }
    }

    public function editScheduledCourse(Request $request)
    {
        $checks = $this->res->exist($request, [C::SCHEDULE_ID, C::COURSE]);
        if ($checks[C::SUCCESS]) {
            $course = $this->service->editScheduledCourse(
                Auth::id(),
                $request->input(C::SCHEDULE_ID),
                $request->input(C::COURSE)
            );
            return $this->res->result([C::COURSE => $course]);
        } else {
            return $this->res->missingParameter($checks[C::NAME]);
        }
    }

    public function deleteScheduledCourse(Request $request)
    {
        $checks = $this->res->exist($request, [C::SCHEDULE_ID, C::SECTION_ID, C::MEETING_ID]);
        if ($checks[C::SUCCESS]) {
            $val = $this->service->deleteScheduledCourse(
                $request->input(C::SCHEDULE_ID),
                $request->input(C::SECTION_ID),
                $request->input(C::MEETING_ID)
            );
            return $this->res->result(['deleted' => $val]);
        } else {
            return $this->res->missingParameter($checks[C::NAME]);
        }
    }

}