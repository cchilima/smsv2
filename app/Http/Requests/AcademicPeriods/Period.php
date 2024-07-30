<?php

namespace App\Http\Requests\AcademicPeriods;

use Illuminate\Foundation\Http\FormRequest;

class Period extends FormRequest
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
            'code' => 'required|string|unique:academic_periods',
            'name' => 'required|string',
            'ac_start_date' => 'required|date|after_or_equal:today',
            'ac_end_date' => 'required|date|after:ac_start_date',
            'period_type_id' => 'required|integer|exists:period_types,id'
        ];
    }
}
