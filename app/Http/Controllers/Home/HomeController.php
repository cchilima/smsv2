<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\Academics\ClassAssessmentsRepo;
use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Accounting\InvoiceRepository;
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
        ClassAssessmentsRepo $classAssessmentsRepo
    ) {
        $this->middleware('auth');
        $this->enrollmentRepository = $enrollmentRepository;
        $this->announcementRepo = $announcementRepo;
        $this->applicantRepo = $applicantRepo;
        $this->invoiceRepo = $invoiceRepo;
        $this->studentRegistrationRepo = $studentRegistrationRepo;
        $this->classAssessmentsRepo = $classAssessmentsRepo;
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

            $data['balancePercentage'] = $this->invoiceRepo->paymentPercentageAllInvoices($user->student->id);

            $data['academicPeriod'] = $this->studentRegistrationRepo->getNextAcademicPeriod($user->student, date('Y-m-d'));

            $data['totalFees'] = $this->invoiceRepo->getStudentAcademicPeriodFeesTotal($user->student->id);
            $data['totalPayments'] = $this->invoiceRepo->getStudentAcademicPeriodPaymentsTotal($user->student->id);
            $data['paymentPercentage'] = $this->invoiceRepo->paymentPercentage($user->student->id);
            $data['registrationStatus'] = $this->studentRegistrationRepo->getRegistrationStatus($user->student->id);
            $data['paymentBalance'] = $this->invoiceRepo->getStudentPaymentBalance($user->student->id);


            $studentInvoicedForCurrentAcademicPeriod = $user->student->invoices()->where('academic_period_id', $data['academicPeriod']?->academic_period_id)->exists();

            if (!$studentInvoicedForCurrentAcademicPeriod) {
                $data['totalFees'] = 0;
                $data['paymentBalance'] = 0;
                $data['paymentPercentage'] = 0;
                $data['totalPayments'] = 0;
            }

            $data['registrationBalance'] = 0;
            $data['viewResultsBalance'] = 0;

            if ($data['balancePercentage'] < 100 && !$studentInvoicedForCurrentAcademicPeriod) {
                $data['academicPeriod'] = $this->invoiceRepo->latestPreviousAcademicPeriod($user->student);
                $data['totalFees'] = $this->invoiceRepo->getStudentAcademicPeriodFeesTotal($user->student->id, true);
                $data['totalPayments'] = $this->invoiceRepo->getStudentAcademicPeriodPaymentsTotal($user->student->id, true);
                $data['paymentPercentage'] = $this->invoiceRepo->paymentPercentage($user->student->id, true);


                $data['registrationStatus'] = true;
                $data['paymentBalance'] = $this->invoiceRepo->getStudentPaymentBalance($user->student->id, true);

                $data['registrationBalance'] = ($data['academicPeriod']?->registration_threshold / 100) * $data['totalFees'] - $data['totalPayments'];

                $data['viewResultsBalance'] = ($data['academicPeriod']?->view_results_threshold / 100) * $data['totalFees'] - $data['totalPayments'];
            }

            if ($studentInvoicedForCurrentAcademicPeriod) {
                $invoices = $user->student->invoices()->where('academic_period_id', $data['academicPeriod']->academic_period_id)->get();

                $totalFees = 0;

                foreach ($invoices as $invoice) {
                    $totalFees += $invoice->details->sum('amount');
                }

                $data['totalFees'] = $totalFees;

                $academicPeriodPaymentsTotal =
                    $this->invoiceRepo->studentPaymentsAgainstInvoice($user->student, $data['academicPeriod']->academic_period_id);

                $data['paymentBalance'] = $totalFees - $academicPeriodPaymentsTotal;

                $data['totalPayments'] = $academicPeriodPaymentsTotal;

                $data['registrationBalance'] = ($data['academicPeriod']?->registration_threshold / 100) * $data['totalFees'] - $data['totalPayments'];

                $data['viewResultsBalance'] = ($data['academicPeriod']?->view_results_threshold / 100) * $data['totalFees'] - $data['totalPayments'];

                $data['paymentPercentage'] = $academicPeriodPaymentsTotal / $totalFees * 100;
            }


            $data['announcements'] = $this->announcementRepo->getAllByUserType('Student');

            $data['resultsPublicationStatus'] = $this->classAssessmentsRepo
                ->getStudentAcademicPeriodResultsPublicationStatus(
                    $user->student->id,
                    $data['academicPeriod']?->academic_period_id
                );

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
