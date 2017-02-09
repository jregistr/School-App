<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Validation\Validator;


/**
 * @property mixed id
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed school_id
 * @property mixed email
 * @property mixed password
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $rules = array(
        'first_name' => 'required|alpha_numeric',
        'last_name' => 'required|alpha_numeric',
        'password' => 'required|min:8',
        'email' => 'required|email'
    );

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
