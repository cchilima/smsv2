<?php

namespace App\Http\Requests\Sponsor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentSponsor extends FormRequest
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
                'sponsor_id' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'level' => [
                    'required',
                    'max:255'
                ]
            ];
        }

        if ($this->isMethod('get')) {
            return [
            ];

//        return [
//            //
//        ];
        }
    }
}
