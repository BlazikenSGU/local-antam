<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Laravel\Socialite\Facades\Socialite;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function testgg()
    {
        return Socialite::driver('google')
            ->with(['hd' => config('services.google.redirect')])->redirect();
    }

    public function testfb()
    {
        return Socialite::driver('facebook')
            ->with(['hd' => config('services.facebook.redirect')])->redirect();
    }
}
