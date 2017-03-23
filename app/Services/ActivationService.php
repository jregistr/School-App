<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ActivationService
{

    protected $tableName = 'account_confirmations';

    public function __construct()
    {

    }

    public function getActivationByToken($token)
    {
        $result = null;
        $confirmation = DB::table($this->tableName)->select('*')
            ->where('token', $token)->get();

        if ($confirmation->count() > 0) {
            $result = $confirmation[0];
        }
        return $result;
    }

    public function getActivationByUID($userId)
    {
        $result = null;
        $confirmation = DB::table($this->tableName)->select('*')
            ->where('user_id', $userId)->get();

        if ($confirmation->count() > 0) {
            $result = $confirmation[0];
        }
        return $result;
    }

    public function createActivation($user)
    {
        DB::table($this->tableName)->insert(
            ['user_id' => $user->id, 'token' => $this->makeToken(),
                'created' => new Carbon()
            ]
        );
    }

    public function deleteActivation($user)
    {
        DB::table($this->tableName)->where('user_id', $user->id)
            ->delete();
    }

    public function updateActivation($user)
    {
        DB::table($this->tableName)->where('user_id', $user->id)
            ->update(['token' => $this->makeToken()]);
    }

    public function activateUser($user)
    {
        $user->activated = true;
        $user->save();
    }

    public function sendActivationMail($user)
    {
        $result = false;

        $confData = $this->getActivationByUID($user->id);
        if ($confData != null) {
            $token = $confData->token;
            $link = route('user.activate', $token);

            Mail::send('email.emailconf',
                ['activationLink' => $link], function ($message) use ($user) {
                    $message->from('admin@myagenda.com', 'myAgenda Team');
                    $message->to($user->email);
                    $message->subject('Account Activation');
                });
            $result = true;
        }
        return $result;
    }

    public function isLessThan24($activation)
    {
        $created = $activation->created;
        $carbon = new Carbon($created);

        return $carbon->diffInHours() < 24;
    }

    private function makeToken()
    {
        return hash_hmac('sha256', str_random(256), config('app.key'));
    }

}