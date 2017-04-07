<?php

namespace App\Models;

/**
 * @property integer id
 * @property integer section_id
 * @property integer student_id
 * @property string category
 * @property float points
 */
class Weight extends BaseModel
{
    public $timestamps = false;

    protected $rules = array(
        'student_id' => 'required|integer',
        'section_id' => 'required|integer',
        'category' => 'required|alpha|max:191',
        'points' => 'required|regex:/^(?:\d{1,4})(?:\.\d{1,2})?$/'
    );

    protected $fillable = [
        'category', 'points'
    ];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public static function getWithGrades($studentId, $sectionId)
    {
        $weightsRaw = Weight::where([['student_id', '=', $studentId],
            ['section_id', '=', $sectionId]])->get();
        $weights = $weightsRaw->count() > 0 ? $weightsRaw : null;

        if ($weights) {
            foreach ($weights as $weight) {
                $weight->grades = $weight->grades()->get();
            }
            return $weights;
        }

        return [];
    }

}
