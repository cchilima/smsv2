<?php

namespace App\Http\Controllers\Reports\Enrollments;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Repositories\Academics\AcademicPeriodRepository;
use App\Repositories\Academics\ProgramsRepository;
use App\Repositories\Reports\enrollments\EnrollmentRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EnrollmentReportsController extends Controller
{
    protected $enrollmentRepository,$academicPeriodRepository, $programsRepository;

    public function __construct(EnrollmentRepository $enrollmentRepository,AcademicPeriodRepository $academicPeriodRepository,ProgramsRepository $programsRepository)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->enrollmentRepository = $enrollmentRepository;
        $this->academicPeriodRepository = $academicPeriodRepository;
        $this->programsRepository = $programsRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['ac'] = $this->academicPeriodRepository->getAllopen();
        $data['program'] = $this->programsRepository->getAll();
        return view('pages.reports.enrollments.index',$data);
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

    public function ExamRegisters()
    {
        $data['ac'] = $this->academicPeriodRepository->getAllopen();
        return view('pages.reports.enrollments.exam_registers',$data);
    }

    public function StudentList()
    {
        $data['ac'] = $this->academicPeriodRepository->getAllopen();
        $data['program'] = $this->programsRepository->getAll();
        return view('pages.reports.enrollments.student_list',$data);
    }

    public function AuditTrailReports()
    {
        return view('pages.reports.enrollments.audit_trail');
    }

    public function DownloadStudentProgramList($ac)
    {
        $academic = $this->enrollmentRepository->getStudentsWithProgramAndAcademicPeriods($ac);
        $fileName = $ac . '-program-student-list-' . now()->format('d-m-Y-His') . '.pdf';
        $pdf = Pdf::loadView('templates.pdf.program-student-list', compact('academic'));

        return $pdf->download($fileName);
        // dd($academic);getStudentsForProgramAndAcademicPeriod($program_id, $academic_period_id)
    }

    public function DownloadStudentProgramListOne($ac, $pid)
    {
        $academic = $this->enrollmentRepository->getStudentsForProgramAndAcademicPeriod($pid, $ac);
        $fileName = $ac . '-program-student-list-' . now()->format('d-m-Y-His') . '.pdf';
        $pdf = Pdf::loadView('templates.pdf.one-program-student-list', compact('academic'));

        return $pdf->download($fileName);
    }

    public function DownloadAcClassLists($ac)
    {

        ini_set('max_execution_time', 3000000);
        $academic = $this->enrollmentRepository->getStudentsWithProgramsForAcademicPeriod($ac);
        // dd($academic);
        $fileName = $ac . '-class-student-list-' . now()->format('d-m-Y-His') . '.pdf';
        $pdf = Pdf::loadView('templates.pdf.class-student-list', compact('academic'));
        return $pdf->download($fileName);
    }

    public function DownloadAcOneClassLists($ac, $classid)
    {

        ini_set('max_execution_time', 3000000);
        $academic = $this->enrollmentRepository->getStudentsWithProgramsForClassAndAcademicPeriod($ac, $classid);
        //dd($academic);
        // dd($academic);
        $fileName = $ac . '-class-student-list-' . now()->format('d-m-Y-His') . '.pdf';
        $pdf = Pdf::loadView('templates.pdf.class-one-student-list', compact('academic'));
        return $pdf->download($fileName);
    }
    public function ExamRegistersDownload(Request $request){
         $ac_id = $request->input('ac_id');
        $class_id = $request->input('class_id');

        $academics = $this->enrollmentRepository->getStudentsWithProgramsForClassesAndAcademicPeriods($ac_id, $class_id);
       // dd($academics);
        $fileName =  'E-exam-register-' . now()->format('d-m-Y-His') . '.pdf';
        $pdf = Pdf::loadView('templates.pdf.exam_register', compact('academics'));
        return $pdf->download($fileName);
       // dd($academic);
    }

    public function DownloadstudentProgramListCsv($ac)
    {
        $academic = $this->enrollmentRepository->getStudentsWithProgramAndAcademicPeriods($ac);

        $csvContent = "";

        // Initialize CSV content with header row
        $csvContent = "Name,Student ID,Email,Gender,Level,Program,Payment Percentage,Balance\n";

// Iterate through each academic period's programs and students
        foreach ($academic['programs'] as $program) {
            foreach ($program['students'] as $student) {
                // Format CSV data for each student
                $csvContent .= "{$student['name']},{$student['student_id']},{$student['email']},{$student['gender']},{$student['level']},{$program['program_name']},{$student['payment_percentage']},{$student['balance']}\n";
            }
        }

// Generate filename
        // $filename = 'student-list-' . now()->format('Y-m-d-His') . '.csv';

// Write CSV content to file
        // file_put_contents($filename, $csvContent);
        $filename = 'student-list-' . now()->format('Y-m-d-His') . '.csv';

// Write CSV content to file
        file_put_contents($filename, $csvContent);

// Return file download response
        return response()->download($filename)->deleteFileAfterSend(true);
    }

    public function DownloadStudentProgramListOneCSV($ac, $pid)
    {
        $academic = $this->enrollmentRepository->getStudentsForProgramAndAcademicPeriod($pid, $ac);
        // Initialize CSV content with header row
        $csvContent = "Name,Student ID,Email,Gender,Level,Payment Percentage,Balance\n";

// Iterate through each student in the academic period
        foreach ($academic['students'] as $student) {
            // Format CSV data for each student
            $csvContent .= "{$student['name']},{$student['student_id']},{$student['email']},{$student['gender']},{$student['level']},{$student['payment_percentage']},{$student['balance']}\n";
        }

// Generate filename
        $filename = 'student-list-' . now()->format('Y-m-d-His') . '.csv';

// Write CSV content to file
        file_put_contents($filename, $csvContent);

// Return file download response
        return response()->download($filename)->deleteFileAfterSend(true);
    }

    public function DownloadAcClassListsCSV($ac)
    {

        ini_set('max_execution_time', 3000000);
        $academic = $this->enrollmentRepository->getStudentsWithProgramsForAcademicPeriod($ac);
        // Initialize CSV content with header row
        $csvContent = "Class Code,Class Name\n";

// Iterate through each class in the academic period
        foreach ($academic['classes'] as $class) {
            $csvContent .= "{$class['class_code']},{$class['class_name']}\n\n\n";

            $csvContent .= "Student ID,Student Name,Email,Gender,Level,Payment Percentage,Balance\n";
            // Iterate through each student in the class
            foreach ($class['students'] as $student) {
                // Format CSV data for each student
                $csvContent .= "{$student['student_id']},{$student['name']},{$student['email']},{$student['gender']},{$student['level']},{$student['payment_percentage']},{$student['balance']}\n\n";
            }
        }

// Generate filename
        $filename = 'student-list-' . now()->format('Y-m-d-His') . '.csv';

// Write CSV content to file
        file_put_contents($filename, $csvContent);

// Return file download response
        return response()->download($filename)->deleteFileAfterSend(true);

    }

    public function DownloadAcOneClassListsCSV($ac, $classid)
    {

        ini_set('max_execution_time', 3000000);
        $academic = $this->enrollmentRepository->getStudentsWithProgramsForClassAndAcademicPeriod($ac, $classid);

        // Initialize CSV content with header row
        $csvContent = "Class Code,Class Name,Student ID,Student Name,Email,Gender,Level,Payment Percentage,Balance\n";

// Iterate through each student in the class
        foreach ($academic['class']['students'] as $student) {
            // Format CSV data for each student
            $csvContent .= "{$academic['class_code']},{$academic['class_name']},{$student['student_id']},{$student['name']},{$student['email']},{$student['gender']},{$student['level']},{$student['payment_percentage']},{$student['balance']}\n";
        }

// Generate filename
        $filename = 'class-student-list-' . now()->format('Y-m-d-His') . '.csv';

// Write CSV content to file
        file_put_contents($filename, $csvContent);

// Return file download response
        return response()->download($filename)->deleteFileAfterSend(true);

    }

    public function acsPrograms(Request $request){

        $academic_period_ids = $request->input('ac');
        $program_ids = $request->input('program');

        // Initialize CSV content with header row
        $csvContent = "Academic Period ID,Academic Period Name,Program ID,Program Name,Student ID,Student Name,Student Number,Gender,Payment Percentage,Balance\n";

// Retrieve data using the getStudentsForPeriodsAndPrograms function
        $results = $this->enrollmentRepository->getStudentsForPeriodsAndPrograms($academic_period_ids, $program_ids);

// Iterate through each academic period
        foreach ($results as $academicPeriod) {
            // Write academic period information in CSV
            $academicPeriodId = $academicPeriod['academic_period_id'];
            $academicPeriodName = $academicPeriod['academic_period_name'];

            // Iterate through each program in the academic period
            foreach ($academicPeriod['programs'] as $program) {
                // Write program information in CSV
                $programId = $program['program_id'];
                $programName = $program['program_name'];

                // Iterate through each student in the program
                foreach ($program['students'] as $student) {
                    // Format CSV data for each student
                    $studentId = $student['student_id'];
                    $studentName = $student['name'];
                    $studentNumber = $student['student_number'];
                    $gender = $student['gender'];
                    $paymentPercentage = $student['payment_percentage'];
                    $balance = $student['balance'];

                    // Append data to CSV content
                    $csvContent .= "$academicPeriodId,$academicPeriodName,$programId,$programName,$studentId,$studentName,$studentNumber,$gender,$paymentPercentage,$balance\n";
                }
            }
        }

// Generate filename
        $filename = 'students-for-periods-and-programs-' . now()->format('Y-m-d-His') . '.csv';

// Write CSV content to file
        file_put_contents($filename, $csvContent);

// Return file download response
        return response()->download($filename)->deleteFileAfterSend(true);

    }
}
