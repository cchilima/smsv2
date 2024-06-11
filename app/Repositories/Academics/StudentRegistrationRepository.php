<?php

namespace App\Repositories\Academics;

use App\Models\Academics\{Course, AcademicPeriodClass, AcademicPeriodInformation, CourseLevel, ProgramCourses, Grade, Prerequisite};
use App\Models\Accounting\{Invoice};
use App\Models\Admissions\{Student};
use App\Models\Enrollments\{Enrollment};
use Carbon\Carbon;
use Auth;

class StudentRegistrationRepository
{
    public function getStudent($student_id = null)
    {
        if ($student_id) {
            // incase request from management
            $student = Student::find($student_id);
        } else {
            // incase request from student
            $student = Student::where('user_id', Auth::user()->id)->first();
        }

        return $student;
    }

    public function getAll($student_id = null)
    {
        // step 1 - get student
        if ($student_id) {
            // incase request from management
            $student = Student::find($student_id);
            
        } else {
            // incase request from student
            $student = $this->getStudent();
        }

        // step 2 - get available courses for that academic period with running classes

        // get courses with prerequisites
        $courses = ProgramCourses::join('courses', 'courses.id', 'program_courses.course_id')
            ->where('program_id', $student->program_id)
            ->where('course_level_id', $student->course_level_id)
            ->get();

        // get academic information
        $academicInfo = AcademicPeriodInformation::where('study_mode_id', $student->study_mode_id)->where('academic_period_intake_id', $student->academic_period_intake_id)->first();

        // get the academic period id
        $currentAcademicPeriodId = false;

        if ($academicInfo !== null) {
            $currentAcademicPeriodId = $academicInfo->academic_period_id;
        }

        if ($currentAcademicPeriodId) {
            // match courses for a specific academic period
            $currentCourses = $courses->filter(function ($course) use ($currentAcademicPeriodId) {
                return AcademicPeriodClass::where('course_id', $course->id)
                    ->where('academic_period_id', $currentAcademicPeriodId)
                    ->exists();
            });

            // get academic class info
            $courseIds = $currentCourses->pluck('course_id')->toArray();

            $currentCourses = AcademicPeriodClass::join('courses', 'courses.id', 'academic_period_classes.course_id')
                ->whereIn('course_id', $courseIds)
                ->where('academic_period_id', $currentAcademicPeriodId)
                ->get(['code', 'name', 'course_id', 'academic_period_classes.id']);

            // check if student has invoice for that academic period
            $invoice = $this->getInvoice($student_id, $currentAcademicPeriodId);

            // if student has invoice present them the courses
            if ($invoice) {
                return $currentCourses;
            }
        }
    }

    public function getAcademicInfo($student_id = null)
    {
        if ($student_id) {
            // incase request from management
            $student = $this->getStudent($student_id);
        } else {
            // incase request from student
            $student = $this->getStudent();
        }

        $academicInfo = $student
            ->academic_info()
            ->with(['academic_period', 'study_mode'])
            ->first();

        return $academicInfo;
    }

    public function getRegistrationStatus($student_id = null)
    {
        // get courses
        $courses = $this->getAll($student_id);
        $classIds = $courses ? $courses->pluck('id')->toArray() : [];

        // check if student has already been enrolled in courses
        $enrollmentExists = Enrollment::whereIn('academic_period_class_id', $classIds)->where('student_id', $student_id)->exists();

        return $enrollmentExists;
    }

    public function checkIfWithinRegistrationPeriod($student_id = null)
    {
        // Get academic information
        $academicInfo = $this->getAcademicInfo($student_id);

        // dd($academicInfo);

        if( $academicInfo ) {

        // Parse registration dates into Carbon instances
        $registrationDate = Carbon::createFromFormat('Y-m-d', $academicInfo->registration_date);
        $lateRegistrationDate = Carbon::createFromFormat('Y-m-d', $academicInfo->late_registration_date);
        $lateRegistrationEndDate = Carbon::createFromFormat('Y-m-d', $academicInfo->late_registration_end_date);

        // Get current date
        $currentDate = Carbon::now();

        // Get invoice
        $invoice = $this->getInvoice($student_id, $academicInfo->academic_period_id);

        // Get payment standing
        $percentage_paid = $invoice ? $this->paymentStanding($invoice->id) : 0;

        // Check if current date is within registration period
        if ($currentDate->gte($registrationDate) && $currentDate->lte($lateRegistrationEndDate) && $percentage_paid >= $academicInfo->registration_threshold) {
            // Within registration period
            return true;
        } else {
            // Outside registration period
            return false;
            
        }
    }
    }

    public function checkIfInvoiced($student_id)
    {
        $academicInfo = $this->getAcademicInfo($student_id);

        if($academicInfo){

            $invoice = $this->getInvoice($student_id, $academicInfo->academic_period_id);

            if ($invoice) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

    private function paymentStanding($invoice_id)
    {
        $invoice = Invoice::find($invoice_id);

        // Calculate the total receipted amount for the invoice
        $receipted_total_amount = $invoice->receipts->sum('amount');

        // Calculate the total amount of the invoice
        $invoice_total_amount = $invoice->details->sum('amount');

        // Calculate the percentage of payments against the invoice
        $percentage_paid = ($receipted_total_amount / $invoice_total_amount) * 100;

        return $percentage_paid;
    }

    private function getInvoice($student_id, $academic_period)
    {
        return Invoice::where('student_id', $student_id)->where('academic_period_id', $academic_period)->first();
    }

    public function getSummaryCourses($student_id, $academic_period_id)
    {
        $student = Student::with(['program', 'user', 'level', 'enrollments.class.course', 'enrollments.class.academicPeriod'])->find($student_id);
        $courses = [];

        foreach ($student->enrollments as $enrollment) {
            if ($academic_period_id == $enrollment->class->academic_period_id) {
                array_push($courses, $enrollment->class->course);
            }
        }

        return $courses;
    }

    public function getSummaryAcademicInfo($academic_period_id)
    {
        $academicInfo = AcademicPeriodInformation::where('academic_period_id', $academic_period_id)->with(['academic_period', 'study_mode'])->first();

        return $academicInfo;
    }
}
