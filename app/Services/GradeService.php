<?php

namespace App\Services;


use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Weight;
use App\Util\C;
use Illuminate\Support\Facades\DB;

/**
 * Service to provide methods to interact with grades and weights.
 * Class GradeService
 * @package App\Services
 */
class GradeService
{

    /**
     * @var array - Array of the letter grades and their corresponding grade point.
     */
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

    /**
     * Calculates grades for all sections in the selected schedule as well as an overall summary.
     * @param int $studentId - The id of the student.
     * @return array|null - A key value pair array containing the summarized data or null if no calculations could be made.
     */
    public function summary($studentId)
    {
        $selectedSchedule = Schedule::where([[C::STUDENT_ID, '=', $studentId], [C::SELECTED, '=', true]])->get();
        if ($selectedSchedule != null && $selectedSchedule->count() > 0) {
            $selectedSchedule = $selectedSchedule[0];
        } else {
            $selectedSchedule = null;
        }

        if ($selectedSchedule != null) {
            $sectionIds = DB::table(C::TABLE_SCHEDULE_SECTION)
                ->select(C::SECTION_ID)
                ->where(C::SCHEDULE_ID, '=', $selectedSchedule->id)
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
                    array_push($sectionAverages, [C::SECTION_ID => $sectionId, C::AVERAGE => $average]);
                }

                $data[C::OVERALL] = $this->overall($sectionAverages);
                $data[C::SECTION_AVERAGES] = $sectionAverages;
                return $data;
            }
        }

        return null;
    }

    /**
     * Summarized grade data for a specific section.
     * @param int $studentId - The id of the student.
     * @param int $sectionId - The id of the section.
     * @return array - A key value pair array containing the summarized data for the section.
     */
    public function summaryForSection($studentId, $sectionId)
    {
        $weights = $this->getWeights($studentId, $sectionId);
        return [C::SECTION_ID => $sectionId, C::AVERAGE => $this->classSummary($weights)];
    }

    /**
     * Retrieves the weights and their grades.
     * @param int $studentId - The id of the student.
     * @param int $sectionId - The id of the section.
     * @return array|null - An array containing the weights and their grades.
     */
    public function getWeights($studentId, $sectionId)
    {
        return Weight::getWithGrades($studentId, $sectionId);
    }

    /**
     * Adds a weight to a section and returns the newly updated list of weights.
     * @param $studentId - The id of the student.
     * @param $sectionId - The id of the section.
     * @param $category - The category of the weight to add.
     * @param $points - The point value of the weight.
     * @return array|null - An array containing the weights and their grades.
     */
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

    /**
     * Updates the given fields in a weight.
     * @param $studentId - The id of the student.
     * @param $sectionId - The id of the section.
     * @param $weightId - The id of the weight to update.
     * @param $updates - The fields to update.
     * @return array|null - An array containing the weights and their grades.
     */
    public function updateWeight($studentId, $sectionId, $weightId, $updates)
    {
        Weight::find($weightId)
            ->update($updates);
        return $this->getWeights($studentId, $sectionId);
    }

    /**
     * Deletes a weight from a section.
     * @param $studentId - The id of the student.
     * @param $sectionId - The id of the section.
     * @param $weightId - The id of the weight to delete.
     * @return array|null - An array containing the weights and their grades.
     */
    public function deleteWeight($studentId, $sectionId, $weightId)
    {
        Weight::find($weightId)->delete();
        return $this->getWeights($studentId, $sectionId);
    }

    /**
     * Retrieves the grades for a given weight.
     * @param $studentId - The id of the student.
     * @param $weightId - The id of the weight.
     * @return array - A collection of the grades for the given weight.
     */
    public function getGrades($studentId, $weightId)
    {
        return Grade::where([[C::STUDENT_ID, '=', $studentId], [C::WEIGHT_ID, '=', $weightId]])->get();
    }

    /**
     * Adds a grade to a weight.
     * @param $studentId - The id of the student.
     * @param $weightId - The id of the weight.
     * @param $assignment - The name of the assignment.
     * @param $grade - The grade received on the assignment.
     * @return array|null - A collection of the grades for the given weight.
     */
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

    /**
     * Updates the provided fields of a grade.
     * @param $studentId - The id of the student.
     * @param $weightId - The id of the weight.
     * @param $gradeId - The id of the grade to update.
     * @param $updates - An array of the updates to be made.
     * @return array - A collection of the grades for the given weight.
     */
    public function updateGrade($studentId, $weightId, $gradeId, $updates)
    {
        Grade::find($gradeId)
            ->update($updates);
        return $this->getGrades($studentId, $weightId);
    }

    /**
     * Deletes a grade from a weight.
     * @param $studentId - The id of the student.
     * @param $weightId - The id of the weight.
     * @param $gradeId - The id of the grade to delete.
     * @return array - A collection of the grades for the given weight.
     */
    public function deleteGrade($studentId, $weightId, $gradeId)
    {
        Grade::find($gradeId)->delete();
        return $this->getGrades($studentId, $weightId);
    }

    /**
     * Summarizes overall grade data based on the given section grades.
     * @param $classGradeSummaries - The array of pairs of section id to average.
     * @return array - An array of pairs summarizing grades.
     */
    private function overall($classGradeSummaries)
    {
        $count = 0;
        $total = 0;
        $totalAttemptedCredits = 0;

        $classAverageTotal = 0;
        foreach ($classGradeSummaries as $classGradeSummary) {
            $count += 1;
            $sectionId = $classGradeSummary[C::SECTION_ID];
            $credits = Section::find($sectionId)->course()->get()[0]->credits;

            $totalAttemptedCredits += $credits;
            $classAverage = $classGradeSummary[C::AVERAGE];

            $letterGrade = $this->gradeToLetter($classAverage);

            $gradePoint = $this->letterToGradePoint($letterGrade, $credits);
            $total += $gradePoint;
            $classAverageTotal += $classAverage;
        }

        $gpa = $totalAttemptedCredits > 0 ? (float)$total / $totalAttemptedCredits : 0;
        $totalAverage = $count > 0 ? (float)$classAverageTotal / $count : 0;

        return [C::AVERAGE => $totalAverage, C::SEMESTER_GPA => $gpa];
    }

    /**
     * Converts a letter grade to grade points.
     * @param $gradeLetter - The letter grade attained.
     * @param $credits - The number of credits for the course.
     * @return float - The grade points for the letter grade.
     */
    private function letterToGradePoint($gradeLetter, $credits)
    {
        $basePoints = $this->gradePointTable[$gradeLetter];
        return $credits * $basePoints;
    }

    /**
     * Converts a number grade to a letter grade.
     * @param $grade - The number grade.
     * @return string - The letter grade appropriate for the number grade.
     */
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

    /**
     * Summarizes the grades for a class based on all the weights for that class.
     * @param $weights - The weights for the section.
     * @return float - The weighted average for the section.
     */
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

    /**
     * Calculates the average grade for a particular weight.
     * @param $weight - The weight.
     * @return float - The average for the weight.
     */
    private function averageWeightGrades($weight)
    {
        $grades = $weight->grades;
        $count = 0;
        $total = 0;
        foreach ($grades as $grade) {
            $count += 1;
            $total += $grade->grade;
        }

        return $count > 0 ? (float)$total / $count : 0.0;
    }

}