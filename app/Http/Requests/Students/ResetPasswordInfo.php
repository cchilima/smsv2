<?php

namespace App\Http\Requests\Students;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordInfo extends FormRequest
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
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|min:8',
            'force_password_reset' => 'string|max:3|nullable',
            'user_id' => 'required',
        ];
        
    }
}
