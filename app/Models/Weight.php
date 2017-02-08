<?php

namespace App\Models;

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
