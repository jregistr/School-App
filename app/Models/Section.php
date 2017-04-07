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
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
