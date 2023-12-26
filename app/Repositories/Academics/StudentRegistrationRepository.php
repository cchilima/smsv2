<?php

namespace App\Repositories\Academics;

 use Auth; 
 use App\Models\Admissions\{ Student };
 use App\Models\Academics\{ Course, AcademicPeriodClass, AcademicPeriodInformation, CourseLevel, ProgramCourses };

class StudentRegistrationRepository
{

    public function getStudent()
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        return $student;
    }

    public function getAll()
    {
        $student = $this->getStudent();

        $courses = ProgramCourses::join('courses', 'courses.id', 'program_courses.course_id')->where('program_id', $student->program_id)->where('course_level_id', $student->course_level_id )->get();
        $academicInfo = AcademicPeriodInformation::where('study_mode_id', $student->study_mode_id)->where('academic_period_intake_id', $student->academic_period_intake_id)->first();

        $currentAcademicPeriodId = $academicInfo->academic_period_id;

        $currentCourses = $courses->filter(function ($course) use ($currentAcademicPeriodId) {
            return AcademicPeriodClass::where('course_id', $course->id)
                          ->where('academic_period_id', $currentAcademicPeriodId)
                          ->exists();
        });
    
        return $currentCourses;

    }

    public function getAcademicInfo()
    {
        $student = $this->getStudent();

        $academicInfo = $student->academic_info()
            ->with(['academic_period', 'study_mode'])
            ->first();
    
        return $academicInfo;
    }

}
