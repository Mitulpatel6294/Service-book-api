<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
            "service_id" => "required|exists:services,id",
            "booking_date" => "required|date|after_or_equal:today",
            "booking_time" => "required|date_format:H:i",
            "notes" => "nullable|string|max:1000",
            "image" => "nullable|image|mimes:jpg,jpeg,png|max:2048",
            "quoted_price"=>"nullable|decimal",
            "quoted_duration"=>"nullable|integer"
        ];
    }
}
