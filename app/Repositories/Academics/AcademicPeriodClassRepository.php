<?php

namespace App\Repositories\Academics;

use App\Models\Academics\{AcademicPeriodClass, AcademicPeriod, AcademicPeriodFee, Course, Program, ProgramCourses};
use App\Models\Admissions\Student;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AcademicPeriodClassRepository
{

    public function create($data)
    {
        return AcademicPeriodClass::create($data);
    }

    public function getAll($order = 'academic_period_id')
    {
        return AcademicPeriodClass::orderBy($order, 'desc')->get();
    }
    public function getAllAcClasses($id, $order = 'academic_period_id', $executeQuery = true)
    {
        $query = AcademicPeriodClass::where('academic_period_id', $id)->with('enrollments')->orderBy($order);

        return $executeQuery ? $query->get() : $query;
    }

    public function update($id, $data)
    {
        return AcademicPeriodClass::find($id)->update($data);
    }

    public function find($id)
    {
        return AcademicPeriodClass::with('instructor', 'course', 'academicPeriod')->find($id);
    }

    public function getCourses()
    {
        return Course::all('id', 'name', 'code');
    }

    public function getAcademicPeriods()
    {
        return AcademicPeriod::all('id', 'name', 'code');
    }

    public function getInstructors()
    {
        return User::join('user_types', 'user_types.id', 'users.user_type_id')
            ->where('user_types.title', 'instructor')->get();
    }
    public function academicPrograms($id)
    {
        //return $courses = AcademicPeriodClass::where('academic_period_id',$id)->with('course')->distinct()->get();
        $courseIds = AcademicPeriodClass::where('academic_period_id', $id)
            ->with('course')
            ->distinct('course_id')
            ->pluck('course_id');
        $ids = ProgramCourses::whereIn('course_id', $courseIds)->distinct('program_id')->pluck('program_id');
        return program::whereIn('id', $ids)->with('qualification', 'department')->get();
    }

    public function academicProgramStudents($academicPeriodId, $executeQuery = true)
    {
        // Retrieve the program IDs associated with courses offered in the academic period
        $courseIds = AcademicPeriodClass::where('academic_period_id', $academicPeriodId)
            ->with('course')
            ->distinct('course_id')
            ->pluck('course_id');

        $programIds = ProgramCourses::whereIn('course_id', $courseIds)
            ->distinct('program_id')
            ->pluck('program_id');

        // Get the count of students enrolled in each program
        $query = Program::whereIn('id', $programIds)
            ->withCount(['students' => function ($query) use ($academicPeriodId) {
                $query->whereHas('enrollments.class', function ($query) use ($academicPeriodId) {
                    $query->where('academic_period_id', $academicPeriodId);
                });
            }])
            ->having('students_count', '>', 0)
            ->with('qualification', 'department');

        return $executeQuery ? $query->get() : $query;
    }

    public function getAssessmentsProgramListsDataTableQuery()
    {
        $academicPeriodIds = AcademicPeriod::whereDate('ac_end_date', '>=', now())->pluck('id');

        // Retrieve the program IDs associated with courses offered in the academic period
        $courseIds = AcademicPeriodClass::whereIn('academic_period_id', $academicPeriodIds)
            ->with('course')
            // ->distinct('course_id')
            ->pluck('course_id');

        $programIds = ProgramCourses::whereIn('course_id', $courseIds)
            // ->distinct('program_id')
            ->pluck('program_id');

        // Get the count of students enrolled in each program
        $query = Program::whereIn('id', $programIds)
            // ->having('students_count', '>', 0)
            ->with('qualification', 'department');

        return $query;
    }

    public function academicPeriodStudents($id)
    {
        return Student::whereHas('enrollments.class', function ($query) use ($id) {
            $query->where('academic_period_id', $id);
        })
            ->withCount('enrollments') // Count the number of enrollments
            ->count();
    }
    public function academicProgramsFees($id)
    {
        $acId = AcademicPeriodFee::find($id);
        $courseIds = AcademicPeriodClass::where('academic_period_id', $acId->academic_period_id)
            ->with('course')
            ->distinct('course_id')
            ->pluck('course_id');
        $ids = ProgramCourses::whereIn('course_id', $courseIds)->distinct('program_id')->pluck('program_id');
        return program::whereIn('id', $ids)->get();
    }

    public function getAcademicPeriodClassDataTableQuery()
    {
        return AcademicPeriodClass::with(['class_assessments.assessment_type', 'instructor', 'course', 'academicPeriod'])->whereHas('academicPeriod', function ($query) {
            $query->whereDate('ac_end_date', '>=', now());
        });
    }

    public function getAssessmentClassListDataTableQuery()
    {
        $user = Auth::user();

        if ($user->userType->title == 'instructor') {
            // If the authenticated user is an instructor, only get academic period classes where the user is the instructor
            return AcademicPeriodClass::with(['class_assessments.assessment_type', 'instructor', 'course', 'academicPeriod', 'enrollments'])->whereHas('instructor', function ($query) use ($user) {
                $query->where('id', $user->id);
            })
                ->whereHas('academicPeriod', function ($query) {
                    $query->whereDate('ac_end_date', '>=', now());
                });
        } else {
            // Else get all academic period classes
            return AcademicPeriodClass::with(['class_assessments.assessment_type', 'instructor', 'course', 'academicPeriod', 'enrollments'])
                ->whereHas('academicPeriod', function ($query) {
                    $query->whereDate('ac_end_date', '>=', now());
                });
        }
    }
}
