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

    public function getEnrollments($id)
    {
        $organizedResults = [];
        $student = Student::with(['program', 'user', 'level', 'enrollments.class.course','enrollments.class.academicPeriod'])->find($id);

        foreach ($student->enrollments as $enrollment) {

            $academicPeriodId = $enrollment->class->academic_period_id;
            //$academicPeriodId = 'courses';
            $course = [
                'course_code' => $enrollment->class->course->code,
                'course_title' => $enrollment->class->course->name,
            ];

            if (!isset($organizedResults[$academicPeriodId])) {
                $organizedResults[$academicPeriodId] = [
                    'academic_period_name' => $enrollment->class->academicPeriod->name,
                    'academic_period_code' => $enrollment->class->academicPeriod->code,
                    'academic_period_id' => $enrollment->class->academic_period_id,
                    'student_id' => $id
                ];
            }

            $organizedResults[$academicPeriodId]['courses'][] = $course;
        }

        return $organizedResults;

    }
}
