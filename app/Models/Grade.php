<?php

namespace App\Models;

/**
 * @property mixed id
 * @property mixed weight_id
 * @property mixed grade
 */
class Grade extends BaseModel
{
    public function weight()
    {
        return $this->belongsTo(Weight::class);
    }
}
