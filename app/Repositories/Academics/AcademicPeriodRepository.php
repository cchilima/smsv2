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

    //ac_start_date
    //ac_end_date

    public function update($id, $data)
    {
        return AcademicPeriod::find($id)->update($data);
    }
    public function getAllopen($order = 'created_at')
    {
        return AcademicPeriod::with('period_types', 'study_mode')
            ->whereDate('ac_end_date', '>=', now())
            ->orderByDesc($order)
            ->get();
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

    public function getAPFeeInformation($id)
    {
        return AcademicPeriodFee::with('academic_period', 'fee')->where('academic_period_id', $id)->get();
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

    // Custom unique validation rule for academic period information requests
    public function validateAcademicPeriod($academicPeriodId, $fail, $studyModeId, $academicPeriodIntakeId)
    {
        // Check if academic period information exists for academic period       
        $existingInfo = AcademicPeriodInformation::where('academic_period_id', $academicPeriodId)
            ->first();

        if ($existingInfo) {
            return $fail('Academic period information already exists for this academic period. Kindly update it to make changes.');
        }

        // Check if academic period information exists with the same study_mode_id and academic_period_intake_id
        $existingInfoFromAnotherPeriod = AcademicPeriodInformation::where('study_mode_id', $studyModeId)
            ->where('academic_period_intake_id', $academicPeriodIntakeId)
            ->where('academic_period_id', '!=', $academicPeriodId)
            ->first();

        if ($existingInfoFromAnotherPeriod) {
            // Check if another academic period exists with the same start and end date and period type
            $existingPeriod = AcademicPeriod::where('ac_start_date', $existingInfoFromAnotherPeriod->academic_period->ac_start_date)
                ->where('ac_end_date', $existingInfoFromAnotherPeriod->academic_period->ac_end_date)
                ->where('period_type_id', $existingInfoFromAnotherPeriod->academic_period->period_type_id)
                ->where('id', '!=', $academicPeriodId)
                ->exists();

            if ($existingPeriod) {
                return $fail('An academic period with the same start date, end date and period type already exists for the selected study mode and intake.');
            }
        }
    }
}
