<?php

namespace App\Http\Requests\Students;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdate extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female', 
            'email' => 'required|email|max:255|unique:users', 

    
            'date_of_birth' => 'required|date',
            'street_main' => 'required|string|max:255',
            'post_code' => 'nullable|string|max:20',
            'telephone' => 'required|string|max:20',
            'mobile' => 'required|string|max:20',
            'marital_status_id' => 'required|exists:marital_statuses,id', 
            'town_id' => 'required|exists:towns,id', 
            'province_id' => 'required|exists:provinces,id', 
            'country_id' => 'required|exists:countries,id', 
            'nrc' => 'required|string|max:20',
            'passport' => 'nullable|string|max:20',
    
            'program_id' => 'required|exists:programs,id', 
            'study_mode_id' => 'required|exists:study_modes,id', 
            'period_type_id' => 'required|exists:period_types,id', 
            'academic_period_intake_id' => 'required|exists:academic_period_intakes,id', 
            'course_level_id' => 'required|exists:course_levels,id', 
            'graduated' => 'required|boolean',
    


            'kin_full_name' => 'required|string|max:255',
            'kin_mobile' => 'required|string|max:20',
            'kin_telephone' => 'required|string|max:20',
            'kin_town_id' => 'required|exists:towns,id',
            'kin_province_id' => 'required|exists:provinces,id',
            'kin_country_id' => 'required|exists:countries,id',
            'kin_relationship_id' => 'required|exists:relationships,id', 
        ];
    }
}
