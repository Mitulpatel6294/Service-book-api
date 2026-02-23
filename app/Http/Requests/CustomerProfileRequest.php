<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerProfileRequest extends FormRequest
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
            "phone"=>"sometimes|required|digits:10|unique:customer_profiles,phone",
            "address"=>"sometimes|required|string|max:255",
            "city"=>"sometimes|required|string|max:100",
            "profile_image"=>"nullable|image|mimes:jpg,jpeg,png|max:2048"
        ];
    }
}
