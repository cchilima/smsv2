<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Middleware\Custom\TeamSAT;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Accounting\InvoiceRepository;
use App\Repositories\Accounting\StudentFinancesRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class StudentRegistrationController extends Controller
{

    protected $registrationRepo;
    protected $invoiceRepo;
    protected $studentFinancesRepo;

    /**
     * Display a listing of the resource.
     */

    public function __construct(
        StudentRegistrationRepository $registrationRepo,
        InvoiceRepository $invoiceRepository,
        StudentFinancesRepository $studentFinancesRepo
    ) {
        // $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        // $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);
        $this->middleware(TeamSAT::class, ['except' => ['destroy',]]);

        $this->registrationRepo = $registrationRepo;
        $this->invoiceRepo = $invoiceRepository;
        $this->studentFinancesRepo = $studentFinancesRepo;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = Auth::user()->student;

        $data['isRegistered'] = $this->registrationRepo->getRegistrationStatus($student->id);
        $data['isWithinRegistrationPeriod'] = $this->registrationRepo->checkIfWithinRegistrationPeriod($student->id);
        $data['courses'] = $this->registrationRepo->getAll();

        // Financial info
        $financialInfo = $this->studentFinancesRepo->getStudentFinancialInfo($student);

        // Academic info
        $data['academicInfo'] = $this->registrationRepo->getAcademicInfo();
        $academicPeriodId = $data['academicInfo']?->academic_period_id;

        $isInvoiced = $this->invoiceRepo->checkStudentAcademicPeriodInvoiceStatus($student, $academicPeriodId);

        if (!$isInvoiced) {
            // Student has not been invoiced for the academic period
            $data['academicInfo'] = [];
        }

        return view('pages.studentRegistration.index', array_merge($data, $financialInfo));
    }

    /**
     * Download registration summary.
     */
    public function summary(Request $request)
    {
        try {
            // Incase request from management get student number
            $studentNumber = $request->input('student_number');
            $academicPeriodId = $request->input('academic_period_id');

            // If request is from student
            if (!$studentNumber) {
                $studentNumber = Auth::user()->student->id;
                $academicPeriodId = Auth::user()->student->academic_info->academic_period->id;
            }

            $courses = $this->registrationRepo->getSummaryCourses($studentNumber, $academicPeriodId);
            $student = $this->registrationRepo->getStudent($studentNumber);
            $studentUser = $student->user;
            $studentUserPersonalInfo = $studentUser->userPersonalInfo;
            $nextOfKin = $studentUser->userNextOfKin;
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
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to download registration summary: ' . $th->getMessage());
        }
    }
}
