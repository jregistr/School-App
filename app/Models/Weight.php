<?php

namespace App\Models;

/**
 * @property mixed id
 * @property mixed grade_scale_id
 * @property mixed name
 * @property mixed value
 */
class Weight extends BaseModel
{
  public function gradingScale()
  {
      return $this->belongsTo(GradeScale::class);
  }

  public function grades()
  {
      return $this->hasMany(Grade::class);
  }
}
