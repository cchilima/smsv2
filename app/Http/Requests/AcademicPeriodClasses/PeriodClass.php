<?php

namespace App\Http\Requests\AcademicPeriodClasses;

use Illuminate\Foundation\Http\FormRequest;

class PeriodClass extends FormRequest
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
            'instructor_id' => 'required|integer',
            'course_id' => 'required|integer',
            'academic_period_id' => 'required|integer',
        ];
    }
}
