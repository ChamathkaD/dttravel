<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;

class UserAccountValidationService
{
    final public function validateStepOne(Request $request): void
    {
        if (Route::current()->getName() == 'agents.step-one.store') {
            $request->validate([
                'first_name' => ['required', 'string', 'max:50', 'min:2'],
                'last_name' => ['required', 'string', 'max:50', 'min:2'],
                'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
                'contact' => ['required', 'phone:INTERNATIONAL'],
                'agent_commission_rate' => ['required', 'numeric'],
                'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            ]);
        }

        $request->validate([
            'contact' => ['required', 'phone:INTERNATIONAL'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

    }

    final public function validateStepTwo(Request $request): void
    {
        $request->validate([
            'business_name' => ['required', 'string'],
            'business_reg_no' => ['required', 'string'],
            'business_contact' => ['required', 'string', 'phone:INTERNATIONAL'],
            'business_email' => ['required', 'string'],
            'business_address' => ['required', 'string'],
            'business_country' => ['required', 'string'],
            'business_city' => ['required', 'string'],
            'logo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);
    }

    final public function validateUpdate(Request $request): void
    {
        $request->validate([
            'password' => ['required', 'string', Password::default(), 'confirmed'],
        ]);
    }
}
