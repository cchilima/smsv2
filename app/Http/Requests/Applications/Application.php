<?php

namespace App\Http\Requests\Applications;

use Illuminate\Foundation\Http\FormRequest;

class Application extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nrc' => 'nullable|string|max:11|regex:/^\d{6}\/\d{2}\/\d$/',
            'passport' => 'nullable|string|max:20',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',

            'date_of_birth' => [
                'nullable',
                'date',
                'before_or_equal:' . (now()->subYears(16)->format('Y-m-d')), // Ensure applicant is at least 16 years old
                'after:' . (now()->subYears(99)->format('Y-m-d')), // Ensure applicant is not older than 99 yeard old
            ],

            'gender' => 'nullable|in:Male,Female',
            'address' => 'nullable|',
            'postal_code' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'application_date' => 'nullable|date',
            'status' => 'nullable|in:incomplete,pending,complete,rejected,accepted',
            'town_id' => 'nullable|exists:towns,id',
            'province_id' => 'nullable|exists:provinces,id',
            'country_id' => 'nullable|exists:countries,id',
            'program_id' => 'nullable|exists:programs,id',
            'period_type_id' => 'nullable|exists:period_types,id',
            'study_mode_id' => 'nullable|exists:study_modes,id',
            'academic_period_intake_id' => 'nullable|exists:academic_period_intakes,id',
            'attachment' => 'nullable|file|mimes:pdf|max:5120'
        ];
    }
}
