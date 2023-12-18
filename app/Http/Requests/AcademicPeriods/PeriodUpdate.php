<?php

namespace App\Http\Requests\AcademicPeriods;

use Illuminate\Foundation\Http\FormRequest;

class PeriodUpdate extends FormRequest
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
            'code' => 'required|string',
            'registration_date' => 'required|date',
            'late_registration_date' => 'required|date',
            'ac_start_date' => 'required|date',
            'ace_end_date' => 'required|date',
            'period_type_id' => 'required|integer',
            'program_intake_id' => 'required|integer',
            'type' => 'required|boolean',
            'study_mode_id' => 'required|integer',
        ];
    }
}
