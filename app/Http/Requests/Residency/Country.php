<?php

namespace App\Http\Requests\Residency;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Country extends FormRequest
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
        if ($this->isMethod('post') || $this->isMethod('put')) {
            return [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('countries', 'country')->ignore($this->country)
                ],
                'alpha_2_code' => [
                    'required',
                    'string',
                    'max:2',
                    Rule::unique('countries', 'alpha_2_code')->ignore($this->country)
                ],
                'alpha_3_code' => [
                    'required',
                    'string',
                    'max:3',
                    Rule::unique('countries', 'alpha_3_code')->ignore($this->country)
                ],
                'dialing_code' => [
                    'required',
                    'string',
                    'min:2',
                    'max:5',
                    Rule::unique('countries', 'dialing_code')->ignore($this->country)
                ],
                'nationality' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('countries', 'nationality')->ignore($this->country)
                ],
            ];
        }

        if ($this->isMethod('get')) {
            return [
                // 'countryId' => ''
            ];
        }
    }
}
