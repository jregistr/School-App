<?php

namespace App\Models;

class Schedule extends BaseModel
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

//    public function sections()
//    {
//        return $this->belongsToMany(Section::class, 'schedule_section', 'section_id', 'schedule_id');
//    }

    public function sections()
    {
        Section::join('courses', 'sections.class_id', '=', 'courses.id');
    }

}
