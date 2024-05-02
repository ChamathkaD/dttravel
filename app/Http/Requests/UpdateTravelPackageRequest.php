<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelPackageRequest extends FormRequest
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
            'travel_destination_id' => ['required', 'integer'],
            'travel_category_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'adults_price' => ['nullable', 'numeric'],
            'child_price' => ['nullable', 'numeric'],
            'discounted_price' => ['nullable', 'integer', 'max:100'],
            'tax' => ['nullable', 'numeric', 'between:0,99.99'],
            'min_pax' => ['required', 'integer', 'min:1'],
            'max_pax' => ['required', 'integer', 'min:1'],
            'is_charge_tax' => ['boolean'],
            'is_published' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keyphrase' => ['nullable', 'string', 'max:255'],
            'meta_image' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'travel_package_images' => ['nullable'],
            'travel_package_images.*.file' => ['mimes:jpeg,png,jpg'],
            'inclusions' => ['required'],
            'exclusions' => ['required'],
            'valueAdds' => ['required'],
            'conditions' => ['required'],
            'itineraries.*.title' => ['required', 'string', 'max:255'],
            'itineraries.*.description' => ['required', 'string'],
            'itineraries.*.accommodation' => ['nullable', 'string', 'max:255'],
            'itineraries.*.food' => ['nullable', 'string', 'max:255'],
            'itineraries.*.location' => ['nullable', 'string', 'max:255'],
        ];
    }
}
