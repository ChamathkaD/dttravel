<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTravelerContactDetailsRequest extends FormRequest
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
            'contact' => ['required', 'string', 'max:20', Rule::unique('users', 'contact')->ignore($this->user->id), 'phone:INTERNATIONAL'],
            'whatsapp' => ['required', 'string', 'max:20', Rule::unique('users', 'whatsapp')->ignore($this->user->id), 'phone:INTERNATIONAL'],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users', 'email')->ignore($this->user->id)],
            'address' => ['required', 'string'],
            'country' => ['required', 'string'],
            'state' => ['required', 'string'],
            'zip' => ['required', 'string'],
            'instagram' => ['nullable', 'string', Rule::unique('users', 'instagram')->ignore($this->user->id)],
            'tiktok' => ['nullable', 'string', Rule::unique('users', 'tiktok')->ignore($this->user->id)],
            'facebook' => ['nullable', 'string', Rule::unique('users', 'facebook')->ignore($this->user->id)],
            'emergency_contact1' => ['required', 'string', 'max:20', Rule::unique('users', 'emergency_contact1')->ignore($this->user->id), 'phone:INTERNATIONAL'],
            'emergency_contact2' => ['required', 'string', 'max:20', Rule::unique('users', 'emergency_contact2')->ignore($this->user->id), 'phone:INTERNATIONAL'],
        ];
    }
}
