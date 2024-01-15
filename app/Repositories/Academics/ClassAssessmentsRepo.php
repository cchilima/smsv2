<?php

namespace App\Repositories\Academics;

use App\Models\Academics\AcademicPeriod;
use App\Models\Academics\AcademicPeriodClass;
use App\Models\Academics\ClassAssessment;
use App\Models\Academics\Grade;
use App\Models\Admissions\Student;
use Illuminate\Support\Facades\Auth;

class ClassAssessmentsRepo
{
    public function create($data)
    {
        return ClassAssessment::create($data);
    }

    public function getAll()
    {
        return ClassAssessment::with('classes','assessments')->get();
    }

    public function getPeriodType($data)
    {
        return ClassAssessment::where($data)->get();
    }

    public function update($id, $data)
    {
        return ClassAssessment::find($id)->update($data);
    }

    public function find($id)
    {
        return ClassAssessment::with('classes','assessments')->find($id);
    }
    public function getClassAssessments($class_id,$assess_id){
        $user = Auth::user();

        if ($user->userType->title == 'instructor') {
            // If the authenticated user is an instructor, only get AcademicPeriods with related classes where the user is the instructor
//            return AcademicPeriodClass::where('instructor_id', $user->id)->with('class_assessments.assessment_type', 'instructor', 'course')
//                ->find($class_id);
            return AcademicPeriodClass::where('id', $class_id)
                ->whereHas('class_assessments', function ($query) use ($assess_id) {
                    $query->where('assessment_type_id', $assess_id);
                })
                ->with('class_assessments.assessment_type', 'enrollments.user.student', 'academicPeriod', 'instructor', 'course')
                ->first();
        } else {
            return AcademicPeriodClass::with([
                'class_assessments' => function ($query) use ($assess_id) {
                    $query->where('assessment_type_id', $assess_id);
                },
                'class_assessments.assessment_type',
                'enrollments.user.student.grades' => function ($query) use ($assess_id) {
                    $query->where('assessment_type_id', $assess_id);
                },
                'academicPeriod',
                'instructor',
                'course',
            ])
                ->where('id', $class_id)
                ->first();

            //return AcademicPeriodClass::with('class_assessments.assessment_type','enrollments.user.student', 'academicPeriod', 'instructor', 'course')->find($id);
        }
    }
    public static function getAllReadyPublish($order = 'created_at')
    {
        $user = Auth::user();
        if ($user->userType->title == 'instructor'){
            return AcademicPeriod::whereHas('classes', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            })->whereHas('grades')->get();

        }else {
            //$data = now();
            return AcademicPeriod::whereHas('grades')
                ->where('ac_end_date', '>=', now())
                ->get();

        }
    }
    public function publishAvailablePrograms(){
        return Student::has('grades')->with('programCourses')->get();
    }

}
