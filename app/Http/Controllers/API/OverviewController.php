<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\GradeService;
use App\Services\PrecondResultsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Util\C;


class OverviewController extends Controller
{

    private $gradeService;
    private $res;

    /**
     * OverviewController constructor.
     * @param GradeService $gradeService
     * @param PrecondResultsService $precondResultsService
     */
    public function __construct(GradeService $gradeService, PrecondResultsService $precondResultsService)
    {
        $this->middleware('auth');
        $this->gradeService = $gradeService;
        $this->res = $precondResultsService;
    }

    /**
     * Calculates and returns grade and gpa data based on the weights and grades for a particular section or
     * for sections in the student's current selected schedule.
     * @param Request $request - The http request object.
     * @return \Illuminate\Http\JsonResponse - A json response containing the grade summary data.
     */
    public function summary(Request $request)
    {
        $sectionId = $request->input(C::SECTION_ID);
        if ($sectionId == null) {
            $scheduleId = $request->input(C::SCHEDULE_ID);
            if ($scheduleId != null) {
                $data = $this->gradeService->summary($this->studentId(), $scheduleId);
                return $this->res->result(['summary' => $data]);
            } else {
                return $this->res->missingParameter(C::SCHEDULE_ID);
            }
        } else {
            return $this->res->result($this->gradeService->summaryForSection($this->studentId(), $sectionId));
        }
    }

    /**
     * Retrieves all the weights with their grades for a section.
     * @param Request $request - The http request object.
     * @return \Illuminate\Http\JsonResponse - A json containing the weights for the given section.
     */
    public function getWeight(Request $request)
    {
        $sectionId = $request->input(C::SECTION_ID);
        if ($sectionId != null) {
            return $this->res->result($this->gradeService->getWeights($this->studentId(), $sectionId));
        } else {
            return $this->res->missingParameter(C::SECTION_ID);
        }
    }

    /**
     * Adds a weight to a section.
     * @param Request $request - The http request object.
     * @return \Illuminate\Http\JsonResponse - A json containing the weights for the section.
     */
    public function addWeight(Request $request)
    {
        $checks = $this->res->exist($request, [C::SECTION_ID, C::CATEGORY, C::POINTS]);
        if ($checks[C::SUCCESS]) {
            $sectionId = $request->input(C::SECTION_ID);
            $category = $request->input(C::CATEGORY);
            $points = $request->input(C::POINTS);

            if ($points < 0 || $points > 100) {
                return $this->res->fail('Points should be between 0 and 100. Points:' . $points);
            } else {
                return $this->res->result($this->gradeService->addWeight($this->studentId(), $sectionId, $category, $points));
            }
        } else {
            return $this->res->missingParameter($checks[C::NAME]);
        }
    }

    /**
     * Updates the given fields for a weight.
     * @param Request $request - The http request object.
     * @return \Illuminate\Http\JsonResponse - A json containing the weights for the section.
     */
    public function updateWeight(Request $request)
    {
        $checks = $this->res->exist($request, [C::SECTION_ID, C::WEIGHT_ID]);
        if ($checks[C::SUCCESS]) {
            $weightId = $request->input(C::WEIGHT_ID);
            $sectionId = $request->input(C::SECTION_ID);
            $updates = [];

            $category = $request->input(C::CATEGORY);
            $points = $request->input(C::POINTS);

            if ($category != null) {
                $updates[C::CATEGORY] = $category;
            }

            if ($points != null) {
                $updates[C::POINTS] = $points;
            }

            if (count($updates) > 0) {

                if ($points != null && ($points < 0 || $points > 100)) {
                    return $this->res->fail('Points should be between 0 and 100. Points:' . $points);
                } else {
                    return $this->res->result($this->gradeService->updateWeight(
                        $this->studentId(), $sectionId, $weightId, $updates)
                    );
                }
            } else {
                return $this->res->fail('Not fields to update provided');
            }

        } else {
            return $this->res->missingParameter($checks[C::NAME]);
        }
    }

    /**
     * Deletes a weight and returns updated list of weights for the section.
     * @param Request $request - The http request object.
     * @return \Illuminate\Http\JsonResponse - A json containing the weights for the section.
     */
    public function deleteWeight(Request $request)
    {
        $checks = $this->res->exist($request, [C::SECTION_ID, C::WEIGHT_ID]);
        if ($checks[C::SUCCESS]) {
            $weightId = $request->input(C::WEIGHT_ID);
            $sectionId = $request->input(C::SECTION_ID);
            return $this->res->result($this->gradeService->deleteWeight($this->studentId(), $sectionId, $weightId));
        } else {
            return $this->res->missingParameter($checks[C::NAME]);
        }
    }

    /**
     * Gets the list of grades in a given weight.
     * @param Request $request - The http request object.
     * @return \Illuminate\Http\JsonResponse - A json containing the grades for the given weight.
     */
    public function getGrade(Request $request)
    {
        $check = $this->res->exist($request, [C::WEIGHT_ID]);
        if ($check[C::SUCCESS]) {
            $weightId = $request->input(C::WEIGHT_ID);
            $studentId = $this->studentId();
            return $this->res->result($this->gradeService->getGrades($studentId, $weightId));
        } else {
            return $this->res->missingParameter(C::WEIGHT_ID);
        }
    }

    /**
     * Adds a grade to a weight.
     * @param Request $request - The http request object.
     * @return \Illuminate\Http\JsonResponse - A json containing the grades.
     */
    public function addGrade(Request $request)
    {
        $checks = $this->res->exist($request, [C::WEIGHT_ID, C::ASSIGNMENT, C::GRADE]);
        if ($checks[C::SUCCESS]) {
            $weightId = $request->input(C::WEIGHT_ID);
            $assign = $request->input(C::ASSIGNMENT);
            $grade = $request->input(C::GRADE);

            if ($grade < 0 || $grade > 100) {
                return $this->res->fail('Grade should between 0 and 100. Grade:' . $grade);
            } else {
                return $this->res->result($this->gradeService->addGrade($this->studentId(), $weightId, $assign, $grade));
            }
        } else {
            return $this->res->missingParameter($checks[C::NAME]);
        }
    }

    /**
     * Updates the provided fields for a grade.
     * @param Request $request - The http request object.
     * @return \Illuminate\Http\JsonResponse - A json view of the grades for the weight for which a grade was updated.
     */
    public function updateGrade(Request $request)
    {
        $checks = $this->res->exist($request, [C::WEIGHT_ID, C::GRADE_ID]);
        if ($checks[C::SUCCESS]) {
            $weightId = $request->input(C::WEIGHT_ID);
            $gradeId = $request->input(C::GRADE_ID);
            $updates = [];

            $assign = $request->input(C::ASSIGNMENT);
            $grade = $request->input(C::GRADE);

            if ($assign != null) {
                $updates[C::ASSIGNMENT] = $assign;
            }

            if ($grade != null) {
                $updates[C::GRADE] = $grade;
            }

            if (count($updates) > 0) {

                if ($grade != null && ($grade < 0 || $grade > 100)) {
                    return $this->res->fail('Grade should between 0 and 100. Grade:' . $grade);
                } else {
                    return $this->res->result($this->gradeService->updateGrade($this->studentId(), $weightId, $gradeId, $updates));
                }
            } else {
                return $this->res->fail('Not fields to update provided');
            }
        } else {
            return $this->res->missingParameter($checks[C::NAME]);
        }
    }

    /**
     * Deletes a grade from a given weight.
     * @param Request $request - The http request object.
     * @return \Illuminate\Http\JsonResponse - A json response made from the weight the grade was deleted from.
     */
    public function deleteGrade(Request $request)
    {
        $checks = $this->res->exist($request, [C::WEIGHT_ID, C::GRADE_ID]);
        if ($checks[C::SUCCESS]) {
            $weightId = $request->input(C::WEIGHT_ID);
            $gradeId = $request->input(C::GRADE_ID);
            return $this->res->result($this->gradeService->deleteGrade($this->studentId(), $weightId, $gradeId));
        } else {
            return $this->res->missingParameter($checks[C::NAME]);
        }
    }

    /**
     * @return int|null - The id of the authenticated user.
     */
    private function studentId()
    {
        return Auth::id();
    }

}