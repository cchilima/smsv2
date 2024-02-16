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
        if (!$student_id) {
            $student_id = Auth::user()->student->id;
        }

        //
        DB::beginTransaction();

        try {
            foreach ($courses as $course) {
                Enrollment::create(['student_id' => $student_id, 'academic_period_class_id' => $course->id]);
            }

            DB::commit();

            return 'enrolled';
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}
