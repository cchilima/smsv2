<?php

namespace App\Http\Requests\Students;

use Illuminate\Foundation\Http\FormRequest;

class NextOfKinInfo extends FormRequest
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
            
            'kin_full_name' => 'required|string|max:255',
            'kin_mobile' => 'required|string|max:20',
            'kin_telephone' => 'nullable|string|max:20',
            'kin_town_id' => 'required|exists:towns,id',
            'kin_province_id' => 'required|exists:provinces,id',
            'kin_country_id' => 'required|exists:countries,id',
            'kin_relationship_id' => 'required|exists:relationships,id',

        ];
    }
}
