<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\Academics\ClassAssessmentsRepo;
use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Accounting\InvoiceRepository;
use App\Repositories\Accounting\StudentFinancesRepository;
use App\Repositories\Reports\enrollments\EnrollmentRepository;
use App\Repositories\Announcements\AnnouncementRepository;
use App\Repositories\Applications\ApplicantRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $enrollmentRepository;
    protected $announcementRepo;
    protected $applicantRepo;
    protected $invoiceRepo;
    protected $studentRegistrationRepo;
    protected $classAssessmentsRepo;
    protected $studentFinancesRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     *
     */
    public function __construct(
        EnrollmentRepository $enrollmentRepository,
        AnnouncementRepository $announcementRepo,
        ApplicantRepository $applicantRepo,
        InvoiceRepository $invoiceRepo,
        StudentRegistrationRepository $studentRegistrationRepo,
        ClassAssessmentsRepo $classAssessmentsRepo,
        StudentFinancesRepository $studentFinancesRepo
    ) {
        $this->middleware('auth');
        $this->enrollmentRepository = $enrollmentRepository;
        $this->announcementRepo = $announcementRepo;
        $this->applicantRepo = $applicantRepo;
        $this->invoiceRepo = $invoiceRepo;
        $this->studentRegistrationRepo = $studentRegistrationRepo;
        $this->classAssessmentsRepo = $classAssessmentsRepo;
        $this->studentFinancesRepo = $studentFinancesRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->userType->title == 'student') {

            $financialInfo = $this->studentFinancesRepo->getStudentFinancialInfo($user->student);

            $data['announcements'] = $this->announcementRepo->getAllByUserType('Student');

            $data['resultsPublicationStatus'] = $this->classAssessmentsRepo
                ->getStudentAcademicPeriodResultsPublicationStatus(
                    $user->student->id,
                    $financialInfo['academicPeriodInfo']?->academic_period_id
                );

            return view('pages.home.student_home', array_merge($data, $financialInfo));
        } else if ($user->userType->title == 'instructor') {
            $data['announcements'] = $this->announcementRepo->getAllByUserType('Instructor');

            return view('pages.home.instructor_home',  $data);
        } else {
            $data['announcements'] = $this->announcementRepo->getAllByUserType('Super Admin');
            $data['students'] = $this->enrollmentRepository->totalStudents();
            $data['users'] = $this->enrollmentRepository->totalUsers();
            $data['staff'] = $this->enrollmentRepository->totalStaff();
            $data['admin'] = $this->enrollmentRepository->totalAdmin();
            $data['registered'] = $this->enrollmentRepository->getStudentsSumForAllOpenPeriods();
            $data['todaysPayments'] = $this->enrollmentRepository->todaysPayments();
            $data['todaysInvoices'] = $this->enrollmentRepository->todaysInvoices();
            $data['todaysApplicants'] = $this->applicantRepo->getByDate(now())->count();
            $data['applicants'] = $this->applicantRepo->getAll()->count();

            return view('pages.home.home', $data);
        }
    }
}
