<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\Accounting\InvoiceRepository;
use App\Repositories\Reports\enrollments\EnrollmentRepository;
use App\Repositories\Announcements\AnnouncementRepository;
use App\Repositories\Applications\ApplicantRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     *
     */
    protected $enrollmentRepository;
    protected $announcementRepo;
    protected $applicantRepo;
    protected $invoiceRepo;

    public function __construct(
        EnrollmentRepository $enrollmentRepository,
        AnnouncementRepository $announcementRepo,
        ApplicantRepository $applicantRepo,
        InvoiceRepository $invoiceRepo
    ) {
        $this->middleware('auth');
        $this->enrollmentRepository = $enrollmentRepository;
        $this->announcementRepo = $announcementRepo;
        $this->applicantRepo = $applicantRepo;
        $this->invoiceRepo = $invoiceRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $data['user'] = $user;

        if ($user->userType->title == 'student') {

            $data['announcements'] = $this->announcementRepo->getAllByUserType('Student');
            $data['totalFees'] = $this->invoiceRepo->getStudentAcademicPeriodFeesTotal($user->student->id);
            $data['totalPayments'] = $this->invoiceRepo->getStudentAcademicPeriodPaymentsTotal($user->student->id);
            $data['paymentPercentage'] = $this->invoiceRepo->paymentPercentage($user->student->id);
            $data['paymentBalance'] = $this->invoiceRepo->getStudentPaymentBalance($user->student->id);

            return view('pages.home.student_home', $data);
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
