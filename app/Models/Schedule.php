<?php

namespace App\Models;

class Schedule extends BaseModel
{
    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function sections()
    {
        $this->hasMany(Section::class);
    }

}
