<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Validator;

class BaseModel extends Model
{

    protected $rules;

    protected $errors;

    public function validateInput($data) {
        $v = Validator::make($data, $this->rules);
        $r = true;
        if($v->fails()) {
            $this->errors = $v->errors;
            $r = false;
        }
        return $r;
    }

    public function errors()
    {
        return $this->errors;
    }

}