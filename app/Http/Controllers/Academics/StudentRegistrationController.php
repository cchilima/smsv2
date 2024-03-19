<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Repositories\Academics\StudentRegistrationRepository;


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
       $academicInfo = $this->registrationRepo->getSummaryAcademicInfo($academicPeriodId);


       $filename = 'registration_summary.pdf';

       // Gather data
       $studentData = [
        'university' => 'Zambia University College of Technology',
        'student_name' => $student->user->first_name . ' ' . $student->user->last_name,
        'student_id' => $student->id,
        'year_of_study' => $student->level->name,
        'programme' => $student->program->name,
        'programme_code' => $student->program->code,
        'academic_period' => $academicInfo->academic_period->name,
        'registered_courses' => $courses,
    ];


       // HTML content
       $html  = '<p style="text-align:center"><img width="80px" height="80px" src="https://www.zictcollege.ac.zm/images/logo-white.png" /></p>';
       $html .= '<p style="text-align:center"><b>' . $studentData['university'] . ' </b></p>';
       $html .= '<p style="text-align:center"><b>' . $studentData['student_name'] . '</b></p>';
       $html .= '<hr>';
       $html .= '<p>Student ID: ' . $studentData['student_id'] . '</p>';
       $html .= '<p>Year of study: ' . $studentData['year_of_study'] . '</p>';
       $html .= '<p>Programme: ' . $studentData['programme'] . '</p>';
       $html .= '<p>Programme Code: ' . $studentData['programme_code'] . '</p>';
       $html .= '<p>Academic Period: ' . $studentData['academic_period'] . '</p>';
       $html .= '<hr>';
       $html .= '<p style="font-weight: bold; text-align:center; margin-bottom: 10px;">Registered Courses</p>';

       foreach ($studentData['registered_courses'] as $key => $course) {
           $html .= '<p>' . ++$key . '. ' . $course->code . ' ' . $course->name . '</p>';
       }

       // Create a new TCPDF object
       $pdf = new TCPDF();

       $pdf::SetTitle('Registration Summary');
       $pdf::AddPage();
       $pdf::writeHTML($html, true, false, true, false, '');

       // Output the PDF to the browser or save it to a file
       return $pdf::Output($filename, 'D');
    }
}
