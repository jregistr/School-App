<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Services\PrecondResultsService;
use App\Services\ScheduleService;
use App\Util\C;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{

    private $service;
    private $res;

    /**
     * ScheduleController constructor.
     * @param ScheduleService $scheduleService
     * @param PrecondResultsService $precondResultsService
     */
    public function __construct(ScheduleService $scheduleService, PrecondResultsService $precondResultsService)
    {
        $this->middleware('auth');
        $this->service = $scheduleService;
        $this->res = $precondResultsService;
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

}