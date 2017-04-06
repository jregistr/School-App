<?php

namespace App\Models;

/**
 * @property integer id
 * @property integer grade_scale_id
 * @property string name
 * @property float value
 */
class Weight extends BaseModel
{
    protected $rules = array(
        'student_id' => 'required|integer',
        'section_id' => 'required|integer',
        'category' => 'required|alpha|max:191',
        'points' => 'required|regex:/^(?:\d{1,4})(?:\.\d{1,2})?$/'
    );

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function getWithGrades($studentId, $sectionId)
    {
        $weightsRaw = Weight::where([['student_id', '=', $studentId],
            ['section_id', '=', $sectionId]])->get();
        $weights = $weightsRaw->count() > 0 ? $weightsRaw : null;

        if ($weights) {
            foreach ($weights as $weight) {
                $weight -> grades = $weight->grades()->get();
            }
            return $weights;
        }

        return [];
    }

}
