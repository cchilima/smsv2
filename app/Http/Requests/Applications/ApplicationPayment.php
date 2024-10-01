<?php

namespace App\Http\Requests\Applications;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationPayment extends FormRequest
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
            'applicant' => 'required|string|max:255|exists:applicants,applicant_code',
            'amount' => 'required|numeric|min:1',
            'reference' => 'nullable|string|unique:applicant_payments,reference',
            'payment_method_id' => 'required|exists:payment_methods,id'
        ];
    }
}
