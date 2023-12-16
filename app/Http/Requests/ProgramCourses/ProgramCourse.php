<?php

namespace App\Http\Requests\ProgramCourses;

use Illuminate\Foundation\Http\FormRequest;

class ProgramCourse extends FormRequest
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
            'level_id' => 'required|integer|exists:course_levels,id',
            'course_id' => 'required|array',
            'course_id.*' => 'exists:courses,id',
            'program_id' => 'required|integer|exists:programs,id',
        ];
    }
}
