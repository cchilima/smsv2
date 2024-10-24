<?php

namespace App\Http\Requests\departments;

use Illuminate\Foundation\Http\FormRequest;

class Department extends FormRequest
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
            'name' => 'required|string|unique:departments,name',
            'school_id' => 'required|integer|exists:schools,id'
        ];
    }
}
