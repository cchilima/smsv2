<?php

namespace App\Repositories\Academics;

use App\Models\Academics\{AcademicPeriodClass, AcademicPeriod, AcademicPeriodFee, Course, Program, ProgramCourses};
use App\Models\Admissions\Student;
use App\Models\Users\User;
use Illuminate\Support\Facades\DB;

class AcademicPeriodClassRepository
{
    public function create($data)
    {
        return AcademicPeriodClass::create($data);
    }

    public function getAll($order = 'academic_period_id')
    {
        return AcademicPeriodClass::orderBy($order)->get();
    }
    public function getAllAcClasses($id,$order = 'academic_period_id')
    {
        return AcademicPeriodClass::where('academic_period_id',$id)->with('enrollments')->orderBy($order)->get();
    }


    public function update($id, $data)
    {
        return AcademicPeriodClass::find($id)->update($data);
    }

    public function find($id)
    {
        return AcademicPeriodClass::with('instructor','course','academicPeriod')->find($id);
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
        $ids = ProgramCourses::whereIn('course_id',$courseIds)->distinct('program_id')->pluck('program_id');
        return program::whereIn('id',$ids)->with('qualification','department')->get();
    }
    public function academicProgramStudents($id)
    {
        // Retrieve the program IDs associated with courses offered in the academic period
        $courseIds = AcademicPeriodClass::where('academic_period_id', $id)
            ->with('course')
            ->distinct('course_id')
            ->pluck('course_id');

        $programIds = ProgramCourses::whereIn('course_id', $courseIds)
            ->distinct('program_id')
            ->pluck('program_id');

        // Get the count of students enrolled in each program
        return Program::whereIn('id', $programIds)
            ->withCount(['students' => function ($query) use ($id) {
                $query->whereHas('enrollments.class', function ($query) use ($id) {
                    $query->where('academic_period_id', $id);
                });
            }])
            ->with('qualification', 'department')
            ->get();
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
        $ids = ProgramCourses::whereIn('course_id',$courseIds)->distinct('program_id')->pluck('program_id');
        return program::whereIn('id',$ids)->get();
    }
}
