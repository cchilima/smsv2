<?php

namespace App\Http\Controllers\Users;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Middleware\Custom\TeamSAT;
use App\Models\Accounting\Invoice;
use App\Repositories\Academics\ClassAssessmentsRepo;
use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Accounting\PaymentMethodRepository;
use App\Repositories\Accounting\QuotationRepository;
use App\Repositories\Admissions\StudentRepository;
use App\Repositories\Enrollments\EnrollmentRepository;
use App\Repositories\Users\userNextOfKinRepository;
use App\Repositories\Users\UserPersonalInfoRepository;
use App\Repositories\Users\UserRepository;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected
        $studentRepo,
        $registrationRepo,
        $userPersonalInfoRepo,
        $userRepo,
        $userNextOfKinRepo,
        $enrollmentRepo,
        $classaAsessmentRepo,
        $paymentMethodRepo,
        $quotationRepo;

    public function __construct(
        StudentRepository $studentRepo,
        StudentRegistrationRepository $registrationRepo,
        UserPersonalInfoRepository $userPersonalInfoRepo,
        UserRepository $userRepo,
        userNextOfKinRepository $userNextOfKinRepo,
        EnrollmentRepository $enrollmentRepo,
        ClassAssessmentsRepo $classaAsessmentRepo,
        PaymentMethodRepository $paymentMethodRepo,
        QuotationRepository $quotationRepo
    ) {
        //        $this->middleware(TeamSA::class, ['except' => ['destroy']]);
        //        $this->middleware(SuperAdmin::class, ['only' => ['destroy']]);
        $this->middleware(TeamSAT::class, ['except' => ['destroy',]]);

        $this->studentRepo = $studentRepo;
        $this->registrationRepo = $registrationRepo;
        $this->userPersonalInfoRepo = $userPersonalInfoRepo;
        $this->userRepo = $userRepo;
        $this->userNextOfKinRepo = $userNextOfKinRepo;
        $this->enrollmentRepo = $enrollmentRepo;
        $this->classaAsessmentRepo = $classaAsessmentRepo;
        $this->paymentMethodRepo = $paymentMethodRepo;
        $this->quotationRepo = $quotationRepo;
    }

    public function profile()
    {
        try {
            $student = Auth::user()->student;
            $data['student'] = $student;
            $data['enrollments']  = $this->enrollmentRepo->getEnrollments($student->id);

            return view('pages.students.profile', $data);
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to load student profile');
        }
    }

    public function finances()
    {
        $student = Auth::user()->student;
        $periodInfo = $this->quotationRepo->openAcademicPeriod($student);

        return view('pages.students.finances', compact('student', 'periodInfo'));
    }

    public function howToMakePayments()
    {
        $paymentMethods = $this->paymentMethodRepo->getAll();
        return view('pages.students.help.make-payments', compact('paymentMethods'));
    }
}
