<?php

namespace App\Models;

/**
 * @property mixed id
 * @property mixed class_id
 * @property mixed start_time
 * @property mixed end_time
 * @property mixed days
 * @property mixed professor
 * @property mixed building
 * @property mixed room_number
 */
class Section extends BaseModel
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function gradingScale()
    {
        return $this->hasOne(GradeScale::class);
    }

}
