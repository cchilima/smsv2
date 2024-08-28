<?php

namespace App\Repositories\Reports\enrollments;

use App\Models\Academics\AcademicPeriod;
use App\Models\Academics\AcademicPeriodClass;
use App\Models\Academics\Program;
use App\Models\Academics\ProgramCourses;
use App\Models\Accounting\Invoice;
use App\Models\Accounting\Receipt;
use App\Models\Admissions\Student;
use App\Models\Enrollments\Enrollment;
use App\Models\Users\User;
use App\Repositories\Academics\AcademicPeriodClassRepository;
use App\Repositories\Academics\AcademicPeriodRepository;
use Illuminate\Support\Carbon;

class EnrollmentRepository
{
    protected $repo;

    public function __construct(AcademicPeriodClassRepository $repo)
    {
        // $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        // $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->repo = $repo;
    }

    public function getStudentsWithProgramAndAcademicPeriod($academic_period_id)
    {
        // Retrieve academic period details
        $academicPeriod = AcademicPeriod::findOrFail($academic_period_id);

        // Retrieve program details associated with the academic period
        $programs = $this->repo->academicProgramStudents($academic_period_id);

        // Extract program IDs
        $programIds = $programs->pluck('id')->toArray();

        // Retrieve students enrolled in the specified academic period and programs
        $students = Student::with(['user'])
            ->whereHas('enrollments.class', function ($query) use ($academic_period_id) {
                $query->where('academic_period_id', $academic_period_id);
            })
            ->whereIn('program_id', $programIds)
            ->get();

        $results = [];

        foreach ($students as $student) {
            // Add student details to results array
            $results[] = [
                'student_id' => $student->id,
                'name' => $student->user->first_name . ' ' . $student->user->last_name,
                'gender' => $student->user->gender,
                'program' => $student->program->name,
                'academic_period' => $academicPeriod->name
            ];
        }

        return $results;
    }

    public function getStudentsWithProgramAndAcademicPeriods($academic_period_id)
    {
        // Retrieve academic period details
        $academicPeriod = AcademicPeriod::findOrFail($academic_period_id);

        // Retrieve program details associated with the academic period
        $programs = $this->repo->academicProgramStudents($academic_period_id);

        // Initialize result array
        $result = [];

        // Loop through each program
        foreach ($programs as $program) {
            // Retrieve students enrolled in the specified academic period and program
            $programArray = $this->getArr($academic_period_id, $program);

            // Add program array to result
            $result[] = $programArray;
        }

        // Create final result array with academic period and programs
        $finalResult = [
            'academic_period_id' => $academicPeriod->id,
            'academic_period_name' => $academicPeriod->name,
            'programs' => $result
        ];

        return $finalResult;
    }

    public function getStudentsForProgramAndAcademicPeriod($program_id, $academic_period_id)
    {
        // Retrieve academic period details
        $academicPeriod = AcademicPeriod::findOrFail($academic_period_id);

        // Retrieve program details
        $program = Program::findOrFail($program_id);

        // Retrieve students enrolled in the specified academic period and program
        $students = Student::with(['user', 'invoices.details', 'receipts'])
            ->whereHas('enrollments.class', function ($query) use ($academic_period_id) {
                $query->where('academic_period_id', $academic_period_id);
            })
            ->where('program_id', $program_id)
            ->get();

        // Initialize result array
        $result = [
            'academic_period_id' => $academicPeriod->id,
            'academic_period_name' => $academicPeriod->name,
            'program_id' => $program->id,
            'program_name' => $program->name,
            'students' => []
        ];

        // Loop through each student
        foreach ($students as $student) {
            // Calculate total invoice amount
            $totalInvoiceAmount = 0;
            foreach ($student->invoices as $invoice) {
                $totalInvoiceAmount += $invoice->details->sum('amount');
            }

            // Calculate total receipt amount
            $totalReceiptAmount = $student->receipts->sum('amount');

            // Calculate balance
            $balance = $totalInvoiceAmount - $totalReceiptAmount;

            // Calculate payment percentage
            $paymentPercentage = $totalInvoiceAmount > 0 ? ($totalReceiptAmount / $totalInvoiceAmount) * 100 : 0;

            // Get data of the student
            $gender = $student->user->gender ?? '';
            $email = $student->user->email ?? '';
            $level = $student->user->level ?? '';

            // Add student information to result array
            $result['students'][] = [
                'student_id' => $student->id,
                'name' => $student->user->first_name . ' ' . $student->user->last_name,
                'gender' => $gender,
                'email' => $email,
                'level' => $level,
                'payment_percentage' => $paymentPercentage,
                'balance' => $balance
            ];
        }

        return $result;
    }

    public function getStudentsForProgramsAndAcademicPeriod($program_ids, $academic_period_id)
    {
        // Retrieve academic period details
        $academicPeriod = AcademicPeriod::findOrFail($academic_period_id);

        // Retrieve program details
        $programs = Program::whereIn('id', $program_ids)->get();

        // Initialize result array
        $result = [
            'academic_period_id' => $academicPeriod->id,
            'academic_period_name' => $academicPeriod->name,
            'programs' => []
        ];

        // Loop through each program
        foreach ($programs as $program) {
            // Retrieve students enrolled in the specified academic period and program
            $programArray = $this->getArr($academic_period_id, $program);

            // Add program array to result
            $result['programs'][] = $programArray;
        }

        return $result;
    }

    public function getStudentsForPeriodsAndPrograms($academic_period_ids, $program_ids)
    {
        // Retrieve academic periods
        $academicPeriods = AcademicPeriod::whereIn('id', $academic_period_ids)->get();

        // Retrieve programs
        $programs = Program::whereIn('id', $program_ids)->get();

        // Initialize result array
        $results = [];

        // Loop through each academic period
        foreach ($academicPeriods as $academicPeriod) {
            // Initialize academic period array
            $academicPeriodArray = [
                'academic_period_id' => $academicPeriod->id,
                'academic_period_name' => $academicPeriod->name,
                'programs' => []
            ];

            // Loop through each program
            foreach ($programs as $program) {
                // Retrieve students enrolled in the specified academic period and program
                $students = Student::with(['user', 'invoices.details', 'receipts'])
                    ->whereHas('enrollments.class', function ($query) use ($academicPeriod, $program) {
                        $query->where('academic_period_id', $academicPeriod->id);
                    })->where('program_id', $program->id)
                    ->get();

                // Initialize program array
                $programArray = [
                    'program_id' => $program->id,
                    'program_name' => $program->name,
                    'program_code' => $program->code,
                    'students' => []
                ];

                // Loop through each student
                foreach ($students as $student) {
                    // Calculate total invoice amount
                    $totalInvoiceAmount = 0;
                    foreach ($student->invoices as $invoice) {
                        $totalInvoiceAmount += $invoice->details->sum('amount');
                    }

                    // Calculate total receipt amount
                    $totalReceiptAmount = $student->receipts->sum('amount');

                    // Calculate balance
                    $balance = $totalInvoiceAmount - $totalReceiptAmount;

                    // Calculate payment percentage
                    $paymentPercentage = $totalInvoiceAmount > 0 ? ($totalReceiptAmount / $totalInvoiceAmount) * 100 : 0;

                    // Get gender of the student
                    $gender = $student->user->gender ?? '';

                    // Add student information to program array
                    $programArray['students'][] = [
                        'student_id' => $student->id,
                        'name' => $student->user->first_name . ' ' . $student->user->last_name,
                        'student_number' => $student->user->student_number,
                        'gender' => $gender,
                        'payment_percentage' => $paymentPercentage,
                        'balance' => $balance
                    ];
                }

                // Add program array to academic period array
                $academicPeriodArray['programs'][] = $programArray;
            }

            // Add academic period array to results
            $results[] = $academicPeriodArray;
        }

        return $results;
    }

    public function getStudentsWithPrograms($academic_period_id, $student_ids)
    {
        // Retrieve program IDs associated with the courses offered in the academic period
        $courseIds = AcademicPeriodClass::where('academic_period_id', $academic_period_id)
            ->with('course')
            ->distinct('course_id')
            ->pluck('course_id');

        $programIds = ProgramCourses::whereIn('course_id', $courseIds)
            ->distinct('program_id')
            ->pluck('program_id');

        // Get programs associated with the retrieved program IDs
        $programs = Program::whereIn('id', $programIds)->get();

        // Retrieve academic period details
        $academicPeriod = AcademicPeriod::findOrFail($academic_period_id);

        // Initialize result array
        $result = [
            'academic_period_id' => $academicPeriod->id,
            'academic_period_name' => $academicPeriod->name,
            'students' => []
        ];

        // Loop through each student
        foreach ($student_ids as $student_id) {
            // Retrieve student details
            $student = Student::with(['user', 'program'])
                ->findOrFail($student_id);

            // Add program information to student
            $studentProgram = $programs->where('id', $student->program->id)->first();

            // Calculate total invoice amount
            $totalInvoiceAmount = 0;
            
            foreach ($student->invoices as $invoice) {
                $totalInvoiceAmount += $invoice->details->sum('amount');
            }

            // Calculate total receipt amount
            $totalReceiptAmount = $student->receipts->sum('amount');

            // Calculate balance
            $balance = $totalInvoiceAmount - $totalReceiptAmount;

            // Calculate payment percentage
            $paymentPercentage = $totalInvoiceAmount > 0 ? ($totalReceiptAmount / $totalInvoiceAmount) * 100 : 0;

            // Get gender of the student
            $gender = $student->user->gender ?? '';

            // Add student information to result array
            $result['students'][] = [
                'student_id' => $student->id,
                'name' => $student->user->first_name . ' ' . $student->user->last_name,
                'student_number' => $student->user->student_number,
                'gender' => $gender,
                'program' => $studentProgram ? $studentProgram->name : '',
                'payment_percentage' => $paymentPercentage,
                'balance' => $balance
            ];
        }

        return $result;
    }

    public function getStudentsWithProgramsForAcademicPeriod($academic_period_id)
    {
        // Retrieve all classes within the specified academic period
        $classes = AcademicPeriodClass::where('academic_period_id', $academic_period_id)->get();
        $ac = AcademicPeriod::find($academic_period_id);

        // Initialize result array
        $result = [
            'academic_period_id' => $academic_period_id,
            'name' => $ac->name,
            'code' => $ac->code,
            'classes' => []
        ];

        // Loop through each class
        foreach ($classes as $class) {
            // Retrieve students enrolled in the class
            $students = $class->enrollments()->with('student')->get();

            // Initialize class array
            $classArray = [
                'class_id' => $class->id,
                'class_name' => $class->course->name,
                'class_code' => $class->course->code,
                'students' => []
            ];

            // Loop through each student
            foreach ($students as $enrollment) {
                $student = $enrollment->student;

                // Retrieve program details for the student
                $program = $student->program()->first();

                // Calculate total invoice amount
                $totalInvoiceAmount = $student->invoices->flatMap(function ($invoice) {
                    return $invoice->details->pluck('amount');
                })->sum();

                // Calculate total receipt amount
                $totalReceiptAmount = $student->receipts->sum('amount');

                // Calculate balance
                $balance = $totalInvoiceAmount - $totalReceiptAmount;

                // Calculate payment percentage
                $paymentPercentage = $totalInvoiceAmount > 0 ? ($totalReceiptAmount / $totalInvoiceAmount) * 100 : 0;

                // Get gender of the student
                $gender = $student->user->gender ?? '';
                $email = $student->user->email ?? '';
                $level = $student->user->level ?? '';

                // Add student information to class array
                $classArray['students'][] = [
                    'student_id' => $student->id,
                    'name' => $student->user->first_name . ' ' . $student->user->last_name,
                    'gender' => $gender,
                    'email' => $email,
                    'level' => $level,
                    'program' => $program ? $program->name : '',
                    'payment_percentage' => $paymentPercentage,
                    'balance' => $balance
                ];
            }

            // Add class array to result
            $result['classes'][] = $classArray;
        }

        return $result;
    }

    public function getStudentsWithProgramsForClassAndAcademicPeriod($academic_period_id, $class_id)
    {
        // Retrieve the class details
        $class = AcademicPeriodClass::with('course')->findOrFail($class_id);

        // Retrieve students enrolled in the specified class and academic period
        $enrollments = Enrollment::where('academic_period_class_id', $class_id)
            ->whereHas('class', function ($query) use ($academic_period_id) {
                $query->where('academic_period_id', $academic_period_id);
            })
            ->with('student.user', 'student.program')
            ->get();

        $ac = AcademicPeriod::find($academic_period_id);

        // Initialize result array
        $result = [
            'academic_period_id' => $academic_period_id,
            'name' => $ac->name,
            'code' => $ac->code,
            'class_id' => $class_id,
            'class_name' => $class->course->name,
            'class_code' => $class->course->code,
            'students' => []
        ];

        // Loop through each enrollment
        $result = $this->getResult($enrollments, $result);

        return $result;
    }

    public function getStudentsWithProgramsForClassesAndAcademicPeriods($academic_period_ids, $class_ids)
    {
        // Initialize result array
        $results = [];

        // Loop through each academic period
        foreach ($academic_period_ids as $academic_period_id) {
            // Retrieve the academic period details
            $academic_period = AcademicPeriod::findOrFail($academic_period_id);

            // Initialize academic period array
            $academic_period_array = [
                'academic_period_id' => $academic_period_id,
                'name' => $academic_period->name,
                'code' => $academic_period->code,
                'total_students' => 0,
                'classes' => []
            ];

            // Loop through each class
            foreach ($class_ids as $class_id) {
                // Retrieve the class details
                $class = AcademicPeriodClass::with('course')->findOrFail($class_id);

                // Retrieve students enrolled in the specified class and academic period
                $enrollments = Enrollment::where('academic_period_class_id', $class_id)
                    ->whereHas('class', function ($query) use ($academic_period_id) {
                        $query->where('academic_period_id', $academic_period_id);
                    })
                    ->with('student.user', 'student.program')
                    ->get();

                // Increment total number of students for the academic period
                $academic_period_array['total_students'] += $enrollments->count();

                // Initialize class array
                $class_array = [
                    'class_id' => $class_id,
                    'class_name' => $class->course->name,
                    'class_code' => $class->course->code,
                    'students' => []
                ];

                // Loop through each enrollment
                foreach ($enrollments as $enrollment) {
                    $student = $enrollment->student;

                    // Calculate total invoice amount
                    $totalInvoiceAmount = $student->invoices->flatMap(function ($invoice) {
                        return $invoice->details->pluck('amount');
                    })->sum();

                    // Calculate total receipt amount
                    $totalReceiptAmount = $student->receipts->sum('amount');

                    // Calculate balance
                    $balance = $totalInvoiceAmount - $totalReceiptAmount;

                    // Calculate payment percentage
                    $paymentPercentage = $totalInvoiceAmount > 0 ? ($totalReceiptAmount / $totalInvoiceAmount) * 100 : 0;

                    // Get data of the student
                    $gender = $student->user->gender ?? '';
                    $email = $student->user->email ?? '';
                    $level = $student->user->level ?? '';

                    // Add student information to class array
                    $class_array['students'][] = [
                        'student_id' => $student->id,
                        'name' => $student->user->first_name . ' ' . $student->user->last_name,
                        'student_number' => $student->user->student_number,
                        'gender' => $gender,
                        'program' => $student->program->name,
                        'email' => $email,
                        'level' => $level,
                        'payment_percentage' => $paymentPercentage,
                        'balance' => $balance
                    ];
                }

                // Add class array to academic period array
                $academic_period_array['classes'][] = $class_array;
            }

            // Add academic period array to results
            $results[] = $academic_period_array;
        }

        return $results;
    }

    public function getStudentsWithProgramsForClassesAndAcademicPeriodsss($academic_period_ids, $class_ids)
    {
        // Initialize result array
        $programs = [];

        // Loop through each academic period
        foreach ($academic_period_ids as $academic_period_id) {
            // Initialize academic period array
            $programs[$academic_period_id] = [
                'total_students' => 0,
                'classes' => []
            ];

            // Loop through each class
            foreach ($class_ids as $class_id) {
                // Retrieve the class details
                $class = AcademicPeriodClass::with('course')->findOrFail($class_id);

                // Retrieve students enrolled in the specified class and academic period
                $students = Student::with(['user', 'invoices.details', 'receipts'])
                    ->whereHas('enrollments.class', function ($query) use ($academic_period_id, $class_id) {
                        $query->where('academic_period_id', $academic_period_id)
                            ->where('class_id', $class_id);
                    })
                    ->get();

                // Increment total number of students for the academic period
                $programs[$academic_period_id]['total_students'] += $students->count();

                // Initialize class array
                $classArray = [
                    'class_id' => $class_id,
                    'class_name' => $class->course->name,
                    'class_code' => $class->course->code,
                    'students' => []
                ];

                // Loop through each student
                foreach ($students as $student) {
                    // Calculate total invoice amount
                    $totalInvoiceAmount = $student->invoices->flatMap(function ($invoice) {
                        return $invoice->details->pluck('amount');
                    })->sum();

                    // Calculate total receipt amount
                    $totalReceiptAmount = $student->receipts->sum('amount');

                    // Calculate balance
                    $balance = $totalInvoiceAmount - $totalReceiptAmount;

                    // Calculate payment percentage
                    $paymentPercentage = $totalInvoiceAmount > 0 ? ($totalReceiptAmount / $totalInvoiceAmount) * 100 : 0;

                    // Get data of the student
                    $gender = $student->user->gender ?? '';
                    $email = $student->user->email ?? '';
                    $level = $student->user->level ?? '';

                    // Add student information to class array
                    $classArray['students'][] = [
                        'student_id' => $student->id,
                        'name' => $student->user->first_name . ' ' . $student->user->last_name,
                        'gender' => $gender,
                        'email' => $email,
                        'level' => $level,
                        'payment_percentage' => $paymentPercentage,
                        'balance' => $balance
                    ];
                }

                // Add class array to academic period array
                $programs[$academic_period_id]['classes'][] = $classArray;
            }
        }

        return $programs;
    }

    /**
     * @param $academic_period_id
     * @param mixed $program
     * @return array
     */
    public function getArr($academic_period_id, mixed $program): array
    {
        $students = Student::with(['user', 'invoices.details', 'receipts'])
            ->whereHas('enrollments.class', function ($query) use ($academic_period_id) {
                $query->where('academic_period_id', $academic_period_id);
            })
            ->where('program_id', $program->id)
            ->get();

        // Initialize program array
        $programArray = [
            'program_id' => $program->id,
            'program_name' => $program->name,
            'students' => []
        ];

        // Loop through each student
        foreach ($students as $student) {
            // Calculate total invoice amount
            $totalInvoiceAmount = 0;
            foreach ($student->invoices as $invoice) {
                $totalInvoiceAmount += $invoice->details->sum('amount');
            }

            // Calculate total receipt amount
            $totalReceiptAmount = $student->receipts->sum('amount');

            // Calculate balance
            $balance = $totalInvoiceAmount - $totalReceiptAmount;

            // Calculate payment percentage
            $paymentPercentage = $totalInvoiceAmount > 0 ? ($totalReceiptAmount / $totalInvoiceAmount) * 100 : 0;

            // Get data of the student
            $gender = $student->user->gender ?? '';
            $email = $student->user->email ?? '';
            $level = $student->user->level ?? '';

            // Add student information to program array
            $programArray['students'][] = [
                'student_id' => $student->id,
                'name' => $student->user->first_name . ' ' . $student->user->last_name,
                'gender' => $gender,
                'email' => $email,
                'level' => $level,
                'payment_percentage' => $paymentPercentage,
                'balance' => $balance
            ];
        }
        return $programArray;
    }

    /**
     * @param $enrollments
     * @param array $result
     * @return array
     */
    public function getResult($enrollments, array $result): array
    {
        foreach ($enrollments as $enrollment) {
            $student = $enrollment->student;
            //dd($student->program()->first());

            // Retrieve program details for the student
            $program = $student->program()->first();

            // Calculate total invoice amount
            $totalInvoiceAmount = $student->invoices->flatMap(function ($invoice) {
                return $invoice->details->pluck('amount');
            })->sum();

            // Calculate total receipt amount
            $totalReceiptAmount = $student->receipts->sum('amount');

            // Calculate balance
            $balance = $totalInvoiceAmount - $totalReceiptAmount;

            // Calculate payment percentage
            $paymentPercentage = $totalInvoiceAmount > 0 ? ($totalReceiptAmount / $totalInvoiceAmount) * 100 : 0;

            // Get data of the student
            $gender = $student->user->gender ?? '';
            $email = $student->user->email ?? '';
            $level = $student->user->level ?? '';

            // Add student information to result array
            $result['class']['students'][] = [
                'student_id' => $student->id,
                'name' => $student->user->first_name . ' ' . $student->user->last_name,
                'gender' => $gender,
                'email' => $email,
                'level' => $level,
                'program' => $program ? $program->name : '',
                'payment_percentage' => $paymentPercentage,
                'balance' => $balance
            ];
        }
        return $result;
    }

    public function getCurrentcourse($student_id)
    {
        // Retrieve the student
        $student = Student::findOrFail($student_id);

        // Retrieve the most recent academic period from the student's enrollments
        $recentAcademicPeriod = Enrollment::where('student_id', $student_id)
            ->with('class.academicPeriod')
            ->latest()
            ->firstOrFail()
            ->class
            ->academicPeriod;

        // Retrieve all enrollments for the student within the classes of the recent academic period
        $enrollments = Enrollment::whereHas('class', function ($query) use ($recentAcademicPeriod) {
            $query->where('academic_period_id', $recentAcademicPeriod->id);
        })
            ->where('student_id', $student_id)
            ->with('class.course')
            ->get();

        // Initialize array to store unique course IDs
        $uniqueCourses = [];

        // Construct result array
        $result = [
            'academic_period_id' => $recentAcademicPeriod->id,
            'academic_period_name' => $recentAcademicPeriod->name,
            'courses' => []
        ];

        // Loop through each enrollment and add associated course to result array
        foreach ($enrollments as $enrollment) {
            $courseId = $enrollment->class->course->id;

            // Check if course ID is already added to uniqueCourses array
            if (!in_array($courseId, $uniqueCourses)) {
                // If not, add course details to result array and mark course ID as seen
                $course = $enrollment->class->course;
                $result['courses'][] = [
                    'course_id' => $course->id,
                    'course_name' => $course->name,
                    'course_code' => $course->code,
                    // Add any other course information you may need
                ];
                $uniqueCourses[] = $courseId;
            }
        }

        return $result;
    }

    public function getStudentsForAllOpenPeriods()
    {
        // Retrieve all open academic periods
        $openAcademicPeriods = AcademicPeriod::whereDate('ac_end_date', '>=', now())->get();

        // Initialize result array
        $results = [];

        // Loop through each open academic period
        foreach ($openAcademicPeriods as $academicPeriod) {
            // Retrieve students enrolled in the academic period
            $studentsCount = Student::whereHas('enrollments.class', function ($query) use ($academicPeriod) {
                $query->where('academic_period_id', $academicPeriod->id);
            })
                ->count();

            // Build academic period information
            $academicPeriodInfo = [
                'academic_period_id' => $academicPeriod->id,
                'academic_period_name' => $academicPeriod->name,
                'students_count' => $studentsCount
            ];

            // Add academic period info to results array
            $results[] = $academicPeriodInfo;
        }

        return $results;
    }
    public function getStudentsSumForAllOpenPeriods()
    {
        // Retrieve all open academic periods
        $openAcademicPeriods = AcademicPeriod::whereDate('ac_end_date', '>=', now())->get();

        // Initialize total students count
        $totalStudentsCount = 0;

        // Loop through each open academic period
        foreach ($openAcademicPeriods as $academicPeriod) {
            // Retrieve students enrolled in the academic period and add to total count
            $studentsCount = Student::whereHas('enrollments.class', function ($query) use ($academicPeriod) {
                $query->where('academic_period_id', $academicPeriod->id);
            })
                ->count();

            // Increment the total students count
            $totalStudentsCount += $studentsCount;
        }

        return $totalStudentsCount;
    }
    public function totalStudents()
    {
        return Student::count();
    }
    public function totalUsers()
    {
        return User::count();
    }
    public function totalStaff()
    {
        return User::where('user_type_id', '!=', 3)->count();
    }
    public function totalAdmin()
    {
        return User::where('user_type_id', '=', 1)->count();
    }
    public static function todaysPayments()
    {
        // figure out what day it is
        $today    = Carbon::today()->toDateString();

        // find todays invoices
        $receipts = Receipt::whereDate('created_at', '>=', now())->get();
        //where('created_at', '>=', $today)

        //foreach invoice find the invoice details and totalout the ammount
        return $receipts->sum('amount');
    }
    public static function todaysInvoices()
    {
        // figure out what day it is
        $today    = Carbon::today()->toDateString();

        // find todays invoices
        $invoices = Invoice::with('details')->where('created_at', '>=', $today)->get();

        $totalAmount = 0;

        // Iterate through each invoice
        foreach ($invoices as $invoice) {
            // Sum the amount in the details of each invoice
            $totalAmount += $invoice->details->sum('amount');
        }

        return $totalAmount;
    }
}
