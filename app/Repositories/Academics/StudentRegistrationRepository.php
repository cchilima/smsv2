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

        $prerequistes = Prerequisite::all();

        // Get the current date in 'YYYY-MM-DD' format
        $currentDate = date('Y-m-d');

        // Get the next available academic period by joining with the academic_periods table
        $nextAcademicPeriod = DB::table('academic_period_information')
            ->join('academic_periods', 'academic_period_information.academic_period_id', '=', 'academic_periods.id')
            ->where('academic_period_information.study_mode_id', $student->study_mode_id)
            ->where('academic_periods.ac_start_date', '<=', $currentDate)
            ->where('academic_periods.ac_end_date', '>=', $currentDate)
            ->orderBy('academic_periods.created_at', 'asc')
            ->select('academic_period_information.*', 'academic_periods.ac_start_date', 'academic_periods.ac_end_date')
            ->first();

        // Get the all closed available
        $closedAcademicPeriods = DB::table('academic_period_information')
            ->join('academic_periods', 'academic_period_information.academic_period_id', '=', 'academic_periods.id')
            ->where('academic_period_information.study_mode_id', $student->study_mode_id)
            ->where(function ($query) use ($currentDate) {
                $query->where('academic_periods.ac_end_date', '<', $currentDate)->orWhere('academic_periods.ac_start_date', '>', $currentDate);
            })
            ->orderBy('academic_periods.created_at', 'asc')
            ->select('academic_period_information.*', 'academic_periods.ac_start_date', 'academic_periods.ac_end_date')
            ->get();

        if ($nextAcademicPeriod) {
            // previous results
            $history = [];

            foreach ($closedAcademicPeriods as $period) {
                $history[] = $this->results($student->id, $period->academic_period_id);
            }

            // check for failed courses and check if the are prerequisites
            $failedCoursesPrerequisites = [];

            $allFailedCourseIds = [];
            $allPassedCourseIds = [];

            foreach ($history as $historyEntry) {
                foreach ($historyEntry['coursesPassed'] as $passedCourse) {
                    $allPassedCourseIds[] = $passedCourse['course_id'];
                }

                foreach ($historyEntry['coursesFailed'] as $failedCourse) {
                    $courseId = $failedCourse['course_id'];
                    $allFailedCourseIds[] = $failedCourse['course_id'];
                    $allPassedCourseIds[] = $passedCourse['course_id'];

                    $prerequisites = DB::table('prerequisites')->where('course_id', $courseId)->pluck('prerequisite_course_id');

                    $failedCoursesPrerequisites[] = [
                        'course_id' => $courseId,
                        'prerequisites' => $prerequisites,
                    ];
                }
            }

            // Set the academic period id
            $currentAcademicPeriodId = $nextAcademicPeriod->academic_period_id;

            if ($currentAcademicPeriodId) {
                // Match courses for a specific academic period
                $currentCourses = $courses->filter(function ($course) use ($currentAcademicPeriodId) {
                    return AcademicPeriodClass::where('course_id', $course->id)
                        ->where('academic_period_id', $currentAcademicPeriodId)
                        ->exists();
                });

                // Step 7 - Filter and get academic class info
                $filteredCourses = [];
                $filteredOutCourses = [];  // Track courses that were filtered out

                // Filter out courses based on unmet prerequisites
                $filteredCourses = $currentCourses->filter(function ($course) use ($failedCoursesPrerequisites, &$filteredOutCourses) {
                    foreach ($failedCoursesPrerequisites as $failedCoursePrerequisite) {
                        if (in_array($course->id, $failedCoursePrerequisite['prerequisites']->toArray())) {
                            $filteredOutCourses[] = $failedCoursePrerequisite['course_id'];  // $course;  // Track the filtered out course
                            return false;
                        }
                    }
                    return true;
                });

                // Pluck course IDs from filteredCourses and filteredOutCourses
                $filteredCourseIds = $filteredCourses->pluck('course_id')->toArray();
                $filteredOutCourseIds = $filteredOutCourses;  // collect($filteredOutCourses)->pluck('course_id')->toArray();

                // Combine the course IDs
                $courseIds = array_merge($filteredCourseIds, $filteredOutCourseIds, $allFailedCourseIds);

                // Get only unique id
                $courseIds = array_unique($courseIds);

                // check if student should go part time
                if (count($allFailedCourseIds) >= 3) {
                    // Get
                    $currentCourses = AcademicPeriodClass::join('courses', 'courses.id', 'academic_period_classes.course_id')
                        ->whereIn('course_id', $allFailedCourseIds)
                        ->where('academic_period_id', $currentAcademicPeriodId)
                        ->get(['code', 'name', 'course_id', 'academic_period_classes.id']);

                    // Check if student has invoice for that academic period
                    $invoice = $this->getInvoice($student->id, $currentAcademicPeriodId);

                    // If student has invoice present them the courses
                    if ($invoice) {
                        return $currentCourses;
                    }
                } elseif (count($allFailedCourseIds) == 0) {
                    $filteredWithoutPassed = array_diff($filteredCourseIds, $allPassedCourseIds);

                    $currentCourses = AcademicPeriodClass::join('courses', 'courses.id', 'academic_period_classes.course_id')
                        ->whereIn('course_id', $filteredWithoutPassed)
                        ->where('academic_period_id', $currentAcademicPeriodId)
                        ->get(['code', 'name', 'course_id', 'academic_period_classes.id']);

                    // Check if student has invoice for that academic period
                    $invoice = $this->getInvoice($student->id, $currentAcademicPeriodId);

                    // If student has invoice present them the courses
                    if ($invoice) {
                        return $currentCourses;
                    }
                } else {

                    $filteredWithoutPassed = array_diff($filteredCourseIds, $allPassedCourseIds);


                    $currentCourses = AcademicPeriodClass::join('courses', 'courses.id', 'academic_period_classes.course_id')
                        ->whereIn('course_id', $filteredWithoutPassed)
                        ->where('academic_period_id', $currentAcademicPeriodId)
                        ->get(['code', 'name', 'course_id', 'academic_period_classes.id']);

                    // Check if student has invoice for that academic period
                    $invoice = $this->getInvoice($student->id, $currentAcademicPeriodId);

                    // If student has invoice present them the courses
                    if ($invoice) {
                        return $currentCourses;
                    }
                }
            }
        }
    }

    public function getAllReturning($student_id = null)
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
        $academicInfo = AcademicPeriodInformation::where('study_mode_id', $student->study_mode_id)
            ->where('academic_period_intake_id', $student->academic_period_intake_id)
            ->first();

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

        $academicInfo = $this->openAcademicPeriod($student);

        /* $academicInfo = $student
             ->academic_info()
             ->with(['academic_period', 'study_mode'])
             ->first(); */

        return $academicInfo;
    }

    public function openAcademicPeriod($student)
    {
        $currentDate = date('Y-m-d');

        // Get the next available academic period by joining with the academic_periods table
        $nextAcademicPeriod = DB::table('academic_period_information')
            ->join('academic_periods', 'academic_period_information.academic_period_id', '=', 'academic_periods.id')
            ->where('academic_period_information.study_mode_id', $student->study_mode_id)
            ->where('academic_periods.ac_start_date', '<=', $currentDate)
            ->where('academic_periods.ac_end_date', '>=', $currentDate)
            ->orderBy('academic_periods.created_at', 'asc')
            ->select('academic_period_information.*', 'academic_periods.ac_start_date', 'academic_periods.ac_end_date')
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
        $academicInfo = $this->getAcademicInfo($student_id);

        if ($academicInfo) {
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
        $academicInfo = AcademicPeriodInformation::where('academic_period_id', $academic_period_id)
            ->with(['academic_period', 'study_mode'])
            ->first();

        return $academicInfo;
    }

    //

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
                    'total_score' => 0,  // Initialize total_score to 0 for courses not found in grades
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
                //   $courses[$grade->course_id]['course_id'] = $grade->course_id;
            }
        }
        // dd($courses);

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
