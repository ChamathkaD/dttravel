<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTravelerContactDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'contact' => ['required', 'string', 'max:20', 'unique:users,contact', 'phone:INTERNATIONAL'],
            'whatsapp' => ['required', 'string', 'max:20', 'unique:users,whatsapp', 'phone::INTERNATIONAL'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'address' => ['required', 'string'],
            'country' => ['required', 'string'],
            'state' => ['required', 'string'],
            'zip' => ['required', 'string'],
            'instagram' => ['nullable', 'string', 'unique:users,instagram'],
            'tiktok' => ['nullable', 'string', 'unique:users,tiktok'],
            'facebook' => ['nullable', 'string', 'unique:users,facebook'],
            'emergency_contact1' => ['required', 'string', 'max:20', 'unique:users,emergency_contact1', 'phone:INTERNATIONAL'],
            'emergency_contact2' => ['required', 'string', 'max:20', 'unique:users,emergency_contact2', 'phone:INTERNATIONAL'],
        ];
    }
}
