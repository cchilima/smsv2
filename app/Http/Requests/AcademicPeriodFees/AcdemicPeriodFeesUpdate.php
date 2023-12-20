<?php

namespace App\Http\Requests\AcademicPeriodFees;

use Illuminate\Foundation\Http\FormRequest;

class AcdemicPeriodFeesUpdate extends FormRequest
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
            'amount' => 'required|numeric',
            'fee_id'  => 'required|integer|exists:fees,id',
            'academic_period_id'  => 'required|integer|exists:academic_periods,id'
        ];
    }
}
