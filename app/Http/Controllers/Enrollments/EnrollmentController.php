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

       $this->middleware(Student::class, ['only' => ['destroy',] ]);

        $this->enrollmentRepo = $enrollmentRepo;
        $this->studentRepo = $studentRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Incase request from management get student number
        $studentNumber = $request->input('student_number');

        // Get courses student can register for
        $courseToRegister = $this->studentRepo->getAll($studentNumber);

        // Register and enrollment student in the above courses.
        $enrolled = $this->enrollmentRepo->create($courseToRegister, $studentNumber);

        // Give student feedback
        if ($enrolled) {
            return redirect()->back()->with('status', 'Enrollment successful');
           // return Qs::jsonStoreOk();
        } else {
            return Qs::json(false,'msg.create_failed');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
