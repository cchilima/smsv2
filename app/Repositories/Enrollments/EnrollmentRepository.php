<?php

namespace App\Repositories\Enrollments;

use DB;
use Auth;
use App\Models\Admissions\Student;
use App\Models\Enrollments\Enrollment;

class EnrollmentRepository
{
    public function create($courses, $student_id = null)
    {
        // Check id student_id is true, if it is request from management
        if($student_id){

            $student = Student::find($student_id);
            $user_id = $student->user->id;

        } else {
            $user_id = Auth::user()->id;
        }

        //
        DB::beginTransaction();

        try {

            foreach ($courses as $course) {
                Enrollment::create(['user_id' => $user_id, 'academic_period_class_id' => $course->id]);
            }

            DB::commit();

            return "enrolled";

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }

    }
}
