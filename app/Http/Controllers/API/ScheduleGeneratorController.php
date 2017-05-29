<?php

namespace App\Http\Controllers\API;


use App\Services\PrecondResultsService;
use App\Services\ScheduleGeneratorService;
use App\Util\C;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleGeneratorController
{

    private $res;
    private $genService;

    /**
     * ScheduleGeneratorController constructor.
     * @param PrecondResultsService $res - Dependency on precondition service to render results.
     * @param ScheduleGeneratorService $genService - Dependency on the generator service.
     */
    public function __construct(PrecondResultsService $res, ScheduleGeneratorService $genService)
    {
        $this->res = $res;
        $this->genService = $genService;
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
        if ($checks[C::SUCCESS]) {
            $studentId = Auth::id();
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
        $checks = $this->res->exist($request, [C::SECTION_ID, C::MEETING_ID]);
        if ($checks[C::SUCCESS]) {
            $studentId = Auth::id();
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