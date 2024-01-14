<?php

namespace App\Http\Requests\Students;

use Illuminate\Foundation\Http\FormRequest;

class AcademicInfo extends FormRequest
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

            'program_id' => 'required|exists:programs,id',
            'study_mode_id' => 'required|exists:study_modes,id',
            'period_type_id' => 'required|exists:period_types,id',
            'academic_period_intake_id' => 'required|exists:academic_period_intakes,id',
            'course_level_id' => 'required|exists:course_levels,id',
            'graduated' => 'required|boolean',

        ];
    }
}
