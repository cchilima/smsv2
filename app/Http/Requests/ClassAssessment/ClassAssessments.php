<?php

namespace App\Http\Requests\ClassAssessment;

use Illuminate\Foundation\Http\FormRequest;

class ClassAssessments extends FormRequest
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
            'assessment_type_id' => 'required|integer|exists:assessment_types,id',
            'academic_period_class_id' => 'required|integer|exists:academic_period_classes,id',
            'total' => 'required|integer',
            'end_date' =>'required|string|date'
        ];
    }
}
