<?php

namespace App\Http\Requests\Prerequisites;

use Illuminate\Foundation\Http\FormRequest;

class Prerequisite extends FormRequest
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
            'course_id' => 'required|integer|exists:courses,id',
            'prerequisite_course_id' => 'required|array',
            'prerequisite_course_id.*' => 'exists:courses,id',
        ];
    }
}
