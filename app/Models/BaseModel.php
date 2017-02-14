<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BaseModel extends Model
{

    protected $rules;

    public function validator($data)
    {
        return Validator::make($data, $this->rules);
    }

}