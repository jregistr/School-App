<?php

namespace App\Models;

/**
 * @property integer id
 * @property integer class_id
 * @property mixed start_time
 * @property mixed end_time
 * @property string days
 * @property string professor
 * @property integer building
 * @property integer room_number
 */
class Section extends BaseModel
{

    protected $rules = array(
        'class_id' => 'required|integer',
        'start_time' => 'required|date_format:"HH:MM"|before:end_time',
        'end_time' => 'required|date_format:"HH:MM"|after:start_time',
        'days' => 'required|max:191',
        'professor' => 'max:191',
        'building' => 'integer',
        'room_number' => 'integer'
    );

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gradingScale()
    {
        return $this->hasOne(GradeScale::class);
    }

}
