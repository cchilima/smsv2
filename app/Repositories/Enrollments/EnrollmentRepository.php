<?php

namespace App\Repositories\Enrollments;

use DB;
use Auth;
use App\Models\Admissions\Student;
use App\Models\Enrollments\Enrollment;
use App\Models\Academics\{AcademicPeriodClass, AcademicPeriod};
use App\Repositories\Academics\{StudentRegistrationRepository};

class EnrollmentRepository
{

    protected $registrationRepo;

    public function __construct(StudentRegistrationRepository $registrationRepo)
    {
        $this->registrationRepo = $registrationRepo;
    }

    public function create($courses, $student_id = null)
    {
        // Check id student_id is true, if it is request from management
        if (!$student_id) {
            $student_id = Auth::user()->student->id;
        }

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


    public function dropCourse($enrollment_id)
    {
        try {
            return Enrollment::find($enrollment_id)->delete();
        } catch (\Throwable $th) {
            return false;
        }
     
    }

    public function addCourse($student, $course_id)
    {

        try {

            $invoice = $this->getLatestInvoice($student);
            $academic_period_class = AcademicPeriodClass::where('academic_period_id', $invoice->academic_period_id)->where('course_id', $course_id)->first();

            return Enrollment::create(['student_id' => $student->id, 'academic_period_class_id' => $academic_period_class->id]);

        } catch (\Throwable $th) {
            dd($th);
            return false;
        }
    }


    public function autoReenrollment($new_study_mode_id, $student)
    {
        try {
            
        DB::beginTransaction();

        // Get the latest invoice for the student
        $invoice = $this->getLatestInvoice($student);

        // Ensure the invoice is found
        if (!$invoice) {
            return false;
            throw new \Exception('No latest invoice found for the student.');
        }

        // Fetch all current enrollments linked to the invoice's academic period
        $enrollments = Enrollment::where('student_id', $student->id)
            ->whereHas('class', function ($query) use ($invoice) {
                $query->where('academic_period_id', $invoice->academic_period_id);
            })
            ->get();

        // Delete current enrollments
        foreach ($enrollments as $enrollment) {
           $enrollment->delete();
        }

        // update student's study mode
        $student->update(['study_mode_id' => $new_study_mode_id]);

        // Fetch the academic period ID for the new study mode
        $academic_period_id = AcademicPeriod::join('academic_period_information', 'academic_periods.id', '=', 'academic_period_information.academic_period_id')
            ->where('academic_period_information.study_mode_id', $new_study_mode_id)
            ->where('academic_period_information.academic_period_intake_id', $student->academic_period_intake_id)
            ->value('academic_periods.id');

        if (!$academic_period_id) {
            throw new \Exception('No matching academic period found for the new study mode.');
        }

        // Get all invoices in the specified academic period
        $invoicesInPeriod = $student->invoices()->where('academic_period_id', $invoice->academic_period_id)->get();

        // Loop through each invoice and update its academic_period_id
        foreach ($invoicesInPeriod as $inv) {
            $inv->update(['academic_period_id' => $academic_period_id]);
        }

        // Commit the transaction before proceeding with new enrollments
        DB::commit();

        // Get new classes that the student can enroll in for the new study mode and academic period
        $newCourses = $this->registrationRepo->getAll($student->id, $study_mode_change = true);

        // Enroll the student in the new classes
        if($this->create($newCourses, $student->id)){
            DB::commit();
            return true;
        } else {
            DB::rollback();
            return false;
        }

        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
            return false;
        }
      
    }



    private function getLatestInvoice($student)
    {
        return $student->invoices()->latest()->first();
    }
}
