<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Session;


use Illuminate\Support\Facades\Auth;
use Closure;

class BackendMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth()->guard('backend')->check()) {
            Session::put('KCFINDER.disabled', true);
            return redirect(Route('backend.login'));
        }

        $user = Auth()->guard('backend')->user();
        if ($user->account_type != 5) {
            return redirect()->route('user.index');
        }

        Session::put('KCFINDER.disabled', false);
        Session::put('KCFINDER.uploadURL', config('constants.upload_dir.url') . '/');
        Session::put('KCFINDER.uploadDir', config('constants.upload_dir.root') . '/');

        return $next($request);
    }
}
