<?php

namespace App\Models;

class Grade extends BaseModel
{
    public function weight()
    {
        return $this->belongsTo(Weight::class);
    }
}
