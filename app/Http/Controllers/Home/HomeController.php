<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\Reports\enrollments\EnrollmentRepository;
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
    public function __construct( EnrollmentRepository $enrollmentRepository)
    {
        $this->middleware('auth');
        $this->enrollmentRepository = $enrollmentRepository;
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
            return view('pages.home.student_home');
        }else if ($user->userType->title == 'instructor'){
            return view('pages.instructor_home.home');
        }else {
            $data['students'] = $this->enrollmentRepository->totalStudents();
            $data['users'] = $this->enrollmentRepository->totalUsers();
            $data['staff'] = $this->enrollmentRepository->totalStaff();
            $data['admin'] = $this->enrollmentRepository->totalAdmin();
            $data['registered'] = $this->enrollmentRepository->getStudentsSumForAllOpenPeriods();
            $data['todaysPayments'] = $this->enrollmentRepository->todaysPayments();
            $data['todaysInvoices'] = $this->enrollmentRepository->todaysInvoices();
            return view('pages.home.home',$data);
        }
    }
}
