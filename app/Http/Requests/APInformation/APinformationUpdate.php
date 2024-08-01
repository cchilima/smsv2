<?php

namespace App\Http\Requests\APInformation;

use Illuminate\Foundation\Http\FormRequest;

class APinformationUpdate extends FormRequest
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
            'academic_period_intake_id' => [
                'required',
                'integer',
                'exists:academic_period_intakes,id',
            ],

            'study_mode_id' => [
                'required',
                'integer',
                'exists:study_modes,id'
            ],

            'view_results_threshold' => [
                'required',
                'numeric',
                'between:1,100'
            ],

            'exam_slip_threshold' => [
                'required',
                'numeric',
                'between:1,100'
            ],

            'registration_threshold' => [
                'required',
                'numeric',
                'between:1,100'
            ],

            'late_registration_end_date' => [
                'required',
                'date',
                'after:late_registration_date'
            ],

            'late_registration_date' => [
                'required',
                'date',
                'after:registration_date'
            ],

            'registration_date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
        ];
    }
}
