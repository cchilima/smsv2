<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\APInformation\APinformation;
use App\Http\Requests\APInformation\APinformationUpdate;
use App\Models\Academics\ProgramCourses;
use App\Repositories\Academics\AcademicPeriodClassRepository;
use App\Repositories\Academics\AcademicPeriodRepository;
use App\Repositories\Academics\ProgramCoursesRepository;
use App\Repositories\Academics\ProgramsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class APManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $periods, $periodClasses,$programsCourses;

    public function __construct(AcademicPeriodRepository $periods,AcademicPeriodClassRepository $periodClasses, AcademicPeriodClassRepository $programsCourses)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->periods = $periods;
        $this->periodClasses = $periodClasses;
        $this->programsCourses = $programsCourses;
    }

    public function index()
    {
        $acid = request()->query('ac');
        $academic= $this->periods->findOne($acid);
        $studyModes = $this->periods->getStudyModes();
        $intakes = $this->periods->getIntakes();
        $fees = $this->periods->getFees();
        $courses = $this->periodClasses->getCourses();
        $instructors = $this->periodClasses->getInstructors();
        $programsCourses = $this->programsCourses->academicPrograms($acid);
        //dd($programsCourses);
        return view('pages.academicPeriodInformation.index', compact('courses', 'studyModes','programsCourses', 'intakes','academic','instructors','fees'));
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
    public function store(APinformation $request)
    {
        //

        $data = $request->only(['academic_period_intake_id', 'study_mode_id', 'view_results_threshold',
            'exam_slip_threshold', 'registration_threshold', 'late_registration_end_date', 'late_registration_date',
            'registration_date','academic_period_id']);

        $data['late_registration_end_date'] = date('Y-m-d', strtotime($data['late_registration_end_date']));
        $data['late_registration_date'] = date('Y-m-d', strtotime($data['late_registration_date']));
        $data['registration_date'] = date('Y-m-d', strtotime($data['registration_date']));
        $period = $this->periods->APcreate($data);

        if ($period) {
            return Qs::jsonStoreOk();
        } else {
            return Qs::json(false,'failed to create message');
        }
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
        $id = Qs::decodeHash($id);
        $periods = $this->periods->APFind($id);
        $studyModes = $this->periods->getStudyModes();
        $intakes = $this->periods->getIntakes();

        return view('pages.academicPeriodInformation.edit', compact('periods', 'studyModes', 'intakes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(APinformationUpdate $request, string $id)
    {
        $data = $request->only(['academic_period_intake_id', 'study_mode_id', 'view_results_threshold',
            'exam_slip_threshold', 'registration_threshold', 'late_registration_end_date', 'late_registration_date',
            'registration_date']);

        $data['late_registration_end_date'] = date('Y-m-d', strtotime($data['late_registration_end_date']));
        $data['late_registration_date'] = date('Y-m-d', strtotime($data['late_registration_date']));
        $data['registration_date'] = date('Y-m-d', strtotime($data['registration_date']));
        $period = $this->periods->APUpdate($id,$data);

        if ($period) {
            return Qs::jsonStoreOk();
        } else {
            return Qs::json(false,'failed to create message');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
