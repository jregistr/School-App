<?php

namespace App\Services;


use App\Models\Grade;
use App\Models\Weight;

class GradeService
{

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

}