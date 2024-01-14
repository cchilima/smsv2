<?php

namespace App\Http\Requests\Students;

use Illuminate\Foundation\Http\FormRequest;

class PersonalInfo extends FormRequest
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

            'date_of_birth' => 'required|date',
            'street_main' => 'required|string|max:255',
            'post_code' => 'nullable|string|max:20',
            'telephone' => 'nullable|string|max:20',
            'mobile' => 'required|string|max:20',
            'marital_status_id' => 'required|exists:marital_statuses,id',
            'town_id' => 'required|exists:towns,id',
            'province_id' => 'required|exists:provinces,id',
            'country_id' => 'required|exists:countries,id',
            'nrc' => 'required|string|max:20',
            'passport' => 'nullable|string|max:20',

        ];
    }
}
