<?php

namespace App\Http\Requests\Sponsor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Sponsor extends FormRequest
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
                    'max:255'
                ],
                'description' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:255'
                ],
                'phone' => [
                    'nullable',
                    'max:255'
                ],
            ];
        }

        if ($this->isMethod('get')) {
            return [

            ];
        }
//        return [
//            //
//        ];
    }
}
