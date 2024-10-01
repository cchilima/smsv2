<?php

namespace App\Repositories\Academics;

use App\Models\Academics\{
    AcademicPeriod,
    AcademicPeriodClass,
    AcademicPeriodFee,
    AcademicPeriodInformation,
    ClassAssessment,
    PeriodType,
    StudyMode
};
use App\Models\Admissions\{AcademicPeriodIntake};
use App\Models\Accounting\Fee;
use Illuminate\Support\Facades\Auth;

class AcademicPeriodRepository
{
    public function create($data)
    {
        return AcademicPeriod::create($data);
    }

    public function getAll($order = 'ac_start_date')
    {
        return AcademicPeriod::with('period_types')->orderBy($order)->get();
    }
    public function getAllOpenedAc($order = 'created_at')
    {
        return AcademicPeriod::with('period_types')->whereDate('ac_end_date', '>=', now())->orderByDesc($order)->get();
    }
    public function getAllOpenedQuery($order = 'created_at')
    {
        return AcademicPeriod::with('period_types')->whereDate('ac_end_date', '>=', now())->orderByDesc($order);
    }
    public function getAllClosed($order = 'created_at')
    {
        return AcademicPeriod::with('period_types')->whereDate('ac_end_date', '<', now())->orderByDesc($order)->get();
    }

    public function getAllClosedQuery($order = 'created_at')
    {
        return AcademicPeriod::with('period_types')->whereDate('ac_end_date', '<', now())->orderByDesc($order);
    }

    public function update($id, $data)
    {
        return AcademicPeriod::find($id)->update($data);
    }
    public function getAllopen($order = 'created_at', $executeQuery = true)
    {
        $query = AcademicPeriod::with('period_types', 'study_mode')
            ->whereDate('ac_end_date', '>=', now())
            ->orderByDesc($order);

        return $executeQuery ? $query->get() : $query;
    }
    public function getAcadeperiodClasses($id)
    {
        return AcademicPeriodClass::with('course', 'instructor')->where('academic_period_id', $id)->get();
    }


    public function find($id)
    {
        return AcademicPeriod::with('period_types')->find($id);
    }
    public function findOne($id)
    {
        return AcademicPeriod::find($id);
    }
    public function getPeriodTypes()
    {
        return PeriodType::all(['id', 'name']);
    }

    public function getStudyModes()
    {
        return StudyMode::all(['id', 'name']);
    }

    public function getIntakes()
    {
        return AcademicPeriodIntake::all(['id', 'name']);
    }
    public function getFees()
    {
        return Fee::all(['id', 'name']);
    }
    //methods for academic period information
    public function getAPInformation($id)
    {
        return AcademicPeriodInformation::with('academic_period', 'study_mode', 'intake')->where('academic_period_id', $id)->get()->first();
    }
    public function APcreate($data)
    {
        return AcademicPeriodInformation::create($data);
    }
    public function APUpdate($id, $data)
    {
        return AcademicPeriodInformation::find($id)->update($data);;
    }
    public function APFind($data)
    {
        return AcademicPeriodInformation::with('academic_period', 'study_mode', 'intake')->find($data);
    }

    //fee management

    public function APFeeCreate($data)
    {
        return AcademicPeriodFee::create($data);
    }

    public function getAPFeeInformation($id, bool $executeQuery = true)
    {
        $query = AcademicPeriodFee::with('academic_period', 'fee')
            ->where('academic_period_id', $id);

        return $executeQuery ? $query->get() : $query;
    }

    public function getOneAPFeeInformation($id)
    {
        return AcademicPeriodFee::with('academic_period', 'fee', 'programs')->find($id);
    }

    public function APFeeUpdate($id, $data)
    {
        return AcademicPeriodFee::find($id)->update($data);;
    }
    //academic period assessment types
    public function getAcadeperiodClassAssessments()
    {
        return AcademicPeriod::whereDate('ac_end_date', '>=', now())->with('classes.class_assessments.assessment_type', 'classes.instructor', 'classes.course')->get();
    }
    public function getClassAssessmentsQuery()
    {
        return AcademicPeriod::whereDate('ac_end_date', '>=', now())->with('classes.class_assessments.assessment_type', 'classes.instructor', 'classes.course');
    }
    public static function getAllOpened($order = 'created_at')
    {
        $user = Auth::user();
        if ($user->userType->title == 'instructor') {
            return AcademicPeriod::whereDate('ac_end_date', '>=', now())->has('classes.instructor')->get();
        } else {
            return  AcademicPeriod::whereDate('ac_end_date', '>=', now())
                ->orderByDesc($order)
                ->distinct('id')
                ->get();
        }
    }
    public function showClasses($id)
    {
        $user = Auth::user();

        if ($user->userType->title == 'instructor') {
            // If the authenticated user is an instructor, only get AcademicPeriods with related classes where the user is the instructor
            return AcademicPeriod::whereHas('classes', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            })
                ->with('classes.class_assessments.assessment_type', 'classes.instructor', 'classes.course')
                ->find($id);
        } else {
            // If the authenticated user is not an instructor, get all AcademicPeriods with related classes
            // dd(AcademicPeriod::with('classes.class_assessments.assessment_type', 'classes.instructor', 'classes.course')->find($id));
            return AcademicPeriod::with('classes.class_assessments.assessment_type', 'classes.instructor', 'classes.course')->find($id);
        }
    }

    /**
     * Validates if an academic period overlaps with any existing academic periods. Used in Academic Period Information creation requests.
     *
     * @param int $academicPeriodId The ID of the academic period being validated
     * @param Closure $fail The validation failure callback. Used to return a validation error message.
     * @param int $studyModeId The study mode ID of the academic period being validated
     * @param int $academicPeriodIntakeId The academic period intake ID of the academic period being validated
     * @return void
     */
    public function validateOverlappingAcademicPeriod($academicPeriodId, $fail, $studyModeId, $academicPeriodIntakeId)
    {
        // Academic period being validated
        $academicPeriod = AcademicPeriod::find($academicPeriodId);

        // Check if any existing academic periods with dates that overlap academic period being validated
        $existingOverlappingAcademicPeriod = AcademicPeriod::where('id', '!=', $academicPeriodId)
            // Start date is on or before the start date of the academic period being validated
            ->whereDate('ac_start_date', '<=', $academicPeriod->ac_start_date)
            // End date is on or after the end date of the academic period being validated
            ->whereDate('ac_end_date', '>=', $academicPeriod->ac_start_date)
            ->where('period_type_id', $academicPeriod->period_type_id)
            ->whereHas('academic_period_information', function ($query) use ($studyModeId, $academicPeriodIntakeId) {
                $query->where('academic_period_information.study_mode_id', $studyModeId)
                    ->where('academic_period_information.academic_period_intake_id', $academicPeriodIntakeId);
            })->first();

        // If overlapping period exists, fail validation
        if ($existingOverlappingAcademicPeriod) {
            return $fail('An academic period with overlapping start and end date (' . $existingOverlappingAcademicPeriod->code . ') already exists for the selected study mode and intake.');
        }
    }
}
