<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class UserAccountAuthenticationService
{
    /**
     * Attempt to log in a user with the provided email and password.
     *
     * @param  string  $email The email address of the user.
     * @param  string  $password The password of the user.
     * @return RedirectResponse Redirects to the dashboard if login is successful.
     *
     * @throws ValidationException If validation fails.
     */
    public function attemptLogin(string $email, string $password): RedirectResponse
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            Session::regenerate();
        }

        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return back()->withErrors('error', 'Something went wrong!');
        }
    }
}
