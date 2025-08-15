<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CoreUsersActivation extends Model
{
    protected $table = 'lck_core_users_activations';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'token',
        'otp_code',
        'created_at',
        'count_input'
    ];



    protected function getToken()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    public function createActivation($user)
    {
        $activation = $this->getActivation($user);

        if (!$activation) {
            return $this->createToken($user);
        }
        return $this->regenerateToken($user);
    }

    private function regenerateToken($user)
    {
        $token = $this->getToken();
        $otp_code = $this->getOTP();
        CoreUsersActivation::where('user_id', $user->id)->update([
            'token'      => $token,
            'otp_code'   => $otp_code,
            'created_at' => new Carbon()
        ]);
        return $token;
    }

    private function createToken($user)
    {
        $token = $this->getToken();
        $otp_code = $this->getOTP();
        CoreUsersActivation::insert([
            'user_id'    => $user->id,
            'token'      => $token,
            'otp_code'   => $otp_code,
            'created_at' => new Carbon()
        ]);
        return $token;
    }

    public function getActivation($user)
    {
        return CoreUsersActivation::where('user_id', $user->id)->first();
    }

    public function getActivationByToken($token)
    {
        return CoreUsersActivation::where('token', $token)->first();
    }

    public function deleteActivation($token)
    {
        CoreUsersActivation::where('token', $token)->delete();
    }

    //otp code
    protected function _randomOTP($number = 6)
    {
        $generator = "1357902468";
        $result = "";
        for ($i = 1; $i <= $number; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        return $result;
    }

    protected function getOTP()
    {
        while (1) {
            $otp_code = $this->_randomOTP();
            $code = CoreUsersActivation::where('otp_code', $otp_code)->first();
            if (empty($code)) {
                return $otp_code;
            }
        }
    }

    public function createOTPActivation($user)
    {
        $activation = $this->getOTPActivation($user);

        if (!$activation) {
            return $this->createOTP($user);
        }
        return $this->regenerateOTP($user);
    }

    private function regenerateOTP($user)
    {
        $token = $this->getToken();
        $otp_code = $this->getOTP();
        CoreUsersActivation::where('user_id', $user->id)->update([
            'token'      => $token,
            'otp_code'   => $otp_code,
            'created_at' => new Carbon()
        ]);
        return $otp_code;
    }

    private function createOTP($user)
    {
        $token = $this->getToken();
        $otp_code = $this->getOTP();
        CoreUsersActivation::insert([
            'user_id'    => $user->id,
            'token'      => $token,
            'otp_code'   => $otp_code,
            'created_at' => new Carbon()
        ]);
        return $otp_code;
    }

    public function getOTPActivation($user)
    {
        return CoreUsersActivation::where('user_id', $user->id)->first();
    }

    public function getActivationByOTP($otp_code)
    {
        return CoreUsersActivation::where('otp_code', $otp_code)->first();
    }

    public function deleteOTPActivation($otp_code)
    {
        CoreUsersActivation::where('otp_code', $otp_code)->delete();
    }
}
