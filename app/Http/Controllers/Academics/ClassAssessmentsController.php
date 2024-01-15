<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\TeamSAT;
use App\Http\Requests\ClassAssessment\ClassAssessments;
use App\Http\Requests\Courses\Courses;
use App\Models\Academics\AcademicPeriodClass;
use App\Models\Academics\ClassAssessment;
use App\Models\Academics\Course;
use App\Models\Academics\Grade;
use App\Models\Admissions\Student;
use App\Models\Enrollments\Enrollment;
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

        //$assessID = Qs::decodeHash($assessid);
        $class_ass = $this->classaAsessmentRepo->getClassAssessments($class,$assessID);
        //dd($class_ass);

        $open = $this->academic->getAllopen();
        return view('pages.class_assessments.instructor_assessment.index', compact('class_ass','open'));
    }

    public function DownloadResultsTemplate(Request $request)
    {
        $csvContent = "";
        $class = $request->input('classId');
        $assessID = $request->input('assessID');
        $class_ass = $this->classaAsessmentRepo->getClassAssessments($class,$assessID);
        // Add header row for Enrolments By Program
        $csvContent .= "First Name,Last Name,Student ID,Course Code,Course Name,AcademicPeriod,Program,Assessment type,Marked out of,Total\n";
        // Extract data from Blade template loop for Enrolments By Program
        foreach ($class_ass->enrollments as $classData) {
                $last_name = $classData->user->first_name;
                $firstname = $classData->user->last_name;
                $studentID = $classData->user->student->id;
                $program = $classData->user->student->program_id;
                $courseCode = $class_ass->course->code;
                $courseName = $class_ass->course->name;
                $apid = $class_ass->academic_period_id;
                $assessmentId = $class_ass->class_assessments[0]->assessment_type_id;
                $total = '';
                $sometotal = $class_ass->class_assessments[0]->total;
                $csvContent .= "$firstname,$last_name,$studentID,$courseCode,$courseName,$apid,$program,$assessmentId,$sometotal,$total\n";
            // dd($classData);
        }
        $filename = 'results_upload_template.csv';
        file_put_contents($filename, $csvContent);
        $response = [
            'fileUrl' => asset($filename), // Generate a URL to the file
        ];

        return response()->json($response);

    }
    public function ProcessUploadedResults(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required',
            'academic' => 'required',
            'course' => 'required',
            'title' => 'required',
            'AssessIDTemplate' => 'required',
            'classIDTemplate' => 'required',
            'assesTotal' => 'required',
            'instructor' => 'sometimes',
            'backroute' => 'required'
        ]);



        try {
            $academicC = $validatedData['academic'];
            $courseC = $validatedData['course'];
            $titleC = $validatedData['title'];
            $AssessIDTemplateC = $validatedData['AssessIDTemplate'];
            $classIDTemplate = $validatedData['classIDTemplate'];
            $assesTotal = $validatedData['assesTotal'];
            //$program = $validatedData['programID'];
            $isInstructor = $validatedData['instructor'] == 'instructorav' ? 1 : 0;
            $backroute = $validatedData['backroute'];

            //$import = new Grade(); // Replace with your import class
            //$data = Excel::toCollection($import, $request->file('file'))[0]; // Get the data from the file

            $path = $request->file('file')->getRealPath();
            $data = Excel::toCollection('', $path, null, \Maatwebsite\Excel\Excel::TSV)[0];

            // Loop through the data and add two additional columns
            /*$processedData = $data->map(function ($row) use ($program, $academic) {
                // Add two additional columns with desired values
                $row['academicPeriodID'] = $academic;
                $row['programID'] = $program;

                return $row;
            });*/
            //dd($data);

            $data->forget(0);
            //$firstElement = $data->shift();

            foreach ($data as $row) {
                $academicPeriodID = $academicC;
                $studentID = $row[2];    // Student ID
                $code = $row[3];
                $title = $row[4];
                $academic = $row[5];
                $aseesID = $row[7];
                $total = $row[9];
                $outof = $row[8];
                $user = Student::where('id', '=', $studentID)->get()->first();
                if (!empty($user)) {
                    $program = $user->program_id;
                    $student_level = $user->course_level_id;
                    $existingRow = Grade::where('student_id', $studentID)->where('assessment_type_id', $aseesID)->where('course_code', $code)->get()->first();

                    if ($existingRow) {
                        $existingRow->delete();
                    }

                    // check if user has registered for this academic period.

                    if ($user) {
                        $lastEnrollment = Enrollment::where('user_id', $user->user_id)->get()->last();

                        if ($lastEnrollment) {
                            $lastEnrolledClass = AcademicPeriodClass::where('id', $lastEnrollment->academic_period_class_id)->get()->first();
                            // dd($lastEnrollment);
                            if ($lastEnrolledClass) {
                                # Proceed to importing
                                # Add results to imports
                                $course = Course::where('code', $code)->get()->first();
                                if ($course) {
                                    if ($academicC == $academic && $titleC == $title && $code == $courseC && $aseesID == $AssessIDTemplateC && $total <= $assesTotal) {
                                        Grade::create([
                                            'academic_period_id' => $academicPeriodID,
                                            'student_id' => $studentID,
                                            'total' => $total, // Total
                                            'course_title' => $title,
                                            'course_code' => $code,
                                            'publication_status' => 0,
                                            'assessment_type_id' => $aseesID,
                                            'student_level_id' => $student_level,
                                            'course_id' => $course->id,
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            //return redirect()->route('dashboard')->with('error', 'Error importing data: ' . $e->getMessage());
           // dd($e->getMessage());
            Qs::json($e->getMessage(),false);
        }

        return redirect(route('myClassStudentList', ['class' => Qs::hash($classIDTemplate), 'assessid' => Qs::hash($backroute)]));
    }

    public function GetProgramsToPublishCas(string $id)
    {
        $id = Qs::decodeHash($id);
        $id = Qs::decodeHash($id);

        $programs = $this->classaAsessmentRepo->publishAvailablePrograms();

        foreach ($programs as $program) {
            $programName = $program->program_id;
            $availableCourseLevels = $program->programCourses->pluck('course_level_id')->unique()->toArray();

            echo "Program: $programName => Available Course Levels: " . implode(', ', $availableCourseLevels) . "\n";
        }
        dd($programs);

        //dd($id);
        $grouped = DB::table('ac_gradebook_imports')
            ->join('ac_programs', 'ac_programs.id', '=', 'ac_gradebook_imports.programID')
            ->join('ac_academicPeriods', 'ac_academicPeriods.id', '=', 'ac_gradebook_imports.academicPeriodID')
            ->join('ac_qualifications', 'ac_qualifications.id', '=', 'ac_programs.qualification_id')
            //->join('ac_programCourses', 'ac_programCourses.programID', '=', 'ac_programs.id')
            ->join('ac_course_levels', 'ac_gradebook_imports.student_level_id', '=', 'ac_course_levels.id')
            ->select(
                'ac_programs.id',
                'ac_programs.name',
                'ac_programs.code',
                'ac_academicPeriods.code as ac_code',
                'ac_qualifications.name AS qualification',
                'ac_course_levels.id as level_id',
                'ac_course_levels.name as level_name',
                'status',
                DB::raw('COUNT(DISTINCT studentID) as students')
            )
            ->where('ac_gradebook_imports.academicPeriodID', '=', $id)
            // ->groupBy('ac_programs.id')
            ->distinct()
            ->groupBy('ac_programs.id', 'ac_programs.name', 'ac_programs.code', 'ac_code', 'status', 'level_id', 'level_name')
            ->get();

        $groupedApClasses = [];
        foreach ($grouped as $program) {
            $programID = $program->id;

            // Create an array for the class if it doesn't exist
            if (!isset($groupedApClasses[$programID])) {
                $groupedApClasses[$programID] = [
                    'code' => $program->code,
                    'id' => $program->id,
                    'name' => $program->name,
                    'ac_code' => $program->ac_code,
                    'qualification' => $program->qualification,
                    'students' => 0,
                    'status' => $program->status,
                    'levels' => [],
                ];
            }
            $groupedApClasses[$programID]['students'] += $program->students;
            // Check if the level data is not already present
            $levelData = [
                'level_name' => $program->level_name,
                'level_id' => $program->level_id,
                //'students' => $program->students,
            ];
            if (!in_array($levelData, $groupedApClasses[$programID]['levels'])) {
                // Add assessment data to the class's levels array
                $groupedApClasses[$programID]['levels'][] = $levelData;
            }
        }


        // Convert the associative array to indexed array if needed
        $programs = array_values($groupedApClasses);
        //dd($programs);

        $academic['apid'] = $id;
        $academic['period'] = \App\Models\Academics\AcademicPeriods::find($id);


        //dd($programs[0]->name);
        //return view('pages.academics.cas.edit', compact('programs'), $academic);
    }

}
