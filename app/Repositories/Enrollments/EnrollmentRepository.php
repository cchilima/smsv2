<?php

namespace App\Repositories\Enrollments;

use DB;
use Auth;
use App\Models\Enrollments\Enrollment;

class EnrollmentRepository
{
    public function create($courses)
    {
        DB::beginTransaction();

        try {

            foreach ($courses as $course) {
                Enrollment::create(['user_id' => Auth::user()->id, 'academic_period_class_id' => $course->id]);
            }

            DB::commit();

            return "enrolled";

        } catch (\Exception $e) {
            DB::rollback();

            dd($e);
        }

    }
}
