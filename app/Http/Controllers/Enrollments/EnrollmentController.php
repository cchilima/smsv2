<?php

namespace App\Http\Controllers\Enrollments;


use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\Student;
use App\Http\Requests\Enrollments\Enrollment;
use App\Repositories\Enrollments\EnrollmentRepository;
use App\Repositories\Academics\StudentRegistrationRepository;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    protected $enrollmentRepo;
    protected $studentRepo;

    public function __construct(EnrollmentRepository $enrollmentRepo, StudentRegistrationRepository $studentRepo)
    {
        $this->middleware(Student::class, ['only' => ['destroy',]]);

        $this->enrollmentRepo = $enrollmentRepo;
        $this->studentRepo = $studentRepo;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Incase request from management get student number
            $studentNumber = $request->input('student_number');

            // Get courses student can register for
            $courseToRegister = $this->studentRepo->getAll($studentNumber);

            // Register and enroll student in the above courses.
            $this->enrollmentRepo->create($courseToRegister, $studentNumber);

            // Give student feedback
            return Qs::goBackWithSuccess('Registration successful');
        } catch (\Throwable $th) {
            return Qs::jsonError('Registration failed');
        }
    }
}
