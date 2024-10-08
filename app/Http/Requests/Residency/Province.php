<?php

namespace App\Http\Requests\Residency;

use Illuminate\Foundation\Http\FormRequest;

class Province extends FormRequest
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
                'name' => 'required|string|max:255',
                'country_id' => 'required|numeric|exists:countries,id',
            ];
        }

        if ($this->isMethod('get')) {
            return [
                // 'provinceId' => 'required|numeric'
            ];
        }
    }
}
