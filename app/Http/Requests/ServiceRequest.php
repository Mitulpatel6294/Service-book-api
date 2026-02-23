<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
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
            'title' => [
                'required',
                'sometimes',
                'string',
                'max:255',
                Rule::unique('services', 'title')->where(fn($q) => $q->where('provider_id', auth()->id()))
            ],
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'duration_minutes' => 'sometimes|required|integer|min:1|max:600',
            'is_active' => 'nullable|boolean'
        ];
    }
}
