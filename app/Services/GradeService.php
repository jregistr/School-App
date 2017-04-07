<?php

namespace App\Services;


use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Weight;
use App\Util\C;
use Illuminate\Support\Facades\DB;

class GradeService
{

    private $gradePointTable = [
        'A' => 4,
        'A-' => 3.7,
        'B+' => 3.3,
        'B' => 3,
        'B-' => 2.7,
        'C+' => 2.3,
        'C' => 2.0,
        'C-' => 1.7,
        'D+' => 1.3,
        'D' => 1.0,
        'D-' => 0.7,
        'F' => 0
    ];

    public function summary($studentId)
    {
        $selectedSchedule = Schedule::where([['student_id', '=', $studentId], ['selected', '=', true]])->get();
        if ($selectedSchedule != null && $selectedSchedule->count() > 0) {
            $selectedSchedule = $selectedSchedule[0];
        } else {
            $selectedSchedule = null;
        }

        if ($selectedSchedule != null) {
            $sectionIds = DB::table('schedule_section')
                ->select('section_id')
                ->where('schedule_id', '=', $selectedSchedule->id)
                ->get();
            if ($sectionIds != null && $sectionIds->count() > 0) {

                $data = [];
                $sectionAverages = [];

                foreach ($sectionIds as $sectionIdObj) {
                    $sectionId = $sectionIdObj->section_id;
                    $weights = $this->getWeights($studentId, $sectionId);
                    $average = 0;

                    if ($weights != null && $weights->count() > 0) {
                        $average = $this->classSummary($weights);
                    }
                    array_push($sectionAverages, [C::SECTION_ID => $sectionId, 'average' => $average]);
                }

                $data['overall'] = $this->overall($sectionAverages);
                $data['sectionAverages'] = $sectionAverages;
                return $data;
            }
        }

        return null;
    }

    public function getWeights($studentId, $sectionId)
    {
        return Weight::getWithGrades($studentId, $sectionId);
    }

    public function addWeight($studentId, $sectionId, $category, $points)
    {
        $weight = new Weight;
        $weight->student_id = $studentId;
        $weight->section_id = $sectionId;
        $weight->category = $category;
        $weight->points = $points;

        if ($weight->save()) {
            return $this->getWeights($studentId, $sectionId);
        } else {
            return null;
        }
    }

    public function updateWeight($studentId, $sectionId, $weightId, $updates)
    {
        Weight::find($weightId)
            ->update($updates);
        return $this->getWeights($studentId, $sectionId);
    }

    public function deleteWeight($studentId, $sectionId, $weightId)
    {
        Weight::find($weightId)->delete();
        return $this->getWeights($studentId, $sectionId);
    }

    public function getGrades($studentId, $weightId)
    {
        return Grade::where([['student_id', '=', $studentId], ['weight_id', '=', $weightId]])->get();
    }

    public function addGrade($studentId, $weightId, $assignment, $grade)
    {
        $gradeObj = new Grade;
        $gradeObj->student_id = $studentId;
        $gradeObj->weight_id = $weightId;
        $gradeObj->assignment = $assignment;
        $gradeObj->grade = $grade;

        if ($gradeObj->save()) {
            return $this->getGrades($studentId, $weightId);
        } else {
            return null;
        }

    }

    public function updateGrade($studentId, $weightId, $gradeId, $updates)
    {
        Grade::find($gradeId)
            ->update($updates);
        return $this->getGrades($studentId, $weightId);
    }

    public function deleteGrade($studentId, $weightId, $gradeId)
    {
        Grade::find($gradeId)->delete();
        return $this->getGrades($studentId, $weightId);
    }

    private function overall($classGradeSummaries)
    {
        return ['Hello' => 'World'];
    }

    private function gradePoint($gradeLetter, $credits)
    {

    }

    private function gradeToLetter($grade)
    {
        if ($grade >= 93) {
            return 'A';
        } else if ($grade >= 90 && $grade < 93) {
            return 'A-';
        } else if ($grade >= 87 && $grade < 90) {
            return 'B+';
        } else if ($grade >= 83 && $grade < 87) {
            return 'B';
        } else if ($grade >= 80 && $grade < 83) {
            return 'B-';
        } else if ($grade >= 77 && $grade < 80) {
            return 'C+';
        } else if ($grade >= 73 && $grade < 77) {
            return 'C';
        } else if ($grade >= 70 && $grade < 73) {
            return 'C-';
        } else if ($grade >= 67 && $grade < 70) {
            return 'D+';
        } else if ($grade >= 63 && $grade < 67) {
            return 'D';
        } else if ($grade >= 60 && $grade < 63) {
            return 'D-';
        } else {
            return 'F';
        }
    }

    private function classSummary($weights)
    {
        $collected = [];
        foreach ($weights as $weight) {
            $average = $this->averageWeightGrades($weight);
            $value = $weight->points;
            $collected[$value] = $average;
        }

        $total = 0;

        foreach ($collected as $worth => $grade) {
            $total += ($worth * $grade);
        }
        return $total / 100.0;
    }

    private function averageWeightGrades($weight)
    {
        $grades = $weight->grades;
        $count = 0;
        $total = 0;
        foreach ($grades as $grade) {
            $count += 1;
            $total += $grade->grade;
        }

        return $count > 0 ? (float)$total / $count : 0;
    }

}