<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Validation\Validator;


/**
 * @property integer id
 * @property string first_name
 * @property string last_name
 * @property integer school_id
 * @property string email
 * @property string password
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $rules = array(
        'first_name' => 'required|alpha',
        'last_name' => 'required|alpha',
        'password' => 'required|min:8',
        'email' => 'required|email',
        'school_name' => 'alpha'
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
