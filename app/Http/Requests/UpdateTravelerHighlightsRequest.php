<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelerHighlightsRequest extends FormRequest
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
            'drug_status' => ['required', 'string'],
            'food_status' => ['required', 'string'],
            'diet' => ['nullable', 'string', 'max:250', 'min:10'],
            'meditation' => ['nullable', 'string', 'max:250', 'min:10'],
            'particular' => ['nullable', 'string', 'max:250', 'min:10'],
            'note' => ['nullable', 'string', 'max:250', 'min:10'],
        ];
    }
}
