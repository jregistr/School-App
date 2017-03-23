<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivationService;
use Illuminate\Support\Facades\Auth;

class ActivationController extends Controller
{

    protected $activationService;

    public function __construct(ActivationService $activationService)
    {
        $this->middleware('guest');
        $this->activationService = $activationService;
    }

    public function activateUser($confirm_token)
    {
        $rawToken = $confirm_token;
        if ($rawToken && ($conf = $this->activationService->getActivationByToken($rawToken)) != null) {
            $user = User::find($conf->user_id);
            $token = $conf->token;
            if ($token == $rawToken) {
                if ($this->activationService->isLessThan24($conf)) {
                    $this->activationService->activateUser($user);
                    $this->activationService->deleteActivation($user);
                    Auth::login($user);
                    return redirect('/profile');
                } else {
                    $this->activationService->updateActivation($user);
                    $this->activationService->sendActivationMail($user);
                    return redirect('/login')->with('status', 'Your confirmation code expired, we sent you another.');
                }
            } else {
                return redirect('/login')->with('status', 'Your confirmation did not match ours, we sent you a new one');
            }
        } else {
            return redirect('/login')->with('status', 'We did not find that confirmation token.');
        }
    }

}