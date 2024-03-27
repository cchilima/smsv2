<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\Reports\enrollments\EnrollmentRepository;
use App\Repositories\Announcements\AnnouncementRepository;
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

    public function __construct( EnrollmentRepository $enrollmentRepository, AnnouncementRepository $announcementRepo)
    {
        $this->middleware('auth');
        $this->enrollmentRepository = $enrollmentRepository;
        $this->announcementRepo = $announcementRepo;
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

            $data['announcements'] = $this->announcementRepo->getAllStudentAnnouncements();

            return view('pages.home.student_home', $data);

        }else if ($user->userType->title == 'instructor'){

            $data['announcements'] = $this->announcementRepo->getAllInstructorAnnouncements();

            return view('pages.home.instructor_home',  $data);

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
