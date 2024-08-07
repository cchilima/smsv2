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
use App\Models\Academics\Program;
use App\Models\Admissions\Student;
use App\Models\Enrollments\Enrollment;
use App\Repositories\Academics\AcademicPeriodClassRepository;
use App\Repositories\Academics\AcademicPeriodRepository;
use App\Repositories\Academics\AssessmentTypesRepo;
use App\Repositories\Academics\ClassAssessmentsRepo;
use App\Repositories\Academics\CourseLevelsRepository;
use App\Repositories\Academics\CourseRepository;
use App\Repositories\Academics\ProgramsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class ClassAssessmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $classaAsessmentRepo, $academic, $assessmentTypes, $programsRepo, $levels, $periodClasses, $coursesRepo;

    public function __construct(
        ClassAssessmentsRepo $classaAsessmentRepo,
        AcademicPeriodRepository $academic,
        AssessmentTypesRepo $assessmentTypes,
        ProgramsRepository   $programsRepo,
        CourseLevelsRepository $levels,
        AcademicPeriodClassRepository $periodClasses,
        CourseRepository $courseRepository
    ) {
        //        $this->middleware(TeamSA::class, ['except' => ['destroy','']]);
        //        $this->middleware(TeamSAT::class, ['except' => ['destroy','']]);
        //        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);
        $this->middleware(TeamSAT::class, ['only' => ['destroy',]]);

        $this->classaAsessmentRepo = $classaAsessmentRepo;
        $this->academic = $academic;
        $this->assessmentTypes = $assessmentTypes;
        $this->programsRepo = $programsRepo;
        $this->levels = $levels;
        $this->periodClasses = $periodClasses;
        $this->coursesRepo = $courseRepository;
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
    public function getProgramResults($academic_id)
    {
        $programs =  $this->periodClasses->academicProgramStudents(Qs::decodeHash($academic_id));
        return view('pages.class_assessments.results_program_list', compact('programs'));
    }

    public function StudentListResults($class, $assessid)
    {
        $class = Qs::decodeHash($class);
        $assessID = Qs::decodeHash($assessid);

        //$assessID = Qs::decodeHash($assessid);
        $class_ass = $this->classaAsessmentRepo->getClassAssessments($class, $assessID);
        //dd($class_ass);

        $open = $this->academic->getAllopen();
        //dd($class_ass);
        return view('pages.class_assessments.instructor_assessment.index', compact('class_ass', 'open'));
    }

    public function DownloadResultsTemplate(Request $request)
    {
        $csvContent = "";
        $class = $request->input('classId');
        $assessID = $request->input('assessID');
        $class_ass = $this->classaAsessmentRepo->getClassAssessments($class, $assessID);
        // Add header row for Enrolments By Program
        $csvContent .= "First Name,Last Name,Student ID,Course Code,Course Name,AcademicPeriod,Program,Assessment type,Marked out of,Total\n";
        // Extract data from Blade template loop for Enrolments By Program
        foreach ($class_ass->enrollments as $classData) {
            $last_name = $classData->student->user->first_name;
            $firstname = $classData->student->user->last_name;
            $studentID = $classData->student->id;
            $program = $classData->student->program_id;
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
            $path = $request->file('file')->getRealPath();
            $data = Excel::toCollection('', $path, null, \Maatwebsite\Excel\Excel::TSV)[0];

            $data->forget(0);
            //$data->forget(1);
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
                $user = Student::find($studentID); //where('id', '=', $studentID)->get()->first();
                if (!empty($user)) {
                    $program = $user->program_id;
                    $student_level = $user->course_level_id;
                    $existingRow = Grade::where('student_id', $studentID)->where('assessment_type_id', $aseesID)->where('course_code', $code)->get()->first();

                    if ($existingRow) {
                        $existingRow->delete();
                    }

                    // check if user has registered for this academic period.

                    if ($user) {
                        $lastEnrollment = Enrollment::where('student_id', $studentID)->get()->last();

                        if ($lastEnrollment) {
                            $lastEnrolledClass = AcademicPeriodClass::where('id', $lastEnrollment->academic_period_class_id)->get()->first();
                            // dd($lastEnrollment);
                            if ($lastEnrolledClass) {
                                # Proceed to importing
                                # Add results to imports
                                $course = Course::where('code', $code)->get()->first();
                                if ($course) {
                                    //dd($academicC == $academic && $titleC == $title && $code == $courseC && $aseesID == $AssessIDTemplateC && $total <= $assesTotal);
                                    if ($academicC == $academic && $titleC == $title && $code == $courseC && $aseesID == $AssessIDTemplateC && $total <= $assesTotal) {
                                        $create = Grade::create([
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
                                        //dd($create);
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
            Qs::json($e->getMessage(), false);
        }

        return redirect(route('myClassStudentList', ['class' => Qs::hash($classIDTemplate), 'assessid' => Qs::hash($backroute)]));
    }

    public function GetProgramsToPublishCas(string $id)
    {
        $id = Qs::decodeHash($id);
        $period = $this->academic->find($id);
        $programs = $this->classaAsessmentRepo->publishAvailableProgramsCas($id);
        //dd($programs);
        return view('pages.cas.edit', compact('programs', 'period'));
    }
    public function GetProgramsToPublish(string $id)
    {
        $id = Qs::decodeHash($id);
        $period = $this->academic->find($id);

        $programs = $this->classaAsessmentRepo->publishAvailablePrograms($id);
        //dd($programs);
        return view('pages.class_assessments.edit', compact('programs', 'period'));
    }

    public function GetProgramResultsLevelCas(Request $request)
    {
        $aid = $request->query('aid');
        $pid = $request->query('pid');
        $level = $request->query('level');
        $aid = Qs::decodeHash($aid);
        $pid = Qs::decodeHash($pid);
        $level = Qs::decodeHash($level);
        $grades = $this->classaAsessmentRepo->getCaGrades($level, $pid, $aid);
        $data['period'] = $this->academic->find($aid);
        $data['program_data'] = $this->programsRepo->findOne($pid);
        $data['level'] = $this->levels->find($level);
        $data['students'] = $this->classaAsessmentRepo->total_students($level, $pid, $aid);
        //dd($grades);

        //dd($grades);
        return view('pages.cas.results_review_board', compact('grades'), $data);
    }
    public function LoadMoreResultsCas(Request $request)
    {
        $aid = $request->input('academic');
        $pid = $request->input('program');
        $level = $request->input('level');
        $current_page = $request->input('current_page');
        $last_page = $request->input('last_page');
        $per_page = $request->input('per_page');

        //        $aid = Qs::decodeHash($aid);
        //        $pid = Qs::decodeHash($pid);
        //        $level = Qs::decodeHash($level);
        $grades = $this->classaAsessmentRepo->getCaGradesLoad($level, $pid, $aid, $current_page, $last_page, $per_page);
        return response()->json($grades);
    }
    public function LoadMoreResults(Request $request)
    {


        $aid = $request->input('academic');
        $pid = $request->input('program');
        $level = $request->input('level');
        //        $aid = Qs::decodeHash($aid);
        //        $pid = Qs::decodeHash($pid);
        //        $level = Qs::decodeHash($level);

        $current_page = $request->input('current_page');
        $last_page = $request->input('last_page');
        $per_page = $request->input('per_page');

        $grades = $this->classaAsessmentRepo->getGradesLoad($level, $pid, $aid, $current_page, $last_page, $per_page);
        //dd($level, $pid, $aid,$current_page,$last_page,$per_page);
        return response()->json($grades);
    }
    public function GetProgramResultsLevel(Request $request)
    {
        $aid = $request->query('aid');
        $pid = $request->query('pid');
        $level = $request->query('level');
        $aid = Qs::decodeHash($aid);
        $pid = Qs::decodeHash($pid);
        $level = Qs::decodeHash($level);

        $grades = $this->classaAsessmentRepo->getGrades($level, $pid, $aid);

        $data['period'] = $this->academic->find($aid);
        $data['program_data'] = $this->programsRepo->findOne($pid);
        $data['level'] = $this->levels->find($level);
        $data['students'] = $this->classaAsessmentRepo->total_students($level, $pid, $aid);

        return view('pages.class_assessments.results_review_board', compact('grades'), $data);
    }
    public
    function BoardofExaminersUpdateResults(Request $request)
    {
        $requestData = $request->input('updatedAssessments'); // Get the request data
        //dd($request);
        $operation = $request->input('operation');
        $sOperation = ($operation == 1 ? '+' : '-');
        if (isset($requestData[0]['apid'])) {
            $studentIDs = $this->classaAsessmentRepo->getStudentId($requestData[0]['code'], $requestData[0]['id'], $requestData[0]['apid']);
            foreach ($studentIDs as $studentID) {
                foreach ($requestData as $item) {
                    $currentTotal = $this->classaAsessmentRepo->getGradeAll($item['id'], $item['code'], $item['apid'], $studentID);
                    if ($sOperation == '+') {
                        $newTotal = $currentTotal + $item['total'];
                    } else {
                        $newTotal = $currentTotal - $item['total'];
                    }

                    if ($newTotal > $item['outof']) {
                        $newTotal = $item['outof'];
                    }

                    $this->classaAsessmentRepo->UpdateGradeAll($item['id'], $item['code'], $item['apid'], $studentID, $newTotal);
                }
            }
            return Qs::json('Marks updated successfully', true);
        } else {
            foreach ($requestData as $item) {
                $currentTotal = $this->classaAsessmentRepo->getGrade($item['id']);
                if ($sOperation == '+') {
                    $newTotal = $currentTotal + $item['total'];
                } else {
                    $newTotal = $currentTotal - $item['total'];
                }
                if ($newTotal > $item['outof']) {
                    $newTotal = $item['outof'];
                }
                $data['total'] = $newTotal;
                $this->classaAsessmentRepo->updateGrade($item['id'], $data);
            }
            return Qs::json('Marks updated successfully', true);
        }
    }

    public
    function getAssessToUpdate(Request $request)
    {
        $class_id = $request->input('classID');
        $exam = $request->input('exam');
        $data = $this->classaAsessmentRepo->getClassAssessmentCas($class_id, $exam);
        return response()->json($data);
    }
    public
    function PublishProgramResults(Request $request)
    {;
        $student_id = $request->input('ids');
        $academicPeriodID = $request->input('academicPeriodID');
        $type = $request->input('type');
        $this->classaAsessmentRepo->publishGrades($request->ids, $academicPeriodID, $type);
        return Qs::json('Marks updated successfully', true);
    }
    public function PublishForAllStudents($ac, $type)
    {

        $this->classaAsessmentRepo->publishGrades(null, $ac, $type);

        // $id = Qs::decodeHash($ac);
        $period = $this->academic->find($ac);
        $programs = $this->classaAsessmentRepo->publishAvailableProgramsCas($ac);
        //dd($programs);
        if ($type == 1) {
            return view('pages.class_assessments.edit', compact('programs', 'period'));
        }
        return view('pages.cas.edit', compact('programs', 'period'));

        //return redirect(route('getPublishPrograms',$ac));
    }
    public function MyCAResults()
    {
        $user = Auth::user();
        $results = $this->classaAsessmentRepo->GetCaStudentGrades($user->id);
        $student = $this->classaAsessmentRepo->getStudentDetails($user->id);
        //dd($results);
        return view('pages.students.exams.ca_results', compact('results', 'student'));
    }
    public function MyResults()
    {
        $user = Auth::user();
        $results = $this->classaAsessmentRepo->GetExamGrades($user->id);
        $student = $this->classaAsessmentRepo->getStudentDetails($user->id);
        //dd($results);
        return view('pages.students.exams.exam_results', compact('results', 'student'));
    }
    public
    function PostStudentResults(Request $request)
    {
        $id = $request->input('id');
        $data['total'] = $request->input('total');
        //dd($id);

        //$this->classaAsessmentRepo->update($id,$data);
        $this->classaAsessmentRepo->updatetotaGrade($id, $data['total']);
        return Qs::jsonStoreOk();
    }
    public function AddStudentResult(Request $request)
    {
        $student_id = Qs::decodeHash($request->input('id'));
        $ac_id = Qs::decodeHash($request->input('ac_id'));
        $assess_type_id = Qs::decodeHash($request->input('assess_type_id'));
        $course_id = Qs::decodeHash($request->input('course_id'));
        $course = $this->coursesRepo->find($course_id);
        $student = Student::find($student_id);
        $total = $request->input('total');
        $existingRow = Grade::where('student_id', $student_id)->where('assessment_type_id', $assess_type_id)->where('course_code', $course->code)->get()->first();

        if ($existingRow) {
            $existingRow->delete();
        }
        $create = Grade::create([
            'academic_period_id' => $ac_id,
            'student_id' => $student_id,
            'total' => $total, // Total
            'course_title' => $course->name,
            'course_code' => $course->code,
            'publication_status' => 0,
            'assessment_type_id' => $assess_type_id,
            'student_level_id' => $student->course_level_id,
            'course_id' => $course->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        //dd($student_id);
        return Qs::jsonStoreOk();
    }
    public function getStudentsProgramResults($id)
    {
        $results = $this->classaAsessmentRepo->GetStudentExamGrades($id);
        dd($results);
    }
}
