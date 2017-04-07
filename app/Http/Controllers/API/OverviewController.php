<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\GradeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Util\C;


class OverviewController extends Controller
{

    private $gradeService;

    /**
     * OverviewController constructor.
     * @param GradeService $gradeService
     */
    public function __construct(GradeService $gradeService)
    {
        $this->middleware('auth');
        $this->gradeService = $gradeService;
    }

    public function summary(Request $request)
    {
        $data = $this->gradeService->summary($this->studentId());
        if ($data != null) {
            return $this->result($data);
        } else {
            return $this->fail('No summary');
        }
    }

    public function getWeight(Request $request)
    {
        $sectionId = $request->input(C::SECTION_ID);
        if ($sectionId != null) {
            return $this->result($this->gradeService->getWeights($this->studentId(), $sectionId));
        } else {
            return $this->missingParameter(C::SECTION_ID);
        }
    }

    public function addWeight(Request $request)
    {
        $checks = $this->exist($request, [C::SECTION_ID, C::CATEGORY, C::POINTS]);
        if ($checks[C::SUCCESS]) {
            $sectionId = $request->input(C::SECTION_ID);
            $category = $request->input(C::CATEGORY);
            $points = $request->input(C::POINTS);

            if ($points < 0 || $points > 100) {
                return $this->fail('Points should be between 0 and 100. Points:' . $points);
            } else {
                return $this->result($this->gradeService->addWeight($this->studentId(), $sectionId, $category, $points));
            }
        } else {
            return $this->missingParameter($checks[C::NAME]);
        }
    }

    public function updateWeight(Request $request)
    {
        $checks = $this->exist($request, [C::SECTION_ID, C::WEIGHT_ID]);
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
                    return $this->fail('Points should be between 0 and 100. Points:' . $points);
                } else {
                    return $this->result($this->gradeService->updateWeight(
                        $this->studentId(), $sectionId, $weightId, $updates)
                    );
                }
            } else {
                return $this->fail('Not fields to update provided');
            }

        } else {
            return $this->missingParameter($checks[C::NAME]);
        }
    }

    public function deleteWeight(Request $request)
    {
        $checks = $this->exist($request, [C::SECTION_ID, C::WEIGHT_ID]);
        if ($checks[C::SUCCESS]) {
            $weightId = $request->input(C::WEIGHT_ID);
            $sectionId = $request->input(C::SECTION_ID);
            return $this->result($this->gradeService->deleteWeight($this->studentId(), $sectionId, $weightId));
        } else {
            return $this->missingParameter($checks[C::NAME]);
        }
    }

    public function getGrade(Request $request)
    {
        $check = $this->exist($request, [C::WEIGHT_ID]);
        if ($check[C::SUCCESS]) {
            $weightId = $request->input(C::WEIGHT_ID);
            $studentId = $this->studentId();
            return $this->result($this->gradeService->getGrades($studentId, $weightId));
        } else {
            return $this->missingParameter(C::WEIGHT_ID);
        }
    }

    public function addGrade(Request $request)
    {
        $checks = $this->exist($request, [C::WEIGHT_ID, C::ASSIGNMENT, C::GRADE]);
        if ($checks[C::SUCCESS]) {
            $weightId = $request->input(C::WEIGHT_ID);
            $assign = $request->input(C::ASSIGNMENT);
            $grade = $request->input(C::GRADE);

            if ($grade < 0 || $grade > 100) {
                return $this->fail('Grade should between 0 and 100. Grade:' . $grade);
            } else {
                return $this->result($this->gradeService->addGrade($this->studentId(), $weightId, $assign, $grade));
            }
        } else {
            return $this->missingParameter($checks[C::NAME]);
        }
    }

    public function updateGrade(Request $request)
    {
        $checks = $this->exist($request, [C::WEIGHT_ID, C::GRADE_ID]);
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
                    return $this->fail('Grade should between 0 and 100. Grade:' . $grade);
                } else {
                    return $this->result($this->gradeService->updateGrade($this->studentId(), $weightId, $gradeId, $updates));
                }
            } else {
                return $this->fail('Not fields to update provided');
            }
        } else {
            return $this->missingParameter($checks[C::NAME]);
        }
    }

    public function deleteGrade(Request $request)
    {
        $checks = $this->exist($request, [C::WEIGHT_ID, C::GRADE_ID]);
        if ($checks[C::SUCCESS]) {
            $weightId = $request->input(C::WEIGHT_ID);
            $gradeId = $request->input(C::GRADE_ID);
            return $this->result($this->gradeService->deleteGrade($this->studentId(), $weightId, $gradeId));
        } else {
            return $this->missingParameter($checks[C::NAME]);
        }
    }

    private function studentId()
    {
        return Auth::id();
    }

    private function result($data)
    {
        return response()->json(
            [
                C::SUCCESS => true,
                C::DATA => $data
            ]
        );
    }

    private function missingParameter($paramName)
    {
        return response()->json(
            [C::SUCCESS => false,
                C::MESSAGE => 'Parameter ' . $paramName . ' is missing']
        );
    }

    private function fail($message)
    {
        return response()->json(
            [
                C::SUCCESS => false,
                C::MESSAGE => $message
            ]
        );
    }

    /**
     * @param Request $request
     * @param $parameters
     * @return array
     */
    private function exist($request, $parameters)
    {
        $result = [C::SUCCESS => true, C::NAME => 'none'];

        foreach ($parameters as $p) {
            if ($request->input($p) == null) {
                $result[C::SUCCESS] = false;
                $result[C::NAME] = $p;
            }
        }

        return $result;
    }

}