<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\TeamSAT;
use App\Http\Requests\ClassAssessment\ClassAssessments;
use App\Models\Academics\ClassAssessment;
use App\Repositories\Academics\AcademicPeriodRepository;
use App\Repositories\Academics\AssessmentTypesRepo;
use App\Repositories\Academics\ClassAssessmentsRepo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class ClassAssessmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $classaAsessmentRepo, $academic, $assessmentTypes;

    public function __construct(ClassAssessmentsRepo $classaAsessmentRepo, AcademicPeriodRepository $academic, AssessmentTypesRepo $assessmentTypes)
    {
//        $this->middleware(TeamSA::class, ['except' => ['destroy','']]);
//        $this->middleware(TeamSAT::class, ['except' => ['destroy','']]);
//        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);
        $this->middleware(TeamSAT::class, ['only' => ['destroy',]]);

        $this->classaAsessmentRepo = $classaAsessmentRepo;
        $this->academic = $academic;
        $this->assessmentTypes = $assessmentTypes;
    }

    public function index()
    {

        $data['open'] = $this->academic->getAllopen();
        $data['assess'] = $this->assessmentTypes->getAll();
        $data['academicPeriodsArray'] = $this->academic->getAcadeperiodClassAssessments();
        //dd($data['academicPeriodsArray']);
        return view('pages.class_assessments.index', $data);

    }
    public
    function getClasses(string $id)
    {
        $classes = $this->academic->getAcadeperiodClasses($id);
        return $classes;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['open'] = $this->academic->getAllopen();
        return view('pages.academics.class_assessments.show', $data);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassAssessments $req)
    {
        $data = $req->only(['assessment_type_id', 'academic_period_class_id', 'total', 'end_date']);
        $classData = ClassAssessment::where('academic_period_class_id', $data['academic_period_class_id'])->get();
        $data['end_date'] = date('Y-m-d', strtotime($data['end_date']));
        $data['key'] = $data['assessment_type_id'] . '-' . $data['assessment_type_id'];
        $totalValue = 0;
        foreach ($classData as $class) {
            $totalValue = $totalValue + $class['total'];
        }
        $existingRecord = ClassAssessment::where([
            'assessment_type_id' => $data['assessment_type_id'],
            'academic_period_class_id' => $data['academic_period_class_id']
        ])->first();

        if ($existingRecord) {
            return Qs::json('Data already exists', false);
        } else {
            if ($totalValue > 100 || ($totalValue + $data['total']) > 100) {
                return Qs::json('Total for the assessment is greater than 100', false);
            } else {
                $this->classaAsessmentRepo->create($data);
                return Qs::jsonStoreOk();
            }
        }
        //dd($totalValue);

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
    public
    function UpdateTotalResultsExams(Request $request, string $id)
    {
        $id = Qs::decodeHash($id);
        $total = $request->input('total');
        $data['total'] = $request->input('total');
        if ($request->input('end_date') !== null || !empty($request->input('end_date'))) {
            $data['end_date'] = date('Y-m-d', strtotime($request->input('end_date')));
            //$data['end_date'] = $request->input('end_date');
        }
        if ($request->input('total') !== null || !empty($request->input('total'))) {
            $data['total'] = $request->input('total');
        }
        if (count($data) > 0) {
            $this->classaAsessmentRepo->update($id, $data);
            return Qs::jsonUpdateOk();
        } else {
            return Qs::json('error failed update', false);
        }
    }
    public function getClassesToPublish($academic_id)
    {
        $id = Qs::decodeHash($academic_id);
        $apClasses = $this->academic->showClasses($id);
        //dd($apClasses);
            return view('pages.class_assessments.show_classes', compact('apClasses'));
    }
    public function StudentListResults($class, $assessid)
    {
        $class = Qs::decodeHash($class);
        $assessID = Qs::decodeHash($assessid);
        $data = [];
        return view('pages.class_assessments.instructor_assessment.index', compact('class'), $data);
    }

    public function DownloadResultsTemplate(Request $request)
    {
        $classes = $request->input('classID');
        $assessID = $request->input('assessID');
        $csvContent = "";
        $class = $request->input('classId');
        $assessID = $request->input('assessID');
        //dd($class);
        //return Classes::with('class_assessments.assessment_type', 'instructor', 'course')->find($id);
        $academicPeriodsData = Classes::where('id', $class)->with(['course', 'enrollments.user', 'academicPeriod', 'assessments'])
            ->get();
        $assessment_total = ClassAssessment::where('classID', $class)->where('assesmentID', $assessID)->get()->first();
        $aseessname = AssessmentTypes::find($assessID);
        $class = $academicPeriodsData->map(function ($class) use ($assessment_total, $aseessname, $assessID) {
            return [
                'classID' => $class->id,
                'courseName' => $class->course->name,
                'courseCode' => $class->course->code,
                'assess_total' => $assessment_total->total,
                'assessmentId' => $assessID,
                'assessmentName' => $aseessname->name,
                'instructor' => [
                    'instructorID' => $class->instructorID,
                    'instructorName' => $class->instructor->first_name . ' ' . $class->instructor->last_name,
                ],
                'apid' => $class->academicPeriodID,
                'code' => $class->academicPeriod->code,
                'students' => $class->enrollments->map(function ($enrollment) use ($assessID, $class) {
                    return [
                        'userID' => $enrollment->user->id,
                        'student_id' => $enrollment->user->student_id,
                        'first_name' => $enrollment->user->first_name,
                        'last_name' => $enrollment->user->last_name,
                        'program' => self::getUserProgramID($enrollment->user->id),
                        'total' => $this->getcurrentTotalonImports($assessID, $enrollment->user->student_id, $enrollment->user->id, $class->academicPeriodID, $class->course->code)//to get this from imports table
                    ];
                }),
            ];
        })->toArray();
        // Add header row for Enrolments By Program
        $csvContent .= "First Name,Last Name,Student ID,Course Code,Course Name,AcademicPeriod,Program,Assessment type,Marked out of,Total\n";
        // Extract data from Blade template loop for Enrolments By Program
        foreach ($class as $classData) {
            foreach ($classData['students'] as $student) {

                $last_name = $student['first_name'];
                $firstname = $student['last_name'];
                $studentID = $student['student_id'];
                $program = $student['program'];
                $courseCode = $classData['courseCode'];
                $courseName = $classData['courseName'];
                $apid = $classData['apid'];
                $assessmentId = $classData['assessmentId'];
                $total = '';
                $sometotal = $classData['assess_total'];
                $csvContent .= "$firstname,$last_name,$studentID,$courseCode,$courseName,$apid,$program,$assessmentId,$sometotal,$total\n";
            }
            // dd($classData);
        }
        $filename = 'results_upload_template.csv';
        file_put_contents($filename, $csvContent);
        $response = [
            'fileUrl' => asset($filename), // Generate a URL to the file
        ];

        return response()->json($response);

    }

    public
    function getcurrentTotalonImports($assessID, $studeID, $id, $academic, $code)
    {
        //$studeID = 1913589;$id = 9;$academic = 29;$code ='BAC 1100';
        $program = self::getUserProgramID($id);
        $result = ImportList::where('academicPeriodID', $academic)->where('programID', $program)
            ->where('studentID', $studeID)->where('assessmentID', $assessID)->where('code', $code)->get()->last();
        //dd($result);
        if ($result) {
            return $result->total;
        } else {
            return 0;
        }
    }


}
