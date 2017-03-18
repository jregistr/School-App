<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * Class Schedule
 * @package App\Models
 * @property integer id
 * @property integer student_id
 * @property boolean selected
 *
 */
class Schedule extends BaseModel
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sections()
    {
        return Section::join('courses', 'sections.class_id', '=', 'courses.id')
                        ->whereIn('sections.id',
                                DB::table('schedule_section')
                                    ->select('section_id')
                                    ->where('schedule_id', $this->id)
                            );

    }

}
