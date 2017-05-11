<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Services\PrecondResultsService;
use App\Services\ScheduleService;
use App\Util\C;
use Illuminate\Http\Request;

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

    public function getUserSchedules(Request $request)
    {
        $userId = $request->input(C::STUDENT_ID);
        if ($userId != null) {
            return $this->res->result($this->service->getUserSchedules($userId));
        } else {
            return $this->res->missingParameter(C::STUDENT_ID);
        }
    }

}