<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderProfileRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_name' => 'sometimes|required|string|max:255',
            'service_category' => 'sometimes|required|string|max:150',
            'description' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'location' => 'sometimes|required|string|max:255',
            'price_range' => 'nullable|string|max:100'
        ];
    }
}
