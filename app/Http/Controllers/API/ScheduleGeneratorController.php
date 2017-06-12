<?php

namespace App\Http\Controllers\API;


use App\Services\PrecondResultsService;
use App\Services\ScheduleGeneratorService;
use App\Services\ScheduleMakerService;
use App\Util\C;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleGeneratorController
{

    private $res;
    private $genService;
    private $makerService;

    /**
     * ScheduleGeneratorController constructor.
     * @param PrecondResultsService $res - Dependency on precondition service to render results.
     * @param ScheduleGeneratorService $genService - Dependency on the generator service.
     * @param ScheduleMakerService $makerService
     */
    public function __construct(PrecondResultsService $res, ScheduleGeneratorService $genService,
                                ScheduleMakerService $makerService)
    {
        $this->res = $res;
        $this->genService = $genService;
        $this->makerService = $makerService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGenerator()
    {
        $studentId = Auth::id();
        if ($studentId != null) {
            $gen = $this->genService->getGeneratorWithCourses($studentId);
            if ($gen != null) {
                return $this->res->result(['generator' => $gen]);
            } else {
                return $this->res->fail('no data found');
            }
        } else {
            return $this->res->missingParameter(C::STUDENT_ID);
        }
    }

    public function addToGenerator(Request $request)
    {
        $checks = $this->res->exist($request, [C::SECTION_ID, C::MEETING_ID]);
        $studentId = Auth::id();
        if ($checks[C::SUCCESS]) {
            $sectionId = $request->input(C::SECTION_ID);
            $meetingId = $request->input(C::MEETING_ID);
            $gen = $this->genService->addToGenerator($studentId, $sectionId, $meetingId);

            return $gen != null ? $this->res->result(['generator' => $gen]) : $this->res->fail('Failed to add');
        } else {
            return $this->res->missingParameter($checks[C::NAME]);
        }
    }

    public function deleteOnGenerator(Request $request)
    {
        $checks = $this->res->exist($request, [C::SECTION_ID, C::MEETING_ID]);
        $studentId = Auth::id();
        if ($checks[C::SUCCESS]) {
            $sectionId = $request->input(C::SECTION_ID);
            $meetingId = $request->input(C::MEETING_ID);
            $this->genService->deleteFromGenerator($studentId, $sectionId, $meetingId);
            return $this->res->result(['generator' => $this->genService->getGeneratorWithCourses($studentId)]);
        } else {
            $deleted = $this->genService->deleteGenerator($studentId);
            return $this->res->result(["deleted" => $deleted]);
        }
    }

    public function modifyGeneratorEntry(Request $request)
    {
        $studentId = Auth::id();
        $cLimit = $request->input(C::CREDIT_LIMIT);
        if ($cLimit != null) {
            return $this->res->result([C::CREDIT_LIMIT => $this->genService->updateCreditLimit($studentId, $cLimit)]);
        } else {
            $checks = $this->res->exist($request, [C::SECTION_ID, C::MEETING_ID, C::REQUIRED]);
            if ($checks[C::SUCCESS]) {
                $sectionId = $request->input(C::SECTION_ID);
                $meetingId = $request->input(C::MEETING_ID);
                $requiredValue = $request->input(C::REQUIRED);
                $this->genService->updateRequiredValue($studentId, $sectionId, $meetingId, $requiredValue);
                return $this->res->result(['generator' => $this->genService->getGeneratorWithCourses($studentId)]);
            } else {
                return $this->res->missingParameter($checks[C::NAME]);
            }
        }
    }

    public function generateSchedules()
    {
        $studentId = Auth::id();
        $schedules = $this->makerService->generateSchedules($studentId);
        return $this->res->result(['schedules' => $schedules]);
    }

}