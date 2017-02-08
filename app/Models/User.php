<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Validation\Validator;


class User extends Authenticatable
{
    use Notifiable;

    protected $rules = array();

    protected $errors;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function validateInput($data)
    {
        $v = Validator::make($data, $this->rules);
        $r = true;
        if ($v->fails()) {
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
