<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Repositories\Academics\StudentRegistrationRepository;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentRegistrationController extends Controller
{

    protected $registrationRepo;

    /**
     * Display a listing of the resource.
     */

    public function __construct(StudentRegistrationRepository $registrationRepo)
    {
        // $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        // $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->registrationRepo = $registrationRepo;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $isRegistered = $this->registrationRepo->getRegistrationStatus();
        $courses = $this->registrationRepo->getAll();
        $academicInfo = $this->registrationRepo->getAcademicInfo();

        return view('pages.studentRegistration.index', compact('courses', 'academicInfo', 'isRegistered'));
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

    /**
     * Download registration summary.
     */
    public function summary(Request $request)
    {

        // Incase request from management get student number
        $studentNumber = $request->input('student_number');
        $academicPeriodId = $request->input('academic_period_id');

        $courses = $this->registrationRepo->getSummaryCourses($studentNumber, $academicPeriodId);
        $student = $this->registrationRepo->getStudent($studentNumber);
        $studentUser = $student->user;
        $studentUserPersonalInfo = $student->user->userPersonalInfo;
        $nextOfKin = $student->user->userNextOfKin;
        $academicInfo = $this->registrationRepo->getSummaryAcademicInfo($academicPeriodId);
        $latestEnrollment = $student->enrollments->sortBy('created_at')->last();

        $fileName = $student->id . '-registration-summary-' . now()->format('d-m-Y-His') . '.pdf';

        $studentInfo = [
            'Student Name' => $studentUser->first_name . ' ' . $studentUser->last_name,
            'Student ID' => $student->id,
            'Gender' => $studentUser->gender,
            'NRC Number' => $studentUserPersonalInfo->nrc,
            'Next of Kin' => $nextOfKin->full_name,
            'Next of Kin Relationship' => $nextOfKin->relationship->relationship,
            'Next of Kin Contact' => $nextOfKin->mobile,
        ];

        $admissionInfo = [
            'Program of Study' => $student->program->name . ' (' . $student->program->code . ')',
            'Mode of Study' => $student->study_mode->name,
            'Registration Date' => $latestEnrollment->created_at->format('d F Y H:i'),
            'Academic Period' => $academicInfo->academic_period->name,
            'Year of Study' => $student->level->name
        ];

        $pdf = Pdf::loadView('templates.pdf.registration-summary', compact('studentInfo', 'admissionInfo', 'courses'));

        return $pdf->download($fileName);
    }
}
