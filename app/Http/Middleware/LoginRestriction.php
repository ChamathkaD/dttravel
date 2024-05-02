<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LoginRestriction
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_ban == 1) {
            Auth::guard('web')->logout();

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Your account is disabled. Please contact your site administrator.']);
        }

        return $next($request);

    }
}
