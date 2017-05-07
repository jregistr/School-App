<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivationService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $activationService;

    /**
     * Create a new controller instance.
     * @param $activationService
     */
    public function __construct(ActivationService $activationService)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->activationService = $activationService;
    }

    protected function authenticated(Request $request, $user)
    {
        if(!$user->activated) {
            auth()->logout();
            $conf = $this->activationService->getActivationByUID($user->id);
            if(!$conf) {
                $this->activationService->createActivation($user);
            }
            $this->activationService->sendActivationMail($user);
            return redirect('/login')->with('status', 'You need to confirm your email. We\'ve resent the email.');
        }

        return redirect()->intended($this->redirectPath());
    }


}
