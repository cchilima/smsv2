<?php

namespace App\Http\Requests\MaritalStatuses;

use Illuminate\Foundation\Http\FormRequest;

class MaritalStatusUpdate extends FormRequest
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
            'status' => 'required|string|in:Married,Single,Widowed,Divorced,Separated',
        ];
    }

    public function messages()
    {
        return [
            'status.in' => 'Marital status must be one of the following: Married, Single, Widowed, Divorced, Separated',
        ];
    }
}
