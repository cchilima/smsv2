<?php

namespace App\Repositories\Academics;

use App\Models\Academics\{AcademicPeriodClass, AcademicPeriod, Course, Program, ProgramCourses};
use App\Models\Users\User;

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
        return program::whereIn('id',$ids)->get();
    }
}
