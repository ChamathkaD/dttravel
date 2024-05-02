<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmEmailRequest;
use App\Http\Requests\ConfirmOtpRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Jobs\SendOtpEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    public function create()
    {
        return inertia('Auth/SentOTP');
    }

    public function store(ConfirmEmailRequest $request)
    {
        $otp_token = $this->userRepository->generateOTP($request->email);

        $user = User::where('email', $request->email)->first();

        $result = SendOtpEmail::dispatch($user, $otp_token);

        if ($result) {
            return inertia_location(route('user.show.confirm-otp', $user->id));
        } else {
            return back()->withErrors(['email' => 'Something went wrong!']);
        }
    }

    public function showConfirmOtp(User $user)
    {
        return inertia('Auth/ConfirmOTP', [
            'user' => $user,
        ]);
    }

    public function confirmOtp(ConfirmOtpRequest $request)
    {
        $user = User::find($request->id);

        $otp = $this->userRepository->validateOTP($request->validated('otp'), $user->email);

        if ($otp->status === true) {
            Auth::login($user);

            return redirect()->intended('/dashboard');
        } else {
            return back()->withErrors(['otp' => 'Your '.$otp->message]);
        }
    }
}
