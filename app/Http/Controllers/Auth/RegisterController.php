<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|alpha|max:191',
            'last_name' => 'required|alpha|max:191',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
            'school_id' => 'sometimes|integer|exists:schools,id'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $info = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ];

        $school_id = $data['school_id'];
        if ($school_id != null) {
            $info['school_id'] = $school_id;

//            if ($this->schoolExists($school_id)) {
//                $info['school_id'] = $school_id;
//            } else {
//                throw new ValidationException("School doesn't exist");
//            }

        }


        return User::create($info);
    }

//    private function schoolExists($schoolId)
//    {
//        $rVal = false;
//
//        $school = School::find($schoolId)->first();
//        if ($school != null) {
//            $rVal = true;
//        }
//
//        return $rVal;
//    }

}
