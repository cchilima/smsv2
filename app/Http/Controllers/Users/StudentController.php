<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Repositories\Academics\ClassAssessmentsRepo;
use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Enrollments\EnrollmentRepository;
use App\Repositories\Users\userNextOfKinRepository;
use App\Repositories\Users\UserPersonalInfoRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected $studentRepo;
    protected $registrationRepo;
    protected $userPersonalInfoRepo;
    protected $userRepo;
    protected $userNextOfKinRepo,$enrollmentRepo,$classaAsessmentRepo;

    public function __construct(
        StudentRepository $studentRepo,
        StudentRegistrationRepository $registrationRepo,
        UserPersonalInfoRepository $userPersonalInfoRepo,
        UserRepository $userRepo,
        userNextOfKinRepository $userNextOfKinRepo,
        EnrollmentRepository $enrollmentRepo,
        ClassAssessmentsRepo $classaAsessmentRepo
    ) {
//        $this->middleware(TeamSA::class, ['except' => ['destroy']]);
//        $this->middleware(SuperAdmin::class, ['only' => ['destroy']]);

        $this->studentRepo = $studentRepo;
        $this->registrationRepo = $registrationRepo;
        $this->userPersonalInfoRepo = $userPersonalInfoRepo;
        $this->userRepo = $userRepo;
        $this->userNextOfKinRepo = $userNextOfKinRepo;
        $this->enrollmentRepo = $enrollmentRepo;
        $this->classaAsessmentRepo = $classaAsessmentRepo;
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
        //
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

    public function Enrollments(){
        $id = Auth::user()->id;
        $student = $this->studentRepo->findUser($id);
        $data['enrollments']  = $this->enrollmentRepo->getEnrollments($student->student->id);

        return view('pages.students.enrollments', $data);
    }
}