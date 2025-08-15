<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class LogoutIfNewDay
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('backend')->check()) {
            $loginDate = Session::get('login_date');
            $today = Carbon::now()->format('Y-m-d');

            if ($loginDate && $loginDate !== $today) {
                Auth::guard('backend')->logout();
                Session::flush();
                return redirect()->route('backend.login')->with('msg', 'Phiên đăng nhập đã hết. Vui lòng đăng nhập lại.');
            }
        }

        return $next($request);
    }
}
