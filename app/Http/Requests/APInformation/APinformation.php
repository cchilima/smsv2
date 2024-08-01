<?php

namespace App\Http\Requests\APInformation;

use App\Models\Academics\AcademicPeriod;
use App\Models\Academics\AcademicPeriodInformation;
use Illuminate\Foundation\Http\FormRequest;

class APinformation extends FormRequest
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
            'academic_period_intake_id' => 'required|integer|exists:academic_period_intakes,id',
            'study_mode_id' => 'required|integer|exists:study_modes,id',
            'view_results_threshold' => 'required|integer|between:1,100',
            'exam_slip_threshold' => 'required|integer|between:1,100',
            'registration_threshold' => 'required|integer|between:1,100',
            'late_registration_end_date' => 'required|date|after:late_registration_date',
            'late_registration_date' => 'required|date|after:registration_date',
            'registration_date' => 'required|date|after_or_equal:today',

            'academic_period_id'  => [
                'required',
                'integer',
                'exists:academic_periods,id',
                function ($attribute, $value, $fail) {
                    $this->validateAcademicPeriod($value, $fail);
                },
            ]
        ];
    }

    private function validateAcademicPeriod($academicPeriodId, $fail)
    {
        $studyModeId = $this->input('study_mode_id');
        $academicPeriodIntakeId = $this->input('academic_period_intake_id');

        // Check if academic period information exists with the same study_mode_id and academic_period_intake_id
        $existingInfo = AcademicPeriodInformation::where('study_mode_id', $studyModeId)
            ->where('academic_period_intake_id', $academicPeriodIntakeId)
            ->first();

        if ($existingInfo) {
            // Check if another academic period exists with the same start and end date and period type
            $existingPeriod = AcademicPeriod::where('ac_start_date', $existingInfo->academic_period->ac_start_date)
                ->where('ac_end_date', $existingInfo->academic_period->ac_end_date)
                ->where('period_type_id', $existingInfo->academic_period->period_type_id)
                ->where('id', '!=', $academicPeriodId)
                ->exists();

            if ($existingPeriod) {
                $fail('An academic period with the same start date, end date and period type already exists. Kindly input a different study mode or intake');
            }
        }
    }
}
