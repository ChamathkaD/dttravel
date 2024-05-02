<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAgentBusinessRequest extends FormRequest
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
            'business_name' => ['required', 'string'],
            'business_reg_no' => ['required', 'string'],
            'business_contact' => ['required', 'string', 'phone:INTERNATIONAL'],
            'business_email' => ['required', 'string'],
            'address' => ['required', 'string'],
            'country' => ['required', 'string'],
            'business_city' => ['required', 'string'],
            'logo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ];
    }
}
