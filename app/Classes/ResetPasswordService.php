<?php

namespace App\Classes;

use Mail;
use App\Mail\ResetPasswordEmail;
use App\Models\CoreUsers;
use App\Models\CoreUsersActivation;

class ResetPasswordService
{
    protected $resendAfter = 24; // Sẽ gửi lại mã xác thực sau 24h nếu thực hiện sendActivationMail()
    protected $userActivation;

    public function __construct(CoreUsersActivation $userActivation)
    {
        $this->userActivation = $userActivation;
    }

    public function sendActivationMail($user)
    {
        if (!$this->shouldSend($user)) return;
        $token = $this->userActivation->createActivation($user);

        $user->reset_password_link = route('frontend.user.resetPassword', $token);
        $mailable = new ResetPasswordEmail($user);
        Mail::to($user->email)->send($mailable);
    }

    public function checkToken($token)
    {
        $activation = $this->userActivation->getActivationByToken($token);

        if ($activation === null) return null;

        return CoreUsers::find($activation->user_id);
    }

    public function deleteToken($token)
    {
        return $this->userActivation->deleteActivation($token);
    }

    private function shouldSend($user)
    {
        $activation = $this->userActivation->getActivation($user);
        return $activation === null || strtotime($activation->created_at) + 60 * 60 * $this->resendAfter < time();
    }

}
