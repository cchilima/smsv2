<?php

namespace App\Http\Requests\Accomodation;

use Illuminate\Foundation\Http\FormRequest;

class Booking extends FormRequest
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
            'bed_space_id' => 'required|integer|exists:bed_spaces,id',
            'student_id' => 'required|integer|exists:students,id',
        ];
    }
}
