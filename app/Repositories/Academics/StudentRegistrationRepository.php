<?php

namespace App\Repositories\Academics;

use App\Models\Academics\{Course, AcademicPeriodClass, AcademicPeriodInformation, CourseLevel, ProgramCourses, Grade, Prerequisite};
use App\Models\Accounting\{Invoice};
use App\Models\Admissions\{Student};
use App\Models\Enrollments\{Enrollment};
use Carbon\Carbon;
use Auth;
use DB;

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
        // Step 1: Get student information
        $student = $this->getStudentById($student_id);

        // Step 2: Get available courses for the student's program and level
        $courses = $this->getProgramCourses($student->program_id, $student->course_level_id);

        // Get the current date
        $currentDate = date('Y-m-d');

        // Step 3: Get the next available academic period
        $nextAcademicPeriod = $this->getNextAcademicPeriod($student->study_mode_id, $currentDate);
        // Step 4: Get closed academic periods
        $closedAcademicPeriods = $this->getClosedAcademicPeriods($student->study_mode_id, $currentDate);

        // Check if there is a next academic period
        if ($nextAcademicPeriod) {
            // Step 5: Get the student's history for closed academic periods
            $history = $this->getStudentHistory($student->id, $closedAcademicPeriods);

            // Step 6: Get prerequisites for failed courses
            $failedCoursesPrerequisites = $this->getFailedCoursesPrerequisites($history);

            // Get all failed course IDs
            $allFailedCourseIds = $this->getAllFailedCourseIds($history);

            // Get all passed course IDs
            $allPassedCourseIds = $this->getAllPassedCourseIds($history);

            // Get the academic period ID
            $currentAcademicPeriodId = $nextAcademicPeriod->academic_period_id;

            if ($currentAcademicPeriodId) {
                // Step 7: Filter courses for the current academic period
                $currentCourses = $this->getCurrentCourses($courses, $currentAcademicPeriodId);

                // Step 8: Filter out courses based on unmet prerequisites
                $filteredCourseIds = $this->filterCourses($currentCourses, $failedCoursesPrerequisites, $allPassedCourseIds);

                // Step 9: Determine course results to return
                return $this->getCourseResults($student->id, $currentAcademicPeriodId, $allFailedCourseIds, $allPassedCourseIds, $filteredCourseIds);
            }
        }
    }

    private function checkIfInvoiceV1Support($student_id)
    {
        $student = $this->getStudent($student_id);
    
        // enrollments is a relationship, use '->latest()->first()' to get the most recent enrollment
        $enrollment = $student->enrollments()->latest()->first();
    
        if (!$enrollment) {
            return false; 
        }
    
        $exists = Invoice::where('student_id', $student_id)
            ->where('academic_period_id', $enrollment->class->academic_period_id)
            ->exists();
    
        return $exists;
    }
    

    private function getStudentById($student_id)
    {
        // Get student by ID, or get the current student if no ID is provided
        if ($student_id) {
            return Student::find($student_id);
        }
        return $this->getStudent();
    }

    private function getProgramCourses($program_id, $course_level_id)
    {
        // Get program courses for the specified program and level
        return ProgramCourses::join('courses', 'courses.id', 'program_courses.course_id')
            ->where('program_id', $program_id)
            ->where('course_level_id', $course_level_id)
            ->get();
    }

    private function getNextAcademicPeriod($study_mode_id, $currentDate)
    {
        // Get the next available academic period for the study mode
        return DB::table('academic_period_information')
            ->join('academic_periods', 'academic_period_information.academic_period_id', '=', 'academic_periods.id')
            ->where('academic_period_information.study_mode_id', $study_mode_id)
            ->where('academic_periods.ac_start_date', '<=', $currentDate)
            ->where('academic_periods.ac_end_date', '>=', $currentDate)
            ->orderBy('academic_periods.created_at', 'asc')
            ->select('academic_period_information.*', 'academic_periods.ac_start_date', 'academic_periods.ac_end_date')
            ->first();
    }

    private function getClosedAcademicPeriods($study_mode_id, $currentDate)
    {
        // Get all closed academic periods for the study mode
        return DB::table('academic_period_information')
            ->join('academic_periods', 'academic_period_information.academic_period_id', '=', 'academic_periods.id')
            ->where('academic_period_information.study_mode_id', $study_mode_id)
            ->where(function ($query) use ($currentDate) {
                $query
                    ->where('academic_periods.ac_end_date', '<', $currentDate)
                    ->orWhere('academic_periods.ac_start_date', '>', $currentDate);
            })
            ->orderBy('academic_periods.created_at', 'asc')
            ->select('academic_period_information.*', 'academic_periods.ac_start_date', 'academic_periods.ac_end_date')
            ->get();
    }

    private function getStudentHistory($student_id, $closedAcademicPeriods)
    {
        // Get the student's history of courses for closed academic periods
        $history = [];
        foreach ($closedAcademicPeriods as $period) {
            $history[] = $this->results($student_id, $period->academic_period_id);
        }
        return $history;
    }

    private function getFailedCoursesPrerequisites($history)
    {
        // Get prerequisites for all failed courses in the student's history
        $failedCoursesPrerequisites = [];
        foreach ($history as $historyEntry) {
            foreach ($historyEntry['coursesFailed'] as $failedCourse) {
                $courseId = $failedCourse['course_id'];
                $prerequisites = DB::table('prerequisites')->where('course_id', $courseId)->pluck('prerequisite_course_id');
                $failedCoursesPrerequisites[] = [
                    'course_id' => $courseId,
                    'prerequisites' => $prerequisites,
                ];
            }
        }
        return $failedCoursesPrerequisites;
    }

    private function getAllFailedCourseIds($history)
    {
        // Get all failed course IDs from the student's history
        $allFailedCourseIds = [];
        foreach ($history as $historyEntry) {
            foreach ($historyEntry['coursesFailed'] as $failedCourse) {
                $allFailedCourseIds[] = $failedCourse['course_id'];
            }
        }
        return $allFailedCourseIds;
    }

    private function getAllPassedCourseIds($history)
    {
        // Get all passed course IDs from the student's history
        $allPassedCourseIds = [];
        foreach ($history as $historyEntry) {
            foreach ($historyEntry['coursesPassed'] as $passedCourse) {
                $allPassedCourseIds[] = $passedCourse['course_id'];
            }
        }
        return $allPassedCourseIds;
    }

    private function getCurrentCourses($courses, $academicPeriodId)
    {
        // Filter courses to match those available in the current academic period
        return $courses->filter(function ($course) use ($academicPeriodId) {
            return AcademicPeriodClass::where('course_id', $course->id)
                ->where('academic_period_id', $academicPeriodId)
                ->exists();
        });
    }

    private function filterCourses($currentCourses, $failedCoursesPrerequisites, $allPassedCourseIds)
    {
        // Filter out courses based on unmet prerequisites
        $filteredCourses = $currentCourses->filter(function ($course) use ($failedCoursesPrerequisites) {
            foreach ($failedCoursesPrerequisites as $failedCoursePrerequisite) {
                if (in_array($course->id, $failedCoursePrerequisite['prerequisites']->toArray())) {
                    return false;
                }
            }
            return true;
        });

        return $filteredCourses->pluck('course_id')->toArray();
    }

    private function getCourseResults($student_id, $academicPeriodId, $allFailedCourseIds, $allPassedCourseIds, $filteredCourseIds)
    {
        // Determine which courses to return based on the student's failed and passed courses
        $filteredWithoutPassed = array_diff($filteredCourseIds, $allPassedCourseIds);

        if (count($allFailedCourseIds) >= 3) {
            // If the student has failed 3 or more courses, get those courses
            $currentCourses = $this->getCoursesByIds($allFailedCourseIds, $academicPeriodId);
        } else {
            // Otherwise, get the filtered courses excluding the passed ones
            $currentCourses = $this->getCoursesByIds($filteredWithoutPassed, $academicPeriodId);
        }

        // Check if the student has an invoice for the current academic period
        $invoice = $this->getInvoice($student_id, $academicPeriodId);

        // Return the courses if there is an invoice
        if ($invoice) {
            return $currentCourses;
        }

        return [];
    }

    private function getCoursesByIds($courseIds, $academicPeriodId)
    {
        // Get courses by their IDs for the specified academic period
        return AcademicPeriodClass::join('courses', 'courses.id', 'academic_period_classes.course_id')
            ->whereIn('course_id', $courseIds)
            ->where('academic_period_id', $academicPeriodId)
            ->get(['code', 'name', 'course_id', 'academic_period_classes.id']);
    }

    public function getAcademicInfo($student_id = null)
    {
        $student_id ? $student = $this->getStudent($student_id) : $student = $this->getStudent();

        $academicInfo = $this->openAcademicPeriod($student);

        return $academicInfo;
    }

    public function openAcademicPeriod($student)
    {
        $currentDate = date('Y-m-d');

        // Get next available academic period
        $nextAcademicPeriod = AcademicPeriodInformation::with('academic_period')
            ->whereHas('academic_period', function ($query) use ($currentDate) {
                $query
                    ->whereDate('ac_start_date', '<=', $currentDate)
                    ->whereDate('ac_end_date', '>=', $currentDate);
            })
            ->where('study_mode_id', $student->study_mode_id)
            ->orderBy('created_at', 'asc')
            ->first();

        return $nextAcademicPeriod;
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

        if ($academicInfo) {
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
        // check if invoiced as per SMS v1 support
        $invoiced = $this->checkIfInvoiceV1Support($student_id);

        if(!$invoiced){

            $academicInfo = $this->getAcademicInfo($student_id);

            if ($academicInfo) {
                
                $invoice = $this->getInvoice($student_id, $academicInfo->academic_period_id);
                if($invoice) { return true; } else { return false; }

            } else { return false; }

        } else { return true; }

    }

    private function paymentStanding($invoice_id)
    {
        $invoice = Invoice::find($invoice_id);

        // Calculate the total receipted amount for the invoice
        $receipted_total_amount = $invoice->receipts->sum('amount');

        // Calculate the total amount of the invoice
        $invoice_total_amount = $invoice->details->sum('amount');

        // Check for both zero cases
        if ($invoice_total_amount == 0) {

            if ($receipted_total_amount == 0) {
                return 0;  // or a special value like -1 to indicate both are zero
            }
            return 0;  // Invoice amount is zero but receipted is not, or both are zero
        }

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
        $academicInfo = AcademicPeriodInformation::where('academic_period_id', $academic_period_id)
            ->with(['academic_period', 'study_mode'])
            ->first();

        return $academicInfo;
    }

    // for exams
    public function results($student_id, $academicPeriodID)
    {
        // Fetching all courses associated with the student's enrollments
        $student = Student::with(['enrollments.class.course'])->find($student_id);
        $courses = [];

        foreach ($student->enrollments as $enrollment) {
            if ($academicPeriodID == $enrollment->class->academic_period_id) {
                $courses[$enrollment->class->course->code] = [
                    'course_code' => $enrollment->class->course->code,
                    'course_title' => $enrollment->class->course->name,
                    'total_score' => 0,
                    'course_id' => $enrollment->class->course->id,
                ];
            }
        }

        // Fetching grades for the specified student and academic period
        $grades = Grade::where('student_id', $student_id)
            ->where('academic_period_id', $academicPeriodID)
            ->with(['academicPeriods', 'student'])
            ->select('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->selectRaw('SUM(total) as total_score')
            ->groupBy('course_id', 'academic_period_id', 'course_code', 'course_title', 'student_id')
            ->orderBy('academic_period_id')
            ->orderBy('course_code')
            ->get();

        foreach ($grades as $grade) {
            if (isset($courses[$grade->course_code])) {
                // Update total_score for courses found in grades
                $courses[$grade->course_code]['total_score'] = $grade->total_score;
                $courses[$grade->course_code]['course_code'] = $grade->course_code;
                $courses[$grade->course_code]['course_title'] = $grade->course_title;
            }
        }

        // Count the courses
        $courseCount = count($courses);
        $passedCourse = 0;
        $failedCourse = 0;
        $coursesPassedArray = [];
        $courseFailedArray = [];

        // Determine pass/fail status and populate passed/failed courses array
        foreach ($courses as $course) {
            if ($course['total_score'] >= 40 || $course['total_score'] == -1) {
                // Adjust the pass threshold as necessary
                $passedCourse++;
                $coursesPassedArray[] = $course;
            } else {
                $failedCourse++;
                $courseFailedArray[] = $course;
            }
        }

        // Prepare and return the data
        return [
            'coursesPassed' => $coursesPassedArray,
            'coursesFailed' => $courseFailedArray,
        ];
    }
}
