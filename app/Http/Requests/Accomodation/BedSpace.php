<?php

namespace App\Http\Requests\Accomodation;

use App\Rules\ValidBedCount;
use Illuminate\Foundation\Http\FormRequest;

class BedSpace extends FormRequest
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
            'room_id' => 'required|integer|exists:rooms,id',
            'bed_number' => 'required|integer|unique:bed_spaces,bed_number',
            'is_available' => 'required|string',
        ];
    }
}
